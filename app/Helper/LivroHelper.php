<?php

namespace App\Helper;

use Biblioteca\Livros\Domain\Dto\AssuntoDto;
use Biblioteca\Livros\Domain\Dto\AutorDto;

class LivroHelper
{
    public static function makeAssuntoDtoFromRequest(array $request, int|null $id = null): AssuntoDto
    {
        return new AssuntoDto(
            CodAs: $id,
            descricao: $request['descricao']
        );
    }

    public static function makeAutorDtoFromRequest(array $request, int|null $id = null): AutorDto
    {
        return new AutorDto(
            CodAu: $id,
            nome: $request['nome']
        );
    }
}
