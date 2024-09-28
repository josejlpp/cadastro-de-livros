<?php

namespace Biblioteca\Livros\Domain\Persistence\Dao;

use Biblioteca\Livros\Domain\Entity\Livro;

interface LivroDao
{
    public function adicionar(Livro $livro): void;
    public function atualizar(int $id, Livro $livro): void;
    public function excluir(int $id): void;
}
