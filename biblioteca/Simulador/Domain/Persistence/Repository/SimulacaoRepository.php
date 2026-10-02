<?php

namespace Biblioteca\Simulador\Domain\Persistence\Repository;

use Biblioteca\Simulador\Domain\Entity\Simulacao;
use Biblioteca\Simulador\Domain\Persistence\Dao\SimulacaoDao;
use DomainException;

readonly class SimulacaoRepository
{
    public function __construct(private SimulacaoDao $simulacaoDao)
    {
    }

    public function adicionar(Simulacao $simulacao): void
    {
        if (isset($simulacao->id)) {
            throw new DomainException('Simulação já registrada não pode ser adicionada novamente');
        }
        $this->simulacaoDao->adicionar($simulacao);
    }
}
