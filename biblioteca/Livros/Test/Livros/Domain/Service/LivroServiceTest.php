<?php

namespace Biblioteca\Livros\Test\Livros\Domain\Service;

use Biblioteca\Livros\Domain\Dto\AssuntoCollectionDto;
use Biblioteca\Livros\Domain\Dto\AssuntoDto;
use Biblioteca\Livros\Domain\Dto\AutorCollectionDto;
use Biblioteca\Livros\Domain\Dto\AutorDto;
use Biblioteca\Livros\Domain\Dto\LivroDto;
use Biblioteca\Livros\Domain\Entity\Assunto;
use Biblioteca\Livros\Domain\Entity\Autor;
use Biblioteca\Livros\Domain\Entity\Livro;
use Biblioteca\Livros\Domain\Service\LivroService;
use PHPUnit\Framework\TestCase;

class LivroServiceTest extends TestCase
{
    public function testCriarEntidadeLivroAPartirDoDtoParaCriacaoDeDados()
    {
        $livroDto = new LivroDto(
            Codl: null,
            titulo: 'Livro de Teste',
            editora: 'Editora Teste',
            edicao: 1,
            anoPublicacao: 2021,
            assuntosDto: new AssuntoCollectionDto(
                new AssuntoDto(CodAs: 1, descricao: 'Assunto 1')
            ),
            autoresDto: new AutorCollectionDto(
                new AutorDto(CodAu: 1, nome: 'Autor 1'),
                new AutorDto(CodAu: 2, nome: 'Autor 2'),
            )
        );

        $service = new LivroService();
        $livro  = $service->buildLivroEntityFromDto($livroDto);

        $this->isInstanceOf(Livro::class, $livro);
        $this->assertFalse(isset($livro->Codl));
        $this->assertEquals('Livro de Teste', $livro->titulo);
        $this->assertEquals('Editora Teste', $livro->editora);
        $this->assertEquals(1, $livro->edicao);
        $this->assertEquals(2021, $livro->anoPublicacao);
        $this->assertCount(1, $livro->getAssuntos());
        $this->assertCount(2, $livro->getAutores());
    }

    public function testCriarEntidadeLivroAPartirDoDtoParaAtualizacaoDeDados()
    {
        $livroDto = new LivroDto(
            Codl: 1,
            titulo: 'Livro de Teste',
            editora: 'Editora Teste',
            edicao: 1,
            anoPublicacao: 2021,
            assuntosDto: new AssuntoCollectionDto(
                new AssuntoDto(CodAs: 1, descricao: 'Assunto 1')
            ),
            autoresDto: new AutorCollectionDto(
                new AutorDto(CodAu: 1, nome: 'Autor 1'),
                new AutorDto(CodAu: 2, nome: 'Autor 2'),
            )
        );

        $service = new LivroService();
        $livro  = $service->buildLivroEntityFromDto($livroDto);

        $this->isInstanceOf(Livro::class, $livro);
        $this->assertEquals(1, $livro->Codl);
        $this->assertEquals('Livro de Teste', $livro->titulo);
        $this->assertEquals('Editora Teste', $livro->editora);
        $this->assertEquals(1, $livro->edicao);
        $this->assertEquals(2021, $livro->anoPublicacao);
        $this->assertCount(1, $livro->getAssuntos());
        $this->assertCount(2, $livro->getAutores());
    }

    public function testCriarEntidadeAutorAPartirDoDtoParaCriacaoDeDados()
    {
        $autorDto = new AutorDto(CodAu: null, nome: 'Autor de Teste');
        $service = new LivroService();

        $autor  = $service->buildAutorEntityFromDto($autorDto);

        $this->assertInstanceOf(Autor::class, $autor);
        $this->assertNull($autor->CodAu);
        $this->assertEquals('Autor de Teste', $autor->nome);
    }

    public function testCriarEntidadeAutorAPartirDoDtoParaAtualizacaoDeDados()
    {
        $autorDto = new AutorDto(CodAu: 1, nome: 'Autor de Teste');
        $service = new LivroService();

        $autor  = $service->buildAutorEntityFromDto($autorDto);

        $this->assertInstanceOf(Autor::class, $autor);
        $this->assertEquals(1, $autor->CodAu);
        $this->assertEquals('Autor de Teste', $autor->nome);
    }

    public function testCriarEntidadeAssuntoAPartirDoDtoParaAtualizacaoDeDados()
    {
        $assuntoDto = new AssuntoDto( CodAs: 1, descricao: 'Assunto Teste');
        $service = new LivroService();

        $assunto  = $service->buildAssuntoEntityFromDto($assuntoDto);

        $this->assertInstanceOf(Assunto::class, $assunto);
        $this->assertEquals(1,$assunto->CodAs);
        $this->assertEquals('Assunto Teste', $assunto->descricao);
    }

    public function testCriarEntidadeAssuntoAPartirDoDtoParaCriacaoDeDados()
    {
        $assuntoDto = new AssuntoDto(CodAs: null, descricao: 'Assunto Teste');
        $service = new LivroService();

        $assunto  = $service->buildAssuntoEntityFromDto($assuntoDto);

        $this->assertInstanceOf(Assunto::class, $assunto);
        $this->assertNull($assunto->CodAs);
        $this->assertEquals('Assunto Teste', $assunto->descricao);
    }
}
