<?php

namespace Biblioteca\Livros\Domain\Dto;

class LivroDto
{
    public function __construct(
        public int|null $Codl,
        public string $titulo,
        public string $editora,
        public int $edicao,
        public int $anoPublicacao,
        public float $valor,
        public AssuntoCollectionDto $assuntosDto,
        public AutorCollectionDto $autoresDto
    )
    {
    }
}
