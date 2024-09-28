<?php

namespace Biblioteca\Livros\Application\UseCase;

use Biblioteca\Livros\Domain\Persistence\Repository\AutorRepository;
use Biblioteca\Livros\Domain\Service\LivroService;

readonly class CriarAutor
{
    public function __construct(
        private AutorRepository $autorRepository,
        private LivroService $service
    )
    {
    }

    public function handle($autorDto): void
    {
        $autorEntity = $this->service->buildAutorEntityFromDto($autorDto);
        $this->autorRepository->adicionar($autorEntity);
    }
}
