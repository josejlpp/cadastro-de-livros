<?php

namespace Biblioteca\Livros\Domain\Entity;

use Countable;
use DomainException;

class AssuntoCollection implements Countable
{
    private array $assuntos = [];

    public function adicionar(Assunto $assunto): void
    {
        $this->validate($assunto);
        $this->assuntos[$assunto->CodAs] = $assunto;
    }

    private function validate(Assunto $assunto): void
    {
        if (isset($this->assuntos[$assunto->CodAs])) {
            throw new DomainException('Assunto já adicionado');
        }
    }

    public function count(): int
    {
        return count($this->assuntos);
    }

    public function getByCodAs($CodAs)
    {
        if (!isset($this->assuntos[$CodAs])) {
            throw new DomainException('Assunto não encontrado');
        }
        return $this->assuntos[$CodAs];
    }
}
