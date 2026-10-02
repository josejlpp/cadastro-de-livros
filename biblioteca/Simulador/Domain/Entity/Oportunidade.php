<?php

namespace Biblioteca\Simulador\Domain\Entity;

use DomainException;

/**
 * Estimativa de uma tese para um perfil: faixa mínima e máxima do valor mensal,
 * do principal acumulado no período e da correção pela SELIC.
 */
readonly class Oportunidade
{
    public function __construct(
        public string $chave,
        public string $titulo,
        public string $descricao,
        public string $fundamento,
        public string $baseDescricao,
        public float  $baseMensal,
        public bool   $baseEstimada,
        public float  $percentualMinimo,
        public float  $percentualMaximo,
        public int    $meses,
        public float  $selicMensal = 0
    ) {
        $this->validate();
    }

    public function valorMensalMinimo(): float
    {
        return round($this->baseMensal * $this->percentualMinimo, 2);
    }

    public function valorMensalMaximo(): float
    {
        return round($this->baseMensal * $this->percentualMaximo, 2);
    }

    public function principalMinimo(): float
    {
        return round($this->valorMensalMinimo() * $this->meses, 2);
    }

    public function principalMaximo(): float
    {
        return round($this->valorMensalMaximo() * $this->meses, 2);
    }

    public function correcaoMinima(): float
    {
        return round($this->valorMensalMinimo() * $this->fatorCorrecao(), 2);
    }

    public function correcaoMaxima(): float
    {
        return round($this->valorMensalMaximo() * $this->fatorCorrecao(), 2);
    }

    public function totalMinimo(): float
    {
        return round($this->principalMinimo() + $this->correcaoMinima(), 2);
    }

    public function totalMaximo(): float
    {
        return round($this->principalMaximo() + $this->correcaoMaxima(), 2);
    }

    /**
     * A SELIC é acumulada de forma simples desde o mês do pagamento indevido:
     * o valor pago há 1 mês recebe 1 × SELIC, o de 2 meses 2 × SELIC, e assim por diante.
     */
    private function fatorCorrecao(): float
    {
        return $this->selicMensal * $this->meses * ($this->meses + 1) / 2;
    }

    public function toArray(): array
    {
        return [
            'chave' => $this->chave,
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'fundamento' => $this->fundamento,
            'base_descricao' => $this->baseDescricao,
            'base_mensal' => round($this->baseMensal, 2),
            'base_estimada' => $this->baseEstimada,
            'percentual_minimo' => $this->percentualMinimo,
            'percentual_maximo' => $this->percentualMaximo,
            'meses' => $this->meses,
            'valor_mensal_minimo' => $this->valorMensalMinimo(),
            'valor_mensal_maximo' => $this->valorMensalMaximo(),
            'principal_minimo' => $this->principalMinimo(),
            'principal_maximo' => $this->principalMaximo(),
            'correcao_minima' => $this->correcaoMinima(),
            'correcao_maxima' => $this->correcaoMaxima(),
            'total_minimo' => $this->totalMinimo(),
            'total_maximo' => $this->totalMaximo(),
        ];
    }

    private function validate(): void
    {
        if ($this->baseMensal <= 0) {
            throw new DomainException("Oportunidade {$this->chave} deve ter base de cálculo maior que zero");
        }

        if ($this->percentualMinimo < 0 || $this->percentualMinimo > $this->percentualMaximo) {
            throw new DomainException("Oportunidade {$this->chave} possui faixa de percentual inválida");
        }

        if ($this->meses < 1) {
            throw new DomainException("Oportunidade {$this->chave} deve ter ao menos um mês");
        }

        if ($this->selicMensal < 0) {
            throw new DomainException('SELIC mensal não pode ser negativa');
        }
    }
}
