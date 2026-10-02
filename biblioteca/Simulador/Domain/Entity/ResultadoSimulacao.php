<?php

namespace Biblioteca\Simulador\Domain\Entity;

use ArrayIterator;
use Countable;
use DomainException;
use IteratorAggregate;
use Traversable;

class ResultadoSimulacao implements Countable, IteratorAggregate
{
    private array $oportunidades = [];

    public function __construct(
        public readonly int   $meses,
        public readonly float $selicMensal = 0
    ) {
    }

    public function adicionar(Oportunidade $oportunidade): void
    {
        if (isset($this->oportunidades[$oportunidade->chave])) {
            throw new DomainException("Oportunidade {$oportunidade->chave} já adicionada");
        }

        $this->oportunidades[$oportunidade->chave] = $oportunidade;
    }

    public function possuiOportunidades(): bool
    {
        return $this->count() > 0;
    }

    public function valorMensalMinimo(): float
    {
        return $this->somar(fn (Oportunidade $o) => $o->valorMensalMinimo());
    }

    public function valorMensalMaximo(): float
    {
        return $this->somar(fn (Oportunidade $o) => $o->valorMensalMaximo());
    }

    public function principalMinimo(): float
    {
        return $this->somar(fn (Oportunidade $o) => $o->principalMinimo());
    }

    public function principalMaximo(): float
    {
        return $this->somar(fn (Oportunidade $o) => $o->principalMaximo());
    }

    public function correcaoMinima(): float
    {
        return $this->somar(fn (Oportunidade $o) => $o->correcaoMinima());
    }

    public function correcaoMaxima(): float
    {
        return $this->somar(fn (Oportunidade $o) => $o->correcaoMaxima());
    }

    public function totalMinimo(): float
    {
        return $this->somar(fn (Oportunidade $o) => $o->totalMinimo());
    }

    public function totalMaximo(): float
    {
        return $this->somar(fn (Oportunidade $o) => $o->totalMaximo());
    }

    private function somar(callable $valor): float
    {
        return round(array_sum(array_map($valor, $this->oportunidades)), 2);
    }

    public function toArray(): array
    {
        return [
            'meses' => $this->meses,
            'selic_mensal' => $this->selicMensal,
            'oportunidades' => array_values(array_map(
                fn (Oportunidade $o) => $o->toArray(),
                $this->oportunidades
            )),
            'totais' => [
                'valor_mensal_minimo' => $this->valorMensalMinimo(),
                'valor_mensal_maximo' => $this->valorMensalMaximo(),
                'principal_minimo' => $this->principalMinimo(),
                'principal_maximo' => $this->principalMaximo(),
                'correcao_minima' => $this->correcaoMinima(),
                'correcao_maxima' => $this->correcaoMaxima(),
                'total_minimo' => $this->totalMinimo(),
                'total_maximo' => $this->totalMaximo(),
            ],
        ];
    }

    public function count(): int
    {
        return count($this->oportunidades);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->oportunidades);
    }
}
