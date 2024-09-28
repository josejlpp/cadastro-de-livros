<?php

namespace Biblioteca\Livros\Domain\Persistence\Repository;

use Biblioteca\Livros\Domain\Entity\Assunto;
use Biblioteca\Livros\Domain\Persistence\Dao\AssuntoDao;
use DomainException;

readonly class AssuntoRepository
{
    public function __construct(private AssuntoDao $assuntoDao)
    {
    }

    public function adicionar(Assunto $assunto): void
    {
        if (isset($assunto->CodAs)) {
            throw new DomainException('Assunto já possui código não pode ser adicionado');
        }
        $this->assuntoDao->adicionar($assunto);
    }

    public function atualizar(Assunto $assunto): void
    {
        if (!isset($assunto->CodAs)) {
            throw new DomainException('Assunto sem código não pode ser atualizado');
        }
        $this->assuntoDao->atualizar($assunto->CodAs, $assunto);
    }

    public function excluir(Assunto $assunto): void
    {
        if (!isset($assunto->CodAs)) {
            throw new DomainException('Assunto sem código não pode ser excluído');
        }
        $this->assuntoDao->excluir($assunto->CodAs);
    }
}
