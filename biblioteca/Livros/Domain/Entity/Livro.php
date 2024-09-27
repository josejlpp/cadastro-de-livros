<?php

namespace Biblioteca\Livros\Domain\Entity;

use DomainException;

class Livro
{
    public readonly int|null $Codl;

    public function __construct(
        public string $titulo,
        public string $editora,
        public int $edicao,
        public int $anoPublicacao,
        private AssuntoCollection $assuntos,
        private AutorCollection $autores
    ) {
        $this->validate();
    }

    public function adicionarCodl(int $Codl): void
    {
        $this->Codl = $Codl;
    }

    private function validate(): void
    {
        if (empty($this->titulo)) {
            throw new DomainException('Título do livro não pode ser vazio');
        }

        if (empty($this->editora)) {
            throw new DomainException('Editora do livro não pode ser vazia');
        }

        if ($this->edicao <= 0) {
            throw new DomainException('Edição do livro deve ser maior que zero');
        }

        if ($this->anoPublicacao <= 0) {
            throw new DomainException('Ano de publicação do livro deve ser maior que zero');
        }
    }

    public function adicionarAutor(Autor $autor): void
    {
        $this->autores->adicionar($autor);
    }

    public function getAutores(): array
    {
        return $this->autores->getAll();
    }

    public function adicionarAssunto(Assunto $assunto): void
    {
        $this->assuntos->adicionar($assunto);
    }

    public function getAssuntos(): array
    {
        return $this->assuntos->getAll();
    }
}
