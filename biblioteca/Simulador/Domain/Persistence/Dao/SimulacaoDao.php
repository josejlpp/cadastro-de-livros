<?php

namespace Biblioteca\Simulador\Domain\Persistence\Dao;

use Biblioteca\Simulador\Domain\Entity\Simulacao;

interface SimulacaoDao
{
    public function adicionar(Simulacao $simulacao): void;
}
