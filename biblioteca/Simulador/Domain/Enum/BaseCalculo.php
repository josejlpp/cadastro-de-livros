<?php

namespace Biblioteca\Simulador\Domain\Enum;

use Biblioteca\Simulador\Domain\Entity\PerfilTributario;

enum BaseCalculo: string
{
    case Faturamento = 'faturamento';
    case FolhaPagamento = 'folha_pagamento';
    case Energia = 'energia';
    case Combustivel = 'combustivel';
    case Icms = 'icms';
    case Iss = 'iss';
    case ReceitaMonofasica = 'receita_monofasica';

    public function label(): string
    {
        return match ($this) {
            self::Faturamento => 'Faturamento mensal',
            self::FolhaPagamento => 'Folha de pagamento mensal',
            self::Energia => 'Gasto mensal com energia elétrica',
            self::Combustivel => 'Gasto mensal com combustíveis',
            self::Icms => 'ICMS destacado nas vendas (mês)',
            self::Iss => 'ISS sobre os serviços (mês)',
            self::ReceitaMonofasica => 'Receita mensal com produtos monofásicos',
        };
    }

    public function valor(PerfilTributario $perfil): float
    {
        return match ($this) {
            self::Faturamento => $perfil->faturamentoMensal,
            self::FolhaPagamento => $perfil->folhaPagamentoMensal,
            self::Energia => $perfil->energiaMensal,
            self::Combustivel => $perfil->combustivelMensal,
            self::Icms => $perfil->icmsMensal,
            self::Iss => $perfil->issMensal,
            self::ReceitaMonofasica => $perfil->receitaMonofasicaMensal(),
        };
    }
}
