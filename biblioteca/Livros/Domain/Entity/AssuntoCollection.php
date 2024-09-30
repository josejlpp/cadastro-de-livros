<?php

namespace Biblioteca\Livros\Domain\Entity;

use Countable;
use DomainException;
use Iterator;

class AssuntoCollection implements Countable, Iterator
{
    private array $assuntos = [];

    public function adicionar(Assunto $assunto): void
    {
        $this->validate($assunto);
        $this->assuntos[$assunto->CodAs] = $assunto;
    }

    private function validate(Assunto $assunto): void
    {
        if (!$assunto->CodAs) {
            throw new DomainException('Código do assunto não pode ser nulo');
        }

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

    public function current(): mixed
    {
        return $this->assuntos[key($this->assuntos)];
    }

    public function next(): void
    {
        next($this->assuntos);
    }

    public function key(): int
    {
        return key($this->assuntos);
    }

    public function valid(): bool
    {
        return key($this->assuntos) !== null;
    }

    public function rewind(): void
    {
        reset($this->assuntos);
    }
}
