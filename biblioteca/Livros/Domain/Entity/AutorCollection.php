<?php

namespace Biblioteca\Livros\Domain\Entity;

use Iterator;
use Countable;
use DomainException;

class AutorCollection implements Countable, Iterator
{
    private array $autores = [];

    public function adicionar(Autor $autor): void
    {
        $this->validate($autor);
        $this->autores[$autor->CodAu] = $autor;
    }

    private function validate(Autor $autor)
    {
        if (!$autor->CodAu) {
            throw new DomainException('Código do autor não pode ser nulo');
        }

        if (isset($this->autores[$autor->CodAu])) {
            throw new DomainException('Autor já adicionado');
        }
    }

    public function count(): int
    {
        return count($this->autores);
    }

    public function getByCodAu($CodAu): Autor
    {
        if (!isset($this->autores[$CodAu])) {
            throw new DomainException('Autor não encontrado');
        }
        return $this->autores[$CodAu];
    }

    public function current(): mixed
    {
        return $this->autores[key($this->autores)];
    }

    public function next(): void
    {
        next($this->autores);
    }

    public function key(): int
    {
        return key($this->autores);
    }

    public function valid(): bool
    {
        return key($this->autores) !== null;
    }

    public function rewind(): void
    {
        reset($this->autores);
    }
}
