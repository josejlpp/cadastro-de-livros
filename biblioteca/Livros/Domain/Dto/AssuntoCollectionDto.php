<?php

namespace Biblioteca\Livros\Domain\Dto;

class AssuntoCollectionDto
{
    public array $assuntos = [];
    public function __construct(AssuntoDto ...$assuntos)
    {
        $this->assuntos = array_merge($this->assuntos, $assuntos);
    }
}
