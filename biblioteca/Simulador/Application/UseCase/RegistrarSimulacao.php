<?php

namespace Biblioteca\Simulador\Application\UseCase;

use Biblioteca\Simulador\Domain\Dto\SimulacaoDto;
use Biblioteca\Simulador\Domain\Entity\Simulacao;
use Biblioteca\Simulador\Domain\Persistence\Repository\SimulacaoRepository;
use Biblioteca\Simulador\Domain\Service\CalculadoraPotencial;
use Biblioteca\Simulador\Domain\Service\SimulacaoService;

readonly class RegistrarSimulacao
{
    public function __construct(
        private SimulacaoRepository  $simulacaoRepository,
        private SimulacaoService     $service,
        private CalculadoraPotencial $calculadora
    )
    {
    }

    public function handle(SimulacaoDto $simulacaoDto): Simulacao
    {
        $perfil = $this->service->buildPerfilTributarioFromDto($simulacaoDto);
        $contato = $this->service->buildContatoFromDto($simulacaoDto);

        $simulacao = new Simulacao($contato, $perfil, $this->calculadora->calcular($perfil));
        $this->simulacaoRepository->adicionar($simulacao);

        return $simulacao;
    }
}
