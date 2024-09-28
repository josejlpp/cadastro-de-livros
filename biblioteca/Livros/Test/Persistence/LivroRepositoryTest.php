<?php

namespace Biblioteca\Livros\Test\Persistence;

use DomainException;
use PHPUnit\Framework\TestCase;
use Biblioteca\Livros\Domain\Entity\Assunto;
use Biblioteca\Livros\Domain\Entity\AssuntoCollection;
use Biblioteca\Livros\Domain\Entity\Autor;
use Biblioteca\Livros\Domain\Entity\AutorCollection;
use Biblioteca\Livros\Domain\Entity\Livro;
use Biblioteca\Livros\Domain\Persistence\Dao\LivroDao;
use Biblioteca\Livros\Domain\Persistence\Repository\LivroRepository;

class LivroRepositoryTest extends TestCase
{
    private LivroDao $livroDao;
    private LivroRepository $livroRepository;
    private Livro $livroPadrao;

    protected function setUp(): void
    {
        $this->livroDao = $this->createMock(LivroDao::class);
        $this->livroRepository = new LivroRepository($this->livroDao);

        $assunto = new Assunto('Fantasia', 1);
        $assuntoCollection = new AssuntoCollection();
        $assuntoCollection->adicionar($assunto);

        $autor = new Autor('J. R. R. Tolkien', 1);
        $autorCollection = new AutorCollection();
        $autorCollection->adicionar($autor);

        $this->livroPadrao = new Livro(
            'O Senhor dos Anéis',
            'editora',
            1,
            '2001',
            $assuntoCollection,
            $autorCollection
        );
    }

    public function testAdicionarLivroComCodigoNulo(): void
    {
        $livro = clone $this->livroPadrao;

        $this->livroDao->expects($this->once())
            ->method('adicionar')
            ->with($livro);

        $this->livroRepository->adicionar($livro);
    }

    public function testAdicionarLivroComCodigoNaoNuloLancaExcecao(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Livro já possui código não pode ser adicionado');

        $livro = clone $this->livroPadrao;
        $livro->adicionarCodl(1);

        $this->livroRepository->adicionar($livro);
    }

    public function testAtualizarLivroComCodigoNaoNulo(): void
    {
        $livro = clone $this->livroPadrao;
        $livro->adicionarCodl(1);

        $this->livroDao->expects($this->once())
            ->method('atualizar')
            ->with($livro->Codl, $livro);

        $this->livroRepository->atualizar($livro);
    }

    public function testAtualizarLivroComCodigoNuloLancaExcecao(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Livro sem código não pode ser atualizado');

        $livro = clone $this->livroPadrao;

        $this->livroRepository->atualizar($livro);
    }

    public function testExcluirLivroComCodigoNaoNulo(): void
    {
        $livro = clone $this->livroPadrao;
        $livro->adicionarCodl(1);

        $this->livroDao->expects($this->once())
            ->method('excluir')
            ->with($livro->Codl);

        $this->livroRepository->excluir($livro);
    }

    public function testExcluirLivroComCodigoNuloLancaExcecao(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Livro sem código não pode ser excluído');

        $livro = clone $this->livroPadrao;

        $this->livroRepository->excluir($livro);
    }
}
