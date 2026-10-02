<?php

namespace Biblioteca\Simulador\Test\Simulador\Domain\Entity;

use Biblioteca\Simulador\Domain\Entity\PerfilTributario;
use Biblioteca\Simulador\Domain\Entity\Tese;
use Biblioteca\Simulador\Domain\Entity\TeseCollection;
use Biblioteca\Simulador\Domain\Enum\BaseCalculo;
use Biblioteca\Simulador\Domain\Enum\RegimeTributario;
use Biblioteca\Simulador\Domain\Enum\Segmento;
use DomainException;
use PHPUnit\Framework\TestCase;

class TeseEntityTest extends TestCase
{
    private function tese(array $percentuais, array $segmentos = []): Tese
    {
        return new Tese(
            chave: 'tese_teste',
            titulo: 'Tese de teste',
            descricao: 'Descrição',
            fundamento: 'Fundamento',
            base: BaseCalculo::Faturamento,
            percentuais: $percentuais,
            segmentos: $segmentos
        );
    }

    public function testAplicavelSomenteAosRegimesComPercentual()
    {
        $tese = $this->tese(['lucro_real' => [0.01, 0.02]]);

        $this->assertTrue($tese->aplicavelA(new PerfilTributario(RegimeTributario::LucroReal, Segmento::Comercio, 1000)));
        $this->assertFalse($tese->aplicavelA(new PerfilTributario(RegimeTributario::LucroPresumido, Segmento::Comercio, 1000)));
        $this->assertEquals(0.01, $tese->percentualMinimo(RegimeTributario::LucroReal));
        $this->assertEquals(0.02, $tese->percentualMaximo(RegimeTributario::LucroReal));
    }

    public function testAplicavelSomenteAosSegmentosInformados()
    {
        $tese = $this->tese(['lucro_real' => [0.01, 0.02]], [Segmento::Industria]);

        $this->assertTrue($tese->aplicavelA(new PerfilTributario(RegimeTributario::LucroReal, Segmento::Industria, 1000)));
        $this->assertFalse($tese->aplicavelA(new PerfilTributario(RegimeTributario::LucroReal, Segmento::Servicos, 1000)));
    }

    public function testPercentualDeRegimeNaoAtendidoLancaExcecao()
    {
        $this->expectException(DomainException::class);

        $this->tese(['lucro_real' => [0.01, 0.02]])->percentualMinimo(RegimeTributario::SimplesNacional);
    }

    public function testFaixaComMinimoMaiorQueMaximoEhInvalida()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Tese tese_teste possui faixa de percentual inválida para lucro_real');

        $this->tese(['lucro_real' => [0.05, 0.02]]);
    }

    public function testFaixaDeveSerUmaListaDeDoisValores()
    {
        $this->expectException(DomainException::class);

        $this->tese(['lucro_real' => ['min' => 0.01, 'max' => 0.02]]);
    }

    public function testRegimeInvalido()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Tese tese_teste possui regime inválido: lucro_imaginario');

        $this->tese(['lucro_imaginario' => [0.01, 0.02]]);
    }

    public function testCollectionAPartirDeArrayRejeitaBaseInvalida()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Tese x possui base de cálculo inválida');

        TeseCollection::fromArray(['x' => ['titulo' => 'X', 'base' => 'lucro', 'percentuais' => ['lucro_real' => [0, 0.1]]]]);
    }

    public function testCollectionAPartirDeArrayRejeitaSegmentoInvalido()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Tese x possui segmento inválido: mineracao');

        TeseCollection::fromArray(['x' => [
            'titulo' => 'X',
            'base' => 'faturamento',
            'segmentos' => ['mineracao'],
            'percentuais' => ['lucro_real' => [0, 0.1]],
        ]]);
    }

    public function testConfiguracaoPadraoDoSimuladorEhValida()
    {
        $config = require __DIR__ . '/../../../../../../config/simulador.php';

        $teses = TeseCollection::fromArray($config['teses']);

        $this->assertGreaterThan(0, count($teses));
    }

    public function testPerfisPorBaseListaRegimeESegmentoAtendidos()
    {
        $teses = TeseCollection::fromArray([
            'icms' => [
                'titulo' => 'ICMS',
                'base' => 'icms',
                'segmentos' => ['comercio'],
                'percentuais' => ['lucro_real' => [0, 0.1], 'lucro_presumido' => [0, 0.1]],
            ],
            'folha' => [
                'titulo' => 'Folha',
                'base' => 'folha_pagamento',
                'percentuais' => ['lucro_real' => [0, 0.1]],
            ],
        ]);

        $perfis = $teses->perfisPorBase();

        $this->assertEquals(['lucro_real:comercio', 'lucro_presumido:comercio'], $perfis['icms']);
        $this->assertCount(count(Segmento::cases()), $perfis['folha_pagamento']);
        $this->assertArrayNotHasKey('energia', $perfis);
    }
}
