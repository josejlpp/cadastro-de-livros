<?php

namespace Biblioteca\Livros\Domain\Dto;

class AssuntoDto
{
    public function __construct(
        public int|null $codAs,
        public string $descricao,
    )
    {
    }
}
