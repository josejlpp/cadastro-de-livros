<?php

namespace App\Helper;

use Biblioteca\Livros\Domain\Dto\AssuntoCollectionDto;

class LivroHelper
{
    public static function makeAssuntoDtoFromRequest(array $request): AssuntoCollectionDto
    {
        return new AssuntoCollectionDto(
            $request['nome'],
            $request['descricao']
        );
    }
}
