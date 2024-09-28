<?php

namespace Biblioteca\Livros\Domain\Entity;

use DomainException;

class Livro
{
    public readonly int|null $Codl;

    public function __construct(
        public string                      $titulo,
        public string                      $editora,
        public int                         $edicao,
        public int                         $anoPublicacao,
        private readonly AssuntoCollection $assuntos,
        private readonly AutorCollection $autores
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

        if ($this->assuntos->count() === 0) {
            throw new DomainException('Livro deve ter pelo menos um assunto');
        }

        if ($this->autores->count() === 0) {
            throw new DomainException('Livro deve ter pelo menos um autor');
        }
    }

    public function adicionarAutor(Autor $autor): void
    {
        $this->autores->adicionar($autor);
    }

    public function getAutores(): AutorCollection
    {
        return $this->autores;
    }

    public function adicionarAssunto(Assunto $assunto): void
    {
        $this->assuntos->adicionar($assunto);
    }

    public function getAssuntos(): AssuntoCollection
    {
        return $this->assuntos;
    }
}
