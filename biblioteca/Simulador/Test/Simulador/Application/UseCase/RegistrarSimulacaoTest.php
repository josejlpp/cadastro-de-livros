<?php

namespace Biblioteca\Simulador\Test\Simulador\Application\UseCase;

use Biblioteca\Simulador\Application\UseCase\RegistrarSimulacao;
use Biblioteca\Simulador\Domain\Dto\SimulacaoDto;
use Biblioteca\Simulador\Domain\Entity\Simulacao;
use Biblioteca\Simulador\Domain\Entity\TeseCollection;
use Biblioteca\Simulador\Domain\Persistence\Dao\SimulacaoDao;
use Biblioteca\Simulador\Domain\Persistence\Repository\SimulacaoRepository;
use Biblioteca\Simulador\Domain\Service\CalculadoraPotencial;
use Biblioteca\Simulador\Domain\Service\SimulacaoService;
use DomainException;
use PHPUnit\Framework\TestCase;

class RegistrarSimulacaoTest extends TestCase
{
    private function useCase(SimulacaoDao $dao): RegistrarSimulacao
    {
        $teses = TeseCollection::fromArray([
            'inss' => [
                'titulo' => 'INSS',
                'base' => 'folha_pagamento',
                'percentuais' => ['lucro_presumido' => [0.01, 0.03]],
            ],
        ]);

        return new RegistrarSimulacao(
            new SimulacaoRepository($dao),
            new SimulacaoService(),
            new CalculadoraPotencial($teses)
        );
    }

    private function dto(string $email = 'joao@example.com'): SimulacaoDto
    {
        return new SimulacaoDto(
            nome: 'João Lima',
            email: $email,
            telefone: '85988887777',
            empresa: 'Clínica Exemplo',
            cnpj: null,
            regime: 'lucro_presumido',
            segmento: 'saude',
            faturamentoMensal: 400000,
            folhaPagamentoMensal: 100000
        );
    }

    public function testCalculaERegistraSimulacao()
    {
        $dao = $this->createMock(SimulacaoDao::class);
        $dao->expects($this->once())
            ->method('adicionar')
            ->willReturnCallback(fn (Simulacao $simulacao) => $simulacao->adicionarIdentificacao(7, 'uuid-teste'));

        $simulacao = $this->useCase($dao)->handle($this->dto());

        $this->assertEquals(7, $simulacao->id);
        $this->assertEquals('uuid-teste', $simulacao->uuid);
        $this->assertEquals('Clínica Exemplo', $simulacao->contato->empresa);
        $this->assertCount(1, $simulacao->resultado);
        $this->assertEquals(1000 * 60, $simulacao->resultado->totalMinimo());
        $this->assertEquals(3000 * 60, $simulacao->resultado->totalMaximo());
    }

    public function testNaoRegistraSimulacaoComDadosInvalidos()
    {
        $this->expectException(DomainException::class);

        $dao = $this->createMock(SimulacaoDao::class);
        $dao->expects($this->never())->method('adicionar');

        $this->useCase($dao)->handle($this->dto(email: 'email-invalido'));
    }
}
