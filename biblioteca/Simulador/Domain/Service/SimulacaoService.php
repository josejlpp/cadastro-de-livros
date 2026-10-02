<?php

namespace Biblioteca\Simulador\Domain\Service;

use Biblioteca\Simulador\Domain\Dto\SimulacaoDto;
use Biblioteca\Simulador\Domain\Entity\Contato;
use Biblioteca\Simulador\Domain\Entity\PerfilTributario;
use Biblioteca\Simulador\Domain\Enum\RegimeTributario;
use Biblioteca\Simulador\Domain\Enum\Segmento;
use DomainException;

class SimulacaoService
{
    public function buildPerfilTributarioFromDto(SimulacaoDto $dto): PerfilTributario
    {
        $regime = RegimeTributario::tryFrom($dto->regime)
            ?? throw new DomainException('Regime tributário inválido');

        $segmento = Segmento::tryFrom($dto->segmento)
            ?? throw new DomainException('Segmento de atuação inválido');

        return new PerfilTributario(
            regime: $regime,
            segmento: $segmento,
            faturamentoMensal: $dto->faturamentoMensal,
            folhaPagamentoMensal: $dto->folhaPagamentoMensal,
            energiaMensal: $dto->energiaMensal,
            combustivelMensal: $dto->combustivelMensal,
            icmsMensal: $dto->icmsMensal,
            issMensal: $dto->issMensal,
            percentualMonofasico: $dto->percentualMonofasico,
            meses: $dto->meses
        );
    }

    public function buildContatoFromDto(SimulacaoDto $dto): Contato
    {
        return new Contato(
            nome: $dto->nome,
            email: $dto->email,
            telefone: $dto->telefone,
            empresa: $dto->empresa,
            cnpj: $dto->cnpj
        );
    }
}
