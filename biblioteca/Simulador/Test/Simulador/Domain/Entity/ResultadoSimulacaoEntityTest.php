<?php

namespace Biblioteca\Simulador\Test\Simulador\Domain\Entity;

use Biblioteca\Simulador\Domain\Entity\Oportunidade;
use Biblioteca\Simulador\Domain\Entity\ResultadoSimulacao;
use DomainException;
use PHPUnit\Framework\TestCase;

class ResultadoSimulacaoEntityTest extends TestCase
{
    private function oportunidade(string $chave, float $base, float $selic = 0): Oportunidade
    {
        return new Oportunidade(
            chave: $chave,
            titulo: $chave,
            descricao: '',
            fundamento: '',
            baseDescricao: 'Faturamento mensal',
            baseMensal: $base,
            baseEstimada: false,
            percentualMinimo: 0.01,
            percentualMaximo: 0.02,
            meses: 60,
            selicMensal: $selic
        );
    }

    public function testCalculaValoresDaOportunidade()
    {
        $oportunidade = $this->oportunidade('a', 100000, 0.01);

        $this->assertEquals(1000, $oportunidade->valorMensalMinimo());
        $this->assertEquals(2000, $oportunidade->valorMensalMaximo());
        $this->assertEquals(60000, $oportunidade->principalMinimo());
        $this->assertEquals(120000, $oportunidade->principalMaximo());
        // SELIC simples acumulada: 1000 × 1% × (1 + 2 + ... + 60) = 1000 × 0,01 × 1830
        $this->assertEquals(18300, $oportunidade->correcaoMinima());
        $this->assertEquals(36600, $oportunidade->correcaoMaxima());
        $this->assertEquals(78300, $oportunidade->totalMinimo());
        $this->assertEquals(156600, $oportunidade->totalMaximo());
    }

    public function testSomaTotaisDasOportunidades()
    {
        $resultado = new ResultadoSimulacao(60);
        $resultado->adicionar($this->oportunidade('a', 100000));
        $resultado->adicionar($this->oportunidade('b', 50000));

        $this->assertCount(2, $resultado);
        $this->assertTrue($resultado->possuiOportunidades());
        $this->assertEquals(1500, $resultado->valorMensalMinimo());
        $this->assertEquals(3000, $resultado->valorMensalMaximo());
        $this->assertEquals(90000, $resultado->totalMinimo());
        $this->assertEquals(180000, $resultado->totalMaximo());
        $this->assertEquals(0, $resultado->correcaoMaxima());
    }

    public function testResultadoVazio()
    {
        $resultado = new ResultadoSimulacao(60);

        $this->assertFalse($resultado->possuiOportunidades());
        $this->assertEquals(0, $resultado->totalMaximo());
        $this->assertEquals([], $resultado->toArray()['oportunidades']);
    }

    public function testNaoPermiteOportunidadeDuplicada()
    {
        $this->expectException(DomainException::class);

        $resultado = new ResultadoSimulacao(60);
        $resultado->adicionar($this->oportunidade('a', 100000));
        $resultado->adicionar($this->oportunidade('a', 100000));
    }

    public function testToArrayContemOportunidadesETotais()
    {
        $resultado = new ResultadoSimulacao(60, 0.01);
        $resultado->adicionar($this->oportunidade('a', 100000, 0.01));

        $array = $resultado->toArray();

        $this->assertEquals(60, $array['meses']);
        $this->assertEquals(0.01, $array['selic_mensal']);
        $this->assertEquals('a', $array['oportunidades'][0]['chave']);
        $this->assertEquals(156600, $array['oportunidades'][0]['total_maximo']);
        $this->assertEquals(156600, $array['totais']['total_maximo']);
        $this->assertEquals(18300, $array['totais']['correcao_minima']);
    }

    public function testOportunidadeSemBaseEhInvalida()
    {
        $this->expectException(DomainException::class);

        $this->oportunidade('a', 0);
    }
}
