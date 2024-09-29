<?php

namespace App\Helper;

use Biblioteca\Livros\Domain\Dto\AssuntoDto;

class LivroHelper
{
    public static function makeAssuntoDtoFromRequest(array $request, int|null $id = null): AssuntoDto
    {
        return new AssuntoDto(
            CodAs: $id,
            descricao: $request['descricao']
        );
    }
}
