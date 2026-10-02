<?php

namespace Biblioteca\Simulador\Test\Simulador\Domain\Entity;

use Biblioteca\Simulador\Domain\Entity\PerfilTributario;
use Biblioteca\Simulador\Domain\Enum\RegimeTributario;
use Biblioteca\Simulador\Domain\Enum\Segmento;
use DomainException;
use PHPUnit\Framework\TestCase;

class PerfilTributarioEntityTest extends TestCase
{
    public function testCriarPerfilValido()
    {
        $perfil = new PerfilTributario(
            regime: RegimeTributario::SimplesNacional,
            segmento: Segmento::Comercio,
            faturamentoMensal: 100000,
            percentualMonofasico: 40
        );

        $this->assertEquals(60, $perfil->meses);
        $this->assertEquals(40000, $perfil->receitaMonofasicaMensal());
        $this->assertEquals(0, $perfil->folhaPagamentoMensal);
    }

    public function testFaturamentoDeveSerMaiorQueZero()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Faturamento mensal deve ser maior que zero');

        new PerfilTributario(RegimeTributario::LucroReal, Segmento::Industria, 0);
    }

    public function testValoresNaoPodemSerNegativos()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Folha de pagamento não pode ser negativo');

        new PerfilTributario(RegimeTributario::LucroReal, Segmento::Industria, 1000, folhaPagamentoMensal: -1);
    }

    public function testPercentualMonofasicoDeveEstarEntreZeroECem()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Percentual de produtos monofásicos deve estar entre 0 e 100');

        new PerfilTributario(RegimeTributario::SimplesNacional, Segmento::Comercio, 1000, percentualMonofasico: 101);
    }

    public function testPeriodoNaoPodeUltrapassarPrazoPrescricional()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Período de análise deve estar entre 1 e 60 meses');

        new PerfilTributario(RegimeTributario::LucroPresumido, Segmento::Servicos, 1000, meses: 61);
    }

    public function testPeriodoDeveTerAoMenosUmMes()
    {
        $this->expectException(DomainException::class);

        new PerfilTributario(RegimeTributario::LucroPresumido, Segmento::Servicos, 1000, meses: 0);
    }
}
