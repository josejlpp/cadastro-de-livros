<?php

namespace Biblioteca\Simulador\Test\Simulador\Domain\Persistence;

use Biblioteca\Simulador\Domain\Entity\Contato;
use Biblioteca\Simulador\Domain\Entity\PerfilTributario;
use Biblioteca\Simulador\Domain\Entity\ResultadoSimulacao;
use Biblioteca\Simulador\Domain\Entity\Simulacao;
use Biblioteca\Simulador\Domain\Enum\RegimeTributario;
use Biblioteca\Simulador\Domain\Enum\Segmento;
use Biblioteca\Simulador\Domain\Persistence\Dao\SimulacaoDao;
use Biblioteca\Simulador\Domain\Persistence\Repository\SimulacaoRepository;
use DomainException;
use PHPUnit\Framework\TestCase;

class SimulacaoRepositoryTest extends TestCase
{
    private function simulacao(): Simulacao
    {
        return new Simulacao(
            new Contato('Maria Souza', 'maria@example.com', '85999998888', 'Empresa Ltda'),
            new PerfilTributario(RegimeTributario::LucroReal, Segmento::Industria, 100000),
            new ResultadoSimulacao(60)
        );
    }

    public function testAdicionarSimulacao()
    {
        $simulacao = $this->simulacao();
        $dao = $this->createMock(SimulacaoDao::class);
        $dao->expects($this->once())->method('adicionar')->with($simulacao);

        (new SimulacaoRepository($dao))->adicionar($simulacao);
    }

    public function testNaoAdicionaSimulacaoJaRegistrada()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Simulação já registrada não pode ser adicionada novamente');

        $simulacao = $this->simulacao();
        $simulacao->adicionarIdentificacao(1, 'b3f1c1f4-6a3e-4e0b-9f43-2f1d0f5a1c11');

        $dao = $this->createMock(SimulacaoDao::class);
        $dao->expects($this->never())->method('adicionar');

        (new SimulacaoRepository($dao))->adicionar($simulacao);
    }
}
