<?php

namespace Biblioteca\Livros\Test\Entity;

use Biblioteca\Livros\Domain\Entity\Autor;
use DomainException;
use PHPUnit\Framework\TestCase;

class AutorEntityTest extends TestCase
{
    public function testCriarAutorComSucesso()
    {
        $autor = new Autor('J. R. R. Tolkien');

        $this->assertInstanceOf(Autor::class, $autor);
        $this->assertEquals('J. R. R. Tolkien', $autor->nome);
    }

    public function testCriarAutorComNomeVazio()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Nome do autor não pode ser vazio');

        new Autor('');
    }

    public function testCriarAutorComNomeComMaisDe40Caracteres()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Nome do autor não pode ter mais de 40 caracteres');

        new Autor('J. R. R. Tolkien, autor de O Senhor dos Anéis');
    }

    public function testCriarAutorComNomeComMenosDe5Caracteres()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Nome do autor deve ter mais de 5 caracteres');
        $autor = new Autor('A');
    }

    public function testRestauraAutorSalvo()
    {
        $autor = new Autor('J. R. R. Tolkien');
        $autor->adicionarCodAu(1);

        $this->assertEquals(1, $autor->CodAu);
    }
}
