<?php

namespace Biblioteca\Simulador\Domain\Dto;

class SimulacaoDto
{
    public function __construct(
        public string      $nome,
        public string      $email,
        public string      $telefone,
        public string      $empresa,
        public string|null $cnpj,
        public string      $regime,
        public string      $segmento,
        public float       $faturamentoMensal,
        public float       $folhaPagamentoMensal = 0,
        public float       $energiaMensal = 0,
        public float       $combustivelMensal = 0,
        public float       $icmsMensal = 0,
        public float       $issMensal = 0,
        public float       $percentualMonofasico = 0,
        public int         $meses = 60
    ) {
    }
}
