<?php

namespace Biblioteca\Livros\Domain\Dto;

class AutorDto
{
    public function __construct(
        public int|null $CodAu,
        public string $nome,
    )
    {
    }
}
