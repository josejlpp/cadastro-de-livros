<?php

namespace Biblioteca\Livros\Test;

use Biblioteca\Livros\Domain\Entity\Assunto;
use Biblioteca\Livros\Domain\Entity\AssuntoCollection;
use Biblioteca\Livros\Domain\Entity\Autor;
use Biblioteca\Livros\Domain\Entity\AutorCollection;
use Biblioteca\Livros\Domain\Entity\Livro;
use DomainException;
use PHPUnit\Framework\TestCase;

class LivroEntityTest extends TestCase
{
    private Livro $livroPadrao;
    protected function setUp(): void
    {
        $this->livroPadrao = new Livro(
            'O Senhor dos Anéis',
            'editora',
            1,
            '2001',
            new AssuntoCollection(),
            new AutorCollection()
        );
    }

    public function testCriarEntidadeLivroValida()
    {
        $livro = $this->livroPadrao;

        $this->assertInstanceOf(Livro::class, $livro);
        $this->assertEquals('O Senhor dos Anéis', $livro->titulo);
        $this->assertEquals('editora', $livro->editora);
        $this->assertEquals(1, $livro->edicao);
        $this->assertEquals('2001', $livro->anoPublicacao);
    }

    public function testCriarEntidadeLivroComTituloVazio()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Título do livro não pode ser vazio');

        new Livro(
            '',
            'editora',
            1,
            '2001',
            new AssuntoCollection(),
            new AutorCollection()
        );
    }

    public function testCriarEntidadeLivroComEditoraVazia()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Editora do livro não pode ser vazia');

        new Livro(
            'O Senhor dos Anéis',
            '',
            1,
            '2001',
            new AssuntoCollection(),
            new AutorCollection()
        );
    }

    public function testCriarEntidadeLivroComEdicaoZero()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Edição do livro deve ser maior que zero');

        new Livro(
            'O Senhor dos Anéis',
            'editora',
            0,
            '2001',
            new AssuntoCollection(),
            new AutorCollection()
        );
    }

    public function testCriarEntidadeLivroComAnoPublicacaoNegativo()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Ano de publicação do livro deve ser maior que zero');

        new Livro(
            'O Senhor dos Anéis',
            'editora',
            1,
            '-2001',
            new AssuntoCollection(),
            new AutorCollection()
        );
    }

    public function testCriarEntidadeLivroComAnoPublicacaoZero()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Ano de publicação do livro deve ser maior que zero');

        new Livro(
            'O Senhor dos Anéis',
            'editora',
            1,
            0,
            new AssuntoCollection(),
            new AutorCollection()
        );
    }

    public function testRestararLivroSalvo()
    {
        $livro = $this->livroPadrao;

        $livro->adicionarCodl(1);

        $this->assertEquals(1, $livro->Codl);
    }

    public function testAdicionarAutorComSucesso()
    {
        $autor = new Autor('J. R. R. Tolkien', 1);
        $livro = $this->livroPadrao;
        $livro->adicionarAutor($autor);

        $this->assertEquals($autor->CodAu, $livro->getAutores()[$autor->CodAu]->CodAu);
    }

    public function testAdicionarAutorDuplicado()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Autor já adicionado');

        $autor = new Autor('J. R. R. Tolkien', 1);
        $livro = $this->livroPadrao;
        $livro->adicionarAutor($autor);
        $livro->adicionarAutor($autor);
    }

    public function testAdicionarMaisDeUmAutorComSucesso()
    {
        $autor1 = new Autor('J. R. R. Tolkien', 1);
        $autor2 = new Autor('C. S. Lewis', 2);

        $livro = $this->livroPadrao;

        $livro->adicionarAutor($autor1);
        $livro->adicionarAutor($autor2);

        $this->assertEquals($autor1->CodAu, $livro->getAutores()[$autor1->CodAu]->CodAu);
        $this->assertEquals($autor2->CodAu, $livro->getAutores()[$autor2->CodAu]->CodAu);
    }

    public function testAdicionarMaisDeUmAutorDuplicado()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Autor já adicionado');

        $autor1 = new Autor('J. R. R. Tolkien', 1);
        $autor2 = new Autor('C. S. Lewis', 2);

        $livro = $this->livroPadrao;

        $livro->adicionarAutor($autor1);
        $livro->adicionarAutor($autor2);
        $livro->adicionarAutor($autor1);
    }

    public function testAdicionarAssuntoComSucesso()
    {
        $assunto = new Assunto('Fantasia', 1);

        $livro = $this->livroPadrao;

        $livro->adicionarAssunto($assunto);

        $this->assertEquals($assunto->codAs, $livro->getAssuntos()[$assunto->codAs]->codAs);
    }

    public function testAdicionarAssuntoDuplicado()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Assunto já adicionado');

        $assunto = new Assunto('Fantasia', 1);

        $livro = $this->livroPadrao;

        $livro->adicionarAssunto($assunto);
        $livro->adicionarAssunto($assunto);
    }

    public function testAdicionarMaisDeUmAssuntoComSucesso()
    {
        $assunto1 = new Assunto('Fantasia', 1);
        $assunto2 = new Assunto('Aventura', 2);

        $livro = $this->livroPadrao;

        $livro->adicionarAssunto($assunto1);
        $livro->adicionarAssunto($assunto2);

        $this->assertEquals($assunto1->codAs, $livro->getAssuntos()[$assunto1->codAs]->codAs);
        $this->assertEquals($assunto2->codAs, $livro->getAssuntos()[$assunto2->codAs]->codAs);

    }

    public function testAdicionarMaisDeUmAssuntoDuplicado()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Assunto já adicionado');

        $assunto1 = new Assunto('Fantasia', 1);
        $assunto2 = new Assunto('Aventura', 2);

        $livro = $this->livroPadrao;

        $livro->adicionarAssunto($assunto1);
        $livro->adicionarAssunto($assunto2);
        $livro->adicionarAssunto($assunto1);
    }
}
