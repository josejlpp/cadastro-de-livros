<?php

namespace Biblioteca\Livros\Test\Livros\Application\UseCase;

use Biblioteca\Livros\Application\UseCase\AtualizarAutor;
use Biblioteca\Livros\Application\UseCase\CriarAutor;
use Biblioteca\Livros\Domain\Dto\AutorDto;
use Biblioteca\Livros\Domain\Entity\Autor;
use Biblioteca\Livros\Domain\Persistence\Dao\AutorDao;
use Biblioteca\Livros\Domain\Persistence\Repository\AutorRepository;
use Biblioteca\Livros\Domain\Service\LivroService;
use DomainException;
use PHPUnit\Framework\TestCase;

class AutorUseCaseTest extends TestCase
{
    private AutorDao $autorDao;
    private AutorRepository $autorRepository;
    private LivroService $service;
    protected function setUp(): void
    {
        $this->autorDao = $this->createMock(AutorDao::class);
        $this->autorRepository = new AutorRepository($this->autorDao);
        $this->service = $this->createMock(LivroService::class);
    }

    public function testCriarAutor()
    {
        $autorDto = new AutorDto(
            null,
            'Autor Teste',
        );

        $autorEntity = new Autor(
            $autorDto->nome
        );

        $criarAutorUseCase = new CriarAutor($this->autorRepository, $this->service);

        $this->autorDao->expects($this->once())
            ->method('adicionar')
            ->with($autorEntity);

        $this->service->expects($this->once())
            ->method('buildAutorEntityFromDto')
            ->with($autorDto)
            ->willReturn($autorEntity);

        $criarAutorUseCase->handle($autorDto);
    }

    public function testCriarAutorComId()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Autor já possui código não pode ser adicionado');

        $autorDto = new AutorDto(
            1,
            'Autor Teste',
        );

        $autorEntity = new Autor(
            $autorDto->nome,
            $autorDto->CodAu
        );

        $this->service->expects($this->once())
            ->method('buildAutorEntityFromDto')
            ->with($autorDto)
            ->willReturn($autorEntity);

        $criarAutorUseCase = new CriarAutor($this->autorRepository, $this->service);
        $criarAutorUseCase->handle($autorDto);
    }

    public function testAtualizarAutor()
    {
        $autorDto = new AutorDto(
            1,
            'Autor Teste',
        );

        $autorEntity = new Autor(
            $autorDto->nome,
            $autorDto->CodAu
        );

        $this->autorDao->expects($this->once())
            ->method('atualizar')
            ->with($autorEntity->CodAu, $autorEntity);

        $this->service->expects($this->once())
            ->method('buildAutorEntityFromDto')
            ->with($autorDto)
            ->willReturn($autorEntity);

        $atualizarAutorUseCase = new AtualizarAutor($this->autorRepository, $this->service);
        $atualizarAutorUseCase->handle($autorDto);
    }

    public function testAtualizarAutorSemId()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Autor sem código não pode ser atualizado');

        $autorDto = new AutorDto(
            null,
            'Autor Teste',
        );

        $autorEntity = new Autor(
            $autorDto->nome,
            $autorDto->CodAu
        );

        $atualizarAutorUseCase = new AtualizarAutor($this->autorRepository, $this->service);
        $atualizarAutorUseCase->handle($autorDto);
    }
}
