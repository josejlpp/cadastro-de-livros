<?php

namespace Biblioteca\Livros\Domain\Persistence\Dao;

use Biblioteca\Livros\Domain\Entity\Autor;

interface AutorDao
{
    public function adicionar(Autor $autor): void;
    public function atualizar(int $id, Autor $autor): void;
    public function excluir(int $id): void;
}
