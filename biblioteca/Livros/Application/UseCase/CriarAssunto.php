<?php

namespace Biblioteca\Livros\Application\UseCase;

use Biblioteca\Livros\Domain\Persistence\Repository\AssuntoRepository;
use Biblioteca\Livros\Domain\Service\LivroService;

readonly class CriarAssunto
{
    public function __construct(
        private AssuntoRepository $assuntoRepository,
        private LivroService $service
    )
    {
    }

    public function handle($assuntoDto): void
    {
        $assuntoEntity = $this->service->buildAssuntoEntityFromDto($assuntoDto);
        $this->assuntoRepository->adicionar($assuntoEntity);
    }
}
