<?php

namespace Biblioteca\Livros\Test\Livros\Domain\Entity;

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
    private AssuntoCollection $assuntoCollection;
    private AutorCollection $autorCollection;
    protected function setUp(): void
    {
        $assunto = new Assunto('Fantasia', 1);
        $assuntoCollection = new AssuntoCollection();
        $assuntoCollection->adicionar($assunto);
        $this->assuntoCollection = $assuntoCollection;

        $autor = new Autor('J. R. R. Tolkien', 1);
        $autorCollection = new AutorCollection();
        $autorCollection->adicionar($autor);
        $this->autorCollection = $autorCollection;

        $this->livroPadrao = new Livro(
            'O Senhor dos Anéis',
            'editora',
            1,
            '2001',
            10,
            $assuntoCollection,
            $autorCollection
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
            10,
            $this->assuntoCollection,
            $this->autorCollection
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
            10,
            $this->assuntoCollection,
            $this->autorCollection
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
            10,
            $this->assuntoCollection,
            $this->autorCollection
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
            10,
            $this->assuntoCollection,
            $this->autorCollection
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
            10,
            $this->assuntoCollection,
            $this->autorCollection
        );
    }

    public function testCriarEntidadeLivroComValorZero()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Valor do livro deve ser maior que zero');

        new Livro(
            'O Senhor dos Anéis',
            'editora',
            1,
            1,
            0,
            $this->assuntoCollection,
            $this->autorCollection
        );
    }

    public function testCriarEntidadeLivroComValorQuebrado()
    {
        $livro = new Livro(
            'O Senhor dos Anéis',
            'editora',
            1,
            1,
            11.99,
            $this->assuntoCollection,
            $this->autorCollection
        );

        $this->assertEquals(11.99, $livro->valor);
    }

    public function testRestararLivroSalvo()
    {
        $livro = $this->livroPadrao;

        $livro->adicionarCodl(1);

        $this->assertEquals(1, $livro->Codl);
    }

    public function testAdicionarAutorComSucesso()
    {
        $autor = new Autor('J. R. R. Tolkien', 2);
        $livro = $this->livroPadrao;
        $livro->adicionarAutor($autor);

        $autores = $livro->getAutores();
        $this->assertEquals($autor->CodAu, $autores->getByCodAu($autor->CodAu)->CodAu);
    }

    public function testAdicionarAutorDuplicado()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Autor já adicionado');

        $autor = new Autor('J. R. R. Tolkien', 2);
        $livro = $this->livroPadrao;
        $livro->adicionarAutor($autor);
        $livro->adicionarAutor($autor);
    }

    public function testAdicionarMaisDeUmAutorComSucesso()
    {
        $autor1 = new Autor('J. R. R. Tolkien', 2);
        $autor2 = new Autor('C. S. Lewis', 3);

        $livro = $this->livroPadrao;

        $livro->adicionarAutor($autor1);
        $livro->adicionarAutor($autor2);

        $autores = $livro->getAutores();
        $this->assertEquals($autor1->CodAu, $autores->getByCodAu($autor1->CodAu)->CodAu);
        $this->assertEquals($autor2->CodAu, $autores->getByCodAu($autor2->CodAu)->CodAu);
    }

    public function testAdicionarMaisDeUmAutorDuplicado()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Autor já adicionado');

        $autor1 = new Autor('J. R. R. Tolkien', 2);
        $autor2 = new Autor('C. S. Lewis', 3);

        $livro = $this->livroPadrao;

        $livro->adicionarAutor($autor1);
        $livro->adicionarAutor($autor2);
        $livro->adicionarAutor($autor1);
    }

    public function testAdicionarAssuntoComSucesso()
    {
        $assunto = new Assunto('Fantasia', 2);

        $livro = $this->livroPadrao;

        $livro->adicionarAssunto($assunto);

        $assuntos = $livro->getAssuntos();
        $this->assertEquals($assunto->CodAs, $assuntos->getByCodAs($assunto->CodAs)->CodAs);
    }

    public function testAdicionarAssuntoDuplicado()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Assunto já adicionado');

        $assunto = new Assunto('Fantasia', 2);

        $livro = $this->livroPadrao;

        $livro->adicionarAssunto($assunto);
        $livro->adicionarAssunto($assunto);
    }

    public function testAdicionarMaisDeUmAssuntoComSucesso()
    {
        $assunto1 = new Assunto('Fantasia', 2);
        $assunto2 = new Assunto('Aventura', 3);

        $livro = $this->livroPadrao;

        $livro->adicionarAssunto($assunto1);
        $livro->adicionarAssunto($assunto2);

        $assuntos = $livro->getAssuntos();
        $this->assertEquals($assunto1->CodAs, $assuntos->getByCodAs($assunto1->CodAs)->CodAs);
        $this->assertEquals($assunto2->CodAs, $assuntos->getByCodAs($assunto2->CodAs)->CodAs);

    }

    public function testAdicionarMaisDeUmAssuntoDuplicado()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Assunto já adicionado');

        $assunto1 = new Assunto('Fantasia', 2);
        $assunto2 = new Assunto('Aventura', 3);

        $livro = $this->livroPadrao;

        $livro->adicionarAssunto($assunto1);
        $livro->adicionarAssunto($assunto2);
        $livro->adicionarAssunto($assunto1);
    }
}
