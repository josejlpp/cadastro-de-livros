<?php

namespace Biblioteca\Livros\Domain\Entity;

use DomainException;

class Assunto
{

    public function __construct(
        public string $descricao,
        public int|null $CodAs = null
    )
    {
        $this->validate();
    }

    public function adicionarCodAs(int $CodAs): void
    {
        $this->CodAs = $CodAs;
    }

    private function validate(): void
    {
        if (empty($this->descricao)) {
            throw new DomainException('Descrição do assunto não pode ser vazia');
        }

        if (mb_strlen($this->descricao) > 20) {
            throw new DomainException('Descrição do assunto não pode ter mais de 20 caracteres');
        }

        if (mb_strlen($this->descricao) < 5) {
            throw new DomainException('Descrição do assunto deve ter mais de 5 caracteres');
        }
    }
}
