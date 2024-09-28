<?php

namespace Biblioteca\Livros\Domain\Persistence\Dao;

use Biblioteca\Livros\Domain\Entity\Assunto;

interface AssuntoDao
{
    public function adicionar(Assunto $assunto): void;
    public function atualizar(int $id, Assunto $assunto): void;
    public function excluir(int $id): void;
}
