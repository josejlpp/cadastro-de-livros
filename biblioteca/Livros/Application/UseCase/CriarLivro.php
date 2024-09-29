<?php

namespace Biblioteca\Livros\Application\UseCase;

use Biblioteca\Livros\Domain\Persistence\Repository\LivroRepository;
use Biblioteca\Livros\Domain\Service\LivroService;

readonly class CriarLivro
{
    public function __construct(
        private LivroRepository $livroRepository,
        private LivroService $service
    )
    {
    }

    public function handle($livroDto): void
    {
        $livroEntity = $this->service->buildLivroEntityFromDto($livroDto);
        $this->livroRepository->adicionar($livroEntity);
    }
}
