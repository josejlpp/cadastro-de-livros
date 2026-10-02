<?php

namespace Biblioteca\Simulador\Test\Simulador\Domain\Entity;

use Biblioteca\Simulador\Domain\Entity\Contato;
use DomainException;
use PHPUnit\Framework\TestCase;

class ContatoEntityTest extends TestCase
{
    public function testCriarContatoComCnpjNormalizado()
    {
        $contato = new Contato('Maria Souza', 'maria@example.com', '(85) 99999-8888', 'Empresa Ltda', '11.222.333/0001-81');

        $this->assertEquals('11222333000181', $contato->cnpj);
    }

    public function testCnpjEhOpcional()
    {
        $contato = new Contato('Maria Souza', 'maria@example.com', '(85) 3333-4444', 'Empresa Ltda');

        $this->assertNull($contato->cnpj);
    }

    public function testAceitaCnpjAlfanumerico()
    {
        $contato = new Contato('Maria Souza', 'maria@example.com', '85999998888', 'Empresa Ltda', '12.abc.345/01de-35');

        $this->assertEquals('12ABC34501DE35', $contato->cnpj);
    }

    public function testCnpjInvalido()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('CNPJ informado é inválido');

        new Contato('Maria Souza', 'maria@example.com', '85999998888', 'Empresa Ltda', '11.222.333/0001-82');
    }

    public function testCnpjComDigitosRepetidosEhInvalido()
    {
        $this->assertFalse(Contato::cnpjValido('00.000.000/0000-00'));
        $this->assertFalse(Contato::cnpjValido('1122233300018'));
    }

    public function testEmailInvalido()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('E-mail do contato é inválido');

        new Contato('Maria Souza', 'maria', '85999998888', 'Empresa Ltda');
    }

    public function testTelefoneSemDdd()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Telefone do contato deve ter DDD e 8 ou 9 dígitos');

        new Contato('Maria Souza', 'maria@example.com', '99999-8888', 'Empresa Ltda');
    }

    public function testNomeVazio()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Nome do contato não pode ser vazio');

        new Contato('  ', 'maria@example.com', '85999998888', 'Empresa Ltda');
    }
}
