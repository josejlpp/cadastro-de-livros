<?php

namespace Biblioteca\Livros\Test\Livros\Application\UseCase;

use Biblioteca\Livros\Application\UseCase\AtualizarAssunto;
use Biblioteca\Livros\Application\UseCase\CriarAssunto;
use Biblioteca\Livros\Domain\Dto\AssuntoDto;
use Biblioteca\Livros\Domain\Entity\Assunto;
use Biblioteca\Livros\Domain\Persistence\Dao\AssuntoDao;
use Biblioteca\Livros\Domain\Persistence\Repository\AssuntoRepository;
use Biblioteca\Livros\Domain\Service\LivroService;
use DomainException;
use PHPUnit\Framework\TestCase;

class AssuntoUseCaseTest extends TestCase
{
    private AssuntoDao $assuntoDao;
    private AssuntoRepository $assuntoRepository;
    private LivroService $service;
    protected function setUp(): void
    {
        $this->assuntoDao = $this->createMock(AssuntoDao::class);
        $this->assuntoRepository = new AssuntoRepository($this->assuntoDao);
        $this->service = $this->createMock(LivroService::class);
    }

    public function testCriarAssunto()
    {
        $assuntoDto = new AssuntoDto(
            null,
            'Assunto Teste',
        );

        $assuntoEntity = new Assunto(
            $assuntoDto->descricao
        );

        $criarAssuntoUseCase = new CriarAssunto($this->assuntoRepository, $this->service);

        $this->assuntoDao->expects($this->once())
            ->method('adicionar')
            ->with($assuntoEntity);

        $this->service->expects($this->once())
            ->method('buildAssuntoEntityFromDto')
            ->with($assuntoDto)
            ->willReturn($assuntoEntity);

        $criarAssuntoUseCase->handle($assuntoDto);
    }

    public function testCriarAssuntoComId()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Assunto já possui código não pode ser adicionado');

        $assuntoDto = new AssuntoDto(
            1,
            'Assunto Teste',
        );

        $assuntoEntity = new Assunto(
            $assuntoDto->descricao,
            $assuntoDto->CodAs
        );

        $this->service->expects($this->once())
            ->method('buildAssuntoEntityFromDto')
            ->with($assuntoDto)
            ->willReturn($assuntoEntity);

        $criarAssuntoUseCase = new CriarAssunto($this->assuntoRepository, $this->service);
        $criarAssuntoUseCase->handle($assuntoDto);
    }

    public function testAtualizarAssunto()
    {
        $assuntoDto = new AssuntoDto(
            1,
            'Assunto Teste',
        );

        $assuntoEntity = new Assunto(
            $assuntoDto->descricao,
            $assuntoDto->CodAs
        );

        $this->assuntoDao->expects($this->once())
            ->method('atualizar')
            ->with($assuntoEntity->CodAs, $assuntoEntity);

        $this->service->expects($this->once())
            ->method('buildAssuntoEntityFromDto')
            ->with($assuntoDto)
            ->willReturn($assuntoEntity);

        $atualizarAssuntoUseCase = new AtualizarAssunto($this->assuntoRepository, $this->service);
        $atualizarAssuntoUseCase->handle($assuntoDto);
    }

    public function testAtualizarAssuntoSemId()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Assunto sem código não pode ser atualizado');

        $assuntoDto = new AssuntoDto(
            null,
            'Assunto Teste',
        );

        $assuntoEntity = new Assunto(
            $assuntoDto->descricao,
            $assuntoDto->CodAs
        );

        $atualizarAssuntoUseCase = new AtualizarAssunto($this->assuntoRepository, $this->service);
        $atualizarAssuntoUseCase->handle($assuntoDto);
    }
}
