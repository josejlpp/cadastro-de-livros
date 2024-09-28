<?php

namespace Biblioteca\Livros\Domain\Dto;

class AutorCollectionDto
{
    public array $autores = [];
    public function __construct(AutorDto ...$autores)
    {
        $this->autores = array_merge($this->autores, $autores);
    }
}
