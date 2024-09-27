<?php

namespace Biblioteca\Livros\Domain\Entity;

use Countable;
use DomainException;
use Iterator;

class AutorCollection implements Iterator, Countable
{
    private array $autores = [];
    private int $position = 0;

    public function adicionar(Autor $autor): void
    {
        $this->validate($autor);
        $this->autores[$autor->CodAu] = $autor;
    }

    private function validate(Autor $autor)
    {
        if (isset($this->autores[$autor->CodAu])) {
            throw new DomainException('Autor já adicionado');
        }
    }

    public function current(): mixed
    {
        return $this->autores[$this->position];
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
        return isset($this->autores[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function count(): int
    {
        return count($this->autores);
    }

    public function getAll(): array
    {
        return $this->autores;
    }
}
