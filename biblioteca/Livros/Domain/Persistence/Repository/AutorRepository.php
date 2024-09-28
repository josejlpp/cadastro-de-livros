<?php

namespace Biblioteca\Livros\Domain\Persistence\Repository;

use Biblioteca\Livros\Domain\Entity\Autor;
use Biblioteca\Livros\Domain\Persistence\Dao\AutorDao;
use DomainException;

readonly class AutorRepository
{
    public function __construct(private AutorDao $autorDao)
    {
    }

    public function adicionar(Autor $autor): void
    {
        if (isset($autor->CodAu)) {
            throw new DomainException('Autor já possui código não pode ser adicionado');
        }
        $this->autorDao->adicionar($autor);
    }

    public function atualizar(Autor $autor): void
    {
        if (!isset($autor->CodAu)) {
            throw new DomainException('Autor sem código não pode ser atualizado');
        }
        $this->autorDao->atualizar($autor->CodAu, $autor);
    }

    public function excluir(Autor $autor): void
    {
        if (!isset($autor->CodAu)) {
            throw new DomainException('Autor sem código não pode ser excluído');
        }
        $this->autorDao->excluir($autor->CodAu);
    }
}
