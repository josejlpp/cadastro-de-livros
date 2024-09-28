<?php

namespace Biblioteca\Livros\Test\Livros\Domain\Entity;

use Biblioteca\Livros\Domain\Entity\Assunto;
use DomainException;
use PHPUnit\Framework\TestCase;

class AssuntoEntityTest extends TestCase
{
    public function testCriarNovoAssunto()
    {
        $assunto = new Assunto('Ficção Científica');

        $this->assertInstanceOf(Assunto::class, $assunto);
        $this->assertEquals('Ficção Científica', $assunto->descricao);
    }

    public function testCriarAssuntoComDescricaoVazia()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Descrição do assunto não pode ser vazia');

        new Assunto('');
    }

    public function testCriarAssuntoComDescricaoComMaisDe20Caracteres()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Descrição do assunto não pode ter mais de 20 caracteres');

        new Assunto('Ficção Científica e Fantasia');
    }

    public function testCriarAssuntoComDescricaoComMenosDe5Caracteres()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Descrição do assunto deve ter mais de 5 caracteres');

        new Assunto('aa');
    }

    public function testRestauraAssuntoSalvo()
    {
        $assunto = new Assunto('Ficção Científica');
        $assunto->adicionarCodAs(1);

        $this->assertEquals(1, $assunto->CodAs);
    }
}
