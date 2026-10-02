<?php

namespace Biblioteca\Simulador\Test\Simulador\Domain\Service;

use Biblioteca\Simulador\Domain\Entity\PerfilTributario;
use Biblioteca\Simulador\Domain\Entity\TeseCollection;
use Biblioteca\Simulador\Domain\Enum\RegimeTributario;
use Biblioteca\Simulador\Domain\Enum\Segmento;
use Biblioteca\Simulador\Domain\Service\CalculadoraPotencial;
use DomainException;
use PHPUnit\Framework\TestCase;

class CalculadoraPotencialTest extends TestCase
{
    private TeseCollection $teses;

    protected function setUp(): void
    {
        $this->teses = TeseCollection::fromArray([
            'exclusao_icms' => [
                'titulo' => 'Exclusão do ICMS',
                'base' => 'icms',
                'base_estimada_sobre_faturamento' => 0.12,
                'segmentos' => ['comercio', 'industria'],
                'percentuais' => [
                    'lucro_presumido' => [0.0110, 0.0365],
                    'lucro_real' => [0.0278, 0.0925],
                ],
            ],
            'inss' => [
                'titulo' => 'INSS',
                'base' => 'folha_pagamento',
                'percentuais' => ['lucro_real' => [0.01, 0.03]],
            ],
            'monofasico' => [
                'titulo' => 'Monofásico',
                'base' => 'receita_monofasica',
                'percentuais' => ['simples_nacional' => [0.0062, 0.0155]],
            ],
            'modulada' => [
                'titulo' => 'Tese modulada',
                'base' => 'energia',
                'meses_maximo' => 12,
                'percentuais' => ['lucro_real' => [0.05, 0.10]],
            ],
        ]);
    }

    public function testCalculaOportunidadesAplicaveisAoPerfil()
    {
        $calculadora = new CalculadoraPotencial($this->teses);
        $perfil = new PerfilTributario(
            regime: RegimeTributario::LucroReal,
            segmento: Segmento::Industria,
            faturamentoMensal: 1000000,
            folhaPagamentoMensal: 200000,
            icmsMensal: 100000
        );

        $resultado = $calculadora->calcular($perfil);
        $oportunidades = iterator_to_array($resultado);

        $this->assertEquals(['exclusao_icms', 'inss'], array_keys($oportunidades));

        $icms = $oportunidades['exclusao_icms'];
        $this->assertFalse($icms->baseEstimada);
        $this->assertEquals(2780, $icms->valorMensalMinimo());
        $this->assertEquals(9250, $icms->valorMensalMaximo());
        $this->assertEquals(555000, $icms->principalMaximo());

        $this->assertEquals(2000, $oportunidades['inss']->valorMensalMinimo());
        $this->assertEquals(6000 * 60, $oportunidades['inss']->principalMaximo());

        $this->assertEquals((2780 + 2000) * 60, $resultado->totalMinimo());
        $this->assertEquals((9250 + 6000) * 60, $resultado->totalMaximo());
    }

    public function testEstimaBaseAPartirDoFaturamentoQuandoNaoInformada()
    {
        $calculadora = new CalculadoraPotencial($this->teses);
        $perfil = new PerfilTributario(RegimeTributario::LucroPresumido, Segmento::Comercio, 500000);

        $oportunidades = iterator_to_array($calculadora->calcular($perfil));

        $this->assertCount(1, $oportunidades);
        $this->assertTrue($oportunidades['exclusao_icms']->baseEstimada);
        $this->assertEquals(60000, $oportunidades['exclusao_icms']->baseMensal);
        $this->assertEquals(2190, $oportunidades['exclusao_icms']->valorMensalMaximo());
    }

    public function testIgnoraTesesForaDoSegmentoOuSemBase()
    {
        $calculadora = new CalculadoraPotencial($this->teses);
        $perfil = new PerfilTributario(RegimeTributario::LucroReal, Segmento::Servicos, 300000);

        $resultado = $calculadora->calcular($perfil);

        $this->assertFalse($resultado->possuiOportunidades());
    }

    public function testCalculaMonofasicoSobreParcelaDaReceita()
    {
        $calculadora = new CalculadoraPotencial($this->teses);
        $perfil = new PerfilTributario(
            regime: RegimeTributario::SimplesNacional,
            segmento: Segmento::Comercio,
            faturamentoMensal: 200000,
            percentualMonofasico: 50,
            meses: 24
        );

        $monofasico = iterator_to_array($calculadora->calcular($perfil))['monofasico'];

        $this->assertEquals(100000, $monofasico->baseMensal);
        $this->assertEquals(620, $monofasico->valorMensalMinimo());
        $this->assertEquals(1550 * 24, $monofasico->principalMaximo());
    }

    public function testLimitaPeriodoDaTeseComMesesMaximo()
    {
        $calculadora = new CalculadoraPotencial($this->teses);
        $perfil = new PerfilTributario(
            regime: RegimeTributario::LucroReal,
            segmento: Segmento::Servicos,
            faturamentoMensal: 300000,
            energiaMensal: 10000
        );

        $modulada = iterator_to_array($calculadora->calcular($perfil))['modulada'];

        $this->assertEquals(12, $modulada->meses);
        $this->assertEquals(1000 * 12, $modulada->principalMaximo());
    }

    public function testAplicaCorrecaoPelaSelic()
    {
        $calculadora = new CalculadoraPotencial($this->teses, 0.01);
        $perfil = new PerfilTributario(
            regime: RegimeTributario::LucroReal,
            segmento: Segmento::Servicos,
            faturamentoMensal: 300000,
            folhaPagamentoMensal: 100000
        );

        $resultado = $calculadora->calcular($perfil);

        // 1.000/mês × 1% × (1 + 2 + ... + 60)
        $this->assertEquals(18300, $resultado->correcaoMinima());
        $this->assertEquals(60000 + 18300, $resultado->totalMinimo());
        $this->assertEquals(0.01, $resultado->selicMensal);
    }

    public function testSelicNegativaEhInvalida()
    {
        $this->expectException(DomainException::class);

        new CalculadoraPotencial($this->teses, -0.01);
    }
}
