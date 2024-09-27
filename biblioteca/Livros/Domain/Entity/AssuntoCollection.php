<?php

namespace Biblioteca\Livros\Domain\Entity;

use Countable;
use DomainException;
use Iterator;

class AssuntoCollection implements Iterator, Countable
{
    private array $assuntos = [];
    private int $position = 0;

    public function adicionar(Assunto $assunto): void
    {
        $this->validate($assunto);
        $this->assuntos[$assunto->codAs] = $assunto;
    }

    private function validate(Assunto $assunto): void
    {
        if (isset($this->assuntos[$assunto->codAs])) {
            throw new DomainException('Assunto já adicionado');
        }
    }

    public function current(): mixed
    {
        return $this->assuntos[$this->position];
    }

    public function next(): void
    {
        $this->position++;
    }

    public function key(): mixed
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->assuntos[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function count(): int
    {
        return count($this->assuntos);
    }

    public function getAll(): array
    {
        return $this->assuntos;
    }
}
