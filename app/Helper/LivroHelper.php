<?php

namespace App\Helper;

use App\Models\Assunto;
use App\Models\Autor;
use Biblioteca\Livros\Domain\Dto\AssuntoCollectionDto;
use Biblioteca\Livros\Domain\Dto\AssuntoDto;
use Biblioteca\Livros\Domain\Dto\AutorCollectionDto;
use Biblioteca\Livros\Domain\Dto\AutorDto;
use Biblioteca\Livros\Domain\Dto\LivroDto;

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

    public static function buildLivroDtoFromRequest(array $request, int|null $id = null): LivroDto
    {
        $valor = self::formatMoney($request['valor']);
        $autorCollectionDto = self::buildAutorCollectinoDtoFromRequest($request);
        $assuntoCollectionDto = self::buildAssuntoCollectinoDtoFromRequest($request);

        return new LivroDto(
            Codl: $id,
            titulo: $request['titulo'],
            editora: $request['editora'],
            edicao: $request['edicao'],
            anoPublicacao: $request['ano_publicacao'],
            valor: $valor,
            assuntosDto: $assuntoCollectionDto,
            autoresDto: $autorCollectionDto
        );
    }

    private static function formatMoney(string $originalValue): float
    {
        $value = str_replace('.', '', $originalValue);
        return (float) str_replace(',', '.', $value);
    }

    private static function buildAutorCollectinoDtoFromRequest(array $request): AutorCollectionDto
    {
        $autores = [];
        $autorModelCollection = Autor::whereIn('CodAu', array_values($request['autor_id']))->get();

        foreach ($autorModelCollection as $autorModel) {
            $autores[] = new AutorDto(
                CodAu: $autorModel->CodAu,
                nome: $autorModel->Nome
            );
        }
        return new AutorCollectionDto(...$autores);
    }

    private static function buildAssuntoCollectinoDtoFromRequest(array $request): AssuntoCollectionDto
    {
        $assuntos = [];
        $assuntoModelCollection = Assunto::whereIn('CodAs', array_values($request['assunto_id']))->get();

        foreach ($assuntoModelCollection as $assuntoModel) {
            $assuntos[] = new AssuntoDto(
                CodAs: $assuntoModel->CodAs,
                descricao: $assuntoModel->Descricao
            );
        }
        return new AssuntoCollectionDto(...$assuntos);
    }
}
