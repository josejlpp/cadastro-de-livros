<?php

namespace Biblioteca\Simulador\Domain\Entity;

use Biblioteca\Simulador\Domain\Enum\BaseCalculo;
use Biblioteca\Simulador\Domain\Enum\RegimeTributario;
use Biblioteca\Simulador\Domain\Enum\Segmento;
use DomainException;

/**
 * Oportunidade de recuperação tributária (tese) e as regras para estimá-la:
 * valor mensal = base de cálculo × percentual do regime (faixa mínima e máxima).
 */
readonly class Tese
{
    /**
     * @param array<string, array{0: float, 1: float}> $percentuais faixa [mínimo, máximo] indexada pelo valor do regime
     * @param Segmento[] $segmentos segmentos atendidos; vazio atende todos
     * @param float|null $fatorEstimativaBase quando a base não for informada, estima como faturamento × fator
     * @param int|null $mesesMaximo limita o período recuperável (ex.: modulação de efeitos)
     */
    public function __construct(
        public string      $chave,
        public string      $titulo,
        public string      $descricao,
        public string      $fundamento,
        public BaseCalculo $base,
        public array       $percentuais,
        public array       $segmentos = [],
        public float|null  $fatorEstimativaBase = null,
        public int|null    $mesesMaximo = null
    ) {
        $this->validate();
    }

    public function aplicavelA(PerfilTributario $perfil): bool
    {
        if (!isset($this->percentuais[$perfil->regime->value])) {
            return false;
        }

        return empty($this->segmentos) || in_array($perfil->segmento, $this->segmentos, true);
    }

    public function percentualMinimo(RegimeTributario $regime): float
    {
        return $this->faixa($regime)[0];
    }

    public function percentualMaximo(RegimeTributario $regime): float
    {
        return $this->faixa($regime)[1];
    }

    private function faixa(RegimeTributario $regime): array
    {
        if (!isset($this->percentuais[$regime->value])) {
            throw new DomainException("Tese {$this->chave} não se aplica ao regime {$regime->label()}");
        }

        return $this->percentuais[$regime->value];
    }

    private function validate(): void
    {
        if (empty($this->chave) || empty($this->titulo)) {
            throw new DomainException('Tese deve ter chave e título');
        }

        if (empty($this->percentuais)) {
            throw new DomainException("Tese {$this->chave} deve ter percentuais para ao menos um regime");
        }

        foreach ($this->percentuais as $regime => $faixa) {
            if (RegimeTributario::tryFrom($regime) === null) {
                throw new DomainException("Tese {$this->chave} possui regime inválido: {$regime}");
            }

            if (!is_array($faixa) || !array_is_list($faixa) || count($faixa) !== 2 || $faixa[0] < 0 || $faixa[0] > $faixa[1] || $faixa[1] > 1) {
                throw new DomainException("Tese {$this->chave} possui faixa de percentual inválida para {$regime}");
            }
        }

        foreach ($this->segmentos as $segmento) {
            if (!$segmento instanceof Segmento) {
                throw new DomainException("Tese {$this->chave} possui segmento inválido");
            }
        }

        if ($this->fatorEstimativaBase !== null && $this->fatorEstimativaBase <= 0) {
            throw new DomainException("Tese {$this->chave} possui fator de estimativa da base inválido");
        }

        if ($this->mesesMaximo !== null && ($this->mesesMaximo < 1 || $this->mesesMaximo > PerfilTributario::MESES_MAXIMO)) {
            throw new DomainException("Tese {$this->chave} possui limite de meses inválido");
        }
    }
}
