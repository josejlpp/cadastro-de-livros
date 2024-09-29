<?php

namespace Biblioteca\Livros\Domain\Service;

use Biblioteca\Livros\Domain\Dto\AssuntoCollectionDto;
use Biblioteca\Livros\Domain\Dto\AssuntoDto;
use Biblioteca\Livros\Domain\Dto\AutorCollectionDto;
use Biblioteca\Livros\Domain\Dto\AutorDto;
use Biblioteca\Livros\Domain\Dto\LivroDto;
use Biblioteca\Livros\Domain\Entity\Assunto;
use Biblioteca\Livros\Domain\Entity\AssuntoCollection;
use Biblioteca\Livros\Domain\Entity\Autor;
use Biblioteca\Livros\Domain\Entity\AutorCollection;
use Biblioteca\Livros\Domain\Entity\Livro;

class LivroService
{
    public function buildLivroEntityFromDto(LivroDto $dto): Livro
    {
        $autores = $this->buildAutoresCollection($dto->autoresDto);
        $assuntos = $this->buildAssuntosCollection($dto->assuntosDto);

        $livro = new Livro(
            titulo: $dto->titulo,
            editora: $dto->editora,
            edicao: $dto->edicao,
            anoPublicacao: $dto->anoPublicacao,
            valor: $dto->valor,
            assuntos: $assuntos,
            autores: $autores
        );

        if ($dto->Codl) {
            $livro->adicionarCodl($dto->Codl);
        }

        return $livro;
    }

    private function buildAutoresCollection(AutorCollectionDto $dto): AutorCollection
    {
        $autores = new AutorCollection();
        foreach ($dto->autores as $autorDto) {
            $autores->adicionar(
                new Autor($autorDto->nome, $autorDto->CodAu)
            );
        }

         return $autores;
    }

    private function buildAssuntosCollection(AssuntoCollectionDto $assuntosDto): AssuntoCollection
    {
        $assuntos = new AssuntoCollection();
        foreach ($assuntosDto->assuntos as $assuntoDto) {
            $assuntos->adicionar(
                new Assunto($assuntoDto->descricao, $assuntoDto->CodAs)
            );
        }

        return $assuntos;
    }

    public function buildAutorEntityFromDto(AutorDto $autorDto): Autor
    {
        return new Autor($autorDto->nome, $autorDto->CodAu);
    }

    public function buildAssuntoEntityFromDto(AssuntoDto $assuntoDto): Assunto
    {
        return new Assunto($assuntoDto->descricao, $assuntoDto->CodAs);
    }
}
