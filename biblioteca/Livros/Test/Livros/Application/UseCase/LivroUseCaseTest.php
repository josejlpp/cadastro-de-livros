<?php

namespace Biblioteca\Livros\Test\Livros\Application\UseCase;

use Biblioteca\Livros\Application\UseCase\AtualizarLivro;
use Biblioteca\Livros\Application\UseCase\CriarLivro;
use Biblioteca\Livros\Domain\Dto\AssuntoCollectionDto;
use Biblioteca\Livros\Domain\Dto\AssuntoDto;
use Biblioteca\Livros\Domain\Dto\AutorCollectionDto;
use Biblioteca\Livros\Domain\Dto\AutorDto;
use Biblioteca\Livros\Domain\Dto\LivroDto;
use Biblioteca\Livros\Domain\Entity\Assunto;
use Biblioteca\Livros\Domain\Entity\AssuntoCollection;
use Biblioteca\Livros\Domain\Entity\Autor;
use Biblioteca\Livros\Domain\Entity\AutorCollection;
use Biblioteca\Livros\Domain\Entity\Livro;
use Biblioteca\Livros\Domain\Persistence\Dao\LivroDao;
use Biblioteca\Livros\Domain\Persistence\Repository\LivroRepository;
use Biblioteca\Livros\Domain\Service\LivroService;
use DomainException;
use PHPUnit\Framework\TestCase;

class LivroUseCaseTest extends TestCase
{
    private LivroDao $livroDao;
    private LivroRepository $livroRepository;
    private LivroService $service;
    private LivroDto $livroDto;
    private AutorCollection $autorCollection;
    private AssuntoCollection $assuntoCollection;
    protected function setUp(): void
    {
        $this->livroDao = $this->createMock(LivroDao::class);
        $this->livroRepository = new LivroRepository($this->livroDao);
        $this->service = $this->createMock(LivroService::class);

        $autorCollectionDto = new AutorCollectionDto(
            new AutorDto(1, 'Autor 1')
        );

        $assuntoCollectionDto = new AssuntoCollectionDto(
            new AssuntoDto(1, 'Assunto 1')
        );

        $this->livroDto = new LivroDto(
            null,
            'Livro 1',
            'editora',
            1,
            2024,
            10,
            $assuntoCollectionDto,
            $autorCollectionDto

        );

        $this->autorCollection = new AutorCollection();
        foreach ($autorCollectionDto->autores as $autor) {
            $this->autorCollection->adicionar(new Autor($autor->nome, $autor->CodAu));
        }

        $this->assuntoCollection = new AssuntoCollection();
        foreach ($assuntoCollectionDto->assuntos as $assunto) {
            $this->assuntoCollection->adicionar(new Assunto($assunto->descricao, $assunto->CodAs));
        }
    }

    public function testCriarLivro()
    {
        $this->livroDao->expects($this->once())
            ->method('adicionar');

        $this->service->expects($this->once())
            ->method('buildLivroEntityFromDto')
            ->with($this->livroDto);

        $criarLivroUseCase = new CriarLivro($this->livroRepository, $this->service);

        $criarLivroUseCase->handle($this->livroDto);
    }

    public function testCriarLivroComId()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Livro já possui código não pode ser adicionado');

        $this->livroDto->Codl = 1;

        $livroEntity = new Livro(
            titulo: $this->livroDto->titulo,
            editora: $this->livroDto->editora,
            edicao: $this->livroDto->edicao,
            anoPublicacao: $this->livroDto->anoPublicacao,
            valor: $this->livroDto->valor,
            assuntos: $this->assuntoCollection,
            autores: $this->autorCollection
        );

        $livroEntity->adicionarCodl($this->livroDto->Codl);

        $this->service->expects($this->once())
            ->method('buildLivroEntityFromDto')
            ->with($this->livroDto)
            ->willReturn($livroEntity);

        $criarLivroUseCase = new CriarLivro($this->livroRepository, $this->service);
        $criarLivroUseCase->handle($this->livroDto);
    }

    public function testAtualizarLivro()
    {
        $this->livroDto->Codl = 1;

        $livroEntity = new Livro(
            titulo: $this->livroDto->titulo,
            editora: $this->livroDto->editora,
            edicao: $this->livroDto->edicao,
            anoPublicacao: $this->livroDto->anoPublicacao,
            valor: $this->livroDto->valor,
            assuntos: $this->assuntoCollection,
            autores: $this->autorCollection
        );

        $livroEntity->adicionarCodl($this->livroDto->Codl);


        $this->livroDao->expects($this->once())
            ->method('atualizar')
            ->with($livroEntity->Codl, $livroEntity);

        $this->service->expects($this->once())
            ->method('buildLivroEntityFromDto')
            ->with($this->livroDto)
            ->willReturn($livroEntity);

        $criarLivroUseCase = new AtualizarLivro($this->livroRepository, $this->service);
        $criarLivroUseCase->handle($this->livroDto);
    }

    public function testAtualizarLivroSemId()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Livro sem código não pode ser atualizado');

        $this->livroDto->Codl = null;

        $this->service->expects($this->once())
            ->method('buildLivroEntityFromDto')
            ->with($this->livroDto);

        $criarLivroUseCase = new AtualizarLivro($this->livroRepository, $this->service);
        $criarLivroUseCase->handle($this->livroDto);
    }
}
