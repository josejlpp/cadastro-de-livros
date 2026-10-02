<?php

namespace Biblioteca\Simulador\Domain\Service;

use Biblioteca\Simulador\Domain\Entity\Oportunidade;
use Biblioteca\Simulador\Domain\Entity\PerfilTributario;
use Biblioteca\Simulador\Domain\Entity\ResultadoSimulacao;
use Biblioteca\Simulador\Domain\Entity\Tese;
use Biblioteca\Simulador\Domain\Entity\TeseCollection;
use DomainException;

readonly class CalculadoraPotencial
{
    public function __construct(
        private TeseCollection $teses,
        private float          $selicMensal = 0
    ) {
        if ($this->selicMensal < 0) {
            throw new DomainException('SELIC mensal não pode ser negativa');
        }
    }

    public function calcular(PerfilTributario $perfil): ResultadoSimulacao
    {
        $resultado = new ResultadoSimulacao($perfil->meses, $this->selicMensal);

        foreach ($this->teses as $tese) {
            if (!$tese->aplicavelA($perfil)) {
                continue;
            }

            $baseInformada = $tese->base->valor($perfil);
            $baseEstimada = $baseInformada <= 0 && $tese->fatorEstimativaBase !== null;
            $baseMensal = $baseEstimada
                ? $perfil->faturamentoMensal * $tese->fatorEstimativaBase
                : $baseInformada;

            if ($baseMensal <= 0) {
                continue;
            }

            $resultado->adicionar(new Oportunidade(
                chave: $tese->chave,
                titulo: $tese->titulo,
                descricao: $tese->descricao,
                fundamento: $tese->fundamento,
                baseDescricao: $tese->base->label(),
                baseMensal: $baseMensal,
                baseEstimada: $baseEstimada,
                percentualMinimo: $tese->percentualMinimo($perfil->regime),
                percentualMaximo: $tese->percentualMaximo($perfil->regime),
                meses: $this->meses($tese, $perfil),
                selicMensal: $this->selicMensal
            ));
        }

        return $resultado;
    }

    private function meses(Tese $tese, PerfilTributario $perfil): int
    {
        return $tese->mesesMaximo === null ? $perfil->meses : min($perfil->meses, $tese->mesesMaximo);
    }
}
