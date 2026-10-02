<?php

namespace Biblioteca\Simulador\Domain\Entity;

use Biblioteca\Simulador\Domain\Enum\RegimeTributario;
use Biblioteca\Simulador\Domain\Enum\Segmento;
use DomainException;

readonly class PerfilTributario
{
    public const MESES_MAXIMO = 60;

    public function __construct(
        public RegimeTributario $regime,
        public Segmento         $segmento,
        public float            $faturamentoMensal,
        public float            $folhaPagamentoMensal = 0,
        public float            $energiaMensal = 0,
        public float            $combustivelMensal = 0,
        public float            $icmsMensal = 0,
        public float            $issMensal = 0,
        public float            $percentualMonofasico = 0,
        public int              $meses = self::MESES_MAXIMO
    ) {
        $this->validate();
    }

    public function receitaMonofasicaMensal(): float
    {
        return $this->faturamentoMensal * $this->percentualMonofasico / 100;
    }

    private function validate(): void
    {
        if ($this->faturamentoMensal <= 0) {
            throw new DomainException('Faturamento mensal deve ser maior que zero');
        }

        $valores = [
            'Folha de pagamento' => $this->folhaPagamentoMensal,
            'Gasto com energia elétrica' => $this->energiaMensal,
            'Gasto com combustíveis' => $this->combustivelMensal,
            'ICMS mensal' => $this->icmsMensal,
            'ISS mensal' => $this->issMensal,
        ];

        foreach ($valores as $descricao => $valor) {
            if ($valor < 0) {
                throw new DomainException("{$descricao} não pode ser negativo");
            }
        }

        if ($this->percentualMonofasico < 0 || $this->percentualMonofasico > 100) {
            throw new DomainException('Percentual de produtos monofásicos deve estar entre 0 e 100');
        }

        if ($this->meses < 1 || $this->meses > self::MESES_MAXIMO) {
            throw new DomainException('Período de análise deve estar entre 1 e ' . self::MESES_MAXIMO . ' meses');
        }
    }
}
