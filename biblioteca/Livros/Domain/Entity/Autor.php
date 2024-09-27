<?php

namespace Biblioteca\Livros\Domain\Entity;

use DomainException;

class Autor
{

    public function __construct(
        public string $nome,
        public int|null $CodAu = null

    )
    {
        $this->validate();
    }

    public function adicionarCodAu(int $CodAu): void
    {
        $this->CodAu = $CodAu;
    }

    private function validate(): void
    {
        if (empty($this->nome)) {
            throw new DomainException('Nome do autor não pode ser vazio');
        }

        if (mb_strlen($this->nome) > 40) {
            throw new DomainException('Nome do autor não pode ter mais de 40 caracteres');
        }

        if (mb_strlen($this->nome) < 5) {
            throw new DomainException('Nome do autor deve ter mais de 5 caracteres');
        }
    }
}
