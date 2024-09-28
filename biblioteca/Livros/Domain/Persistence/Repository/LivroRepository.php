<?php

namespace Biblioteca\Livros\Domain\Persistence\Repository;

use DomainException;
use Biblioteca\Livros\Domain\Entity\Livro;
use Biblioteca\Livros\Domain\Persistence\Dao\LivroDao;

readonly class LivroRepository
{
    public function __construct(private LivroDao $livroDao)
    {
    }

    public function adicionar(Livro $livro): void
    {
        if (isset($livro->Codl)) {
            throw new DomainException('Livro já possui código não pode ser adicionado');
        }
        $this->livroDao->adicionar($livro);
    }

    public function atualizar(Livro $livro): void
    {
        if (!isset($livro->Codl)) {
            throw new DomainException('Livro sem código não pode ser atualizado');
        }
        $this->livroDao->atualizar($livro->Codl, $livro);
    }

    public function excluir(Livro $livro): void
    {
        if (!isset($livro->Codl)) {
            throw new DomainException('Livro sem código não pode ser excluído');
        }
        $this->livroDao->excluir($livro->Codl);
    }
}
