<?php

namespace Biblioteca\Simulador\Domain\Enum;

enum RegimeTributario: string
{
    case SimplesNacional = 'simples_nacional';
    case LucroPresumido = 'lucro_presumido';
    case LucroReal = 'lucro_real';

    public function label(): string
    {
        return match ($this) {
            self::SimplesNacional => 'Simples Nacional',
            self::LucroPresumido => 'Lucro Presumido',
            self::LucroReal => 'Lucro Real',
        };
    }

    public static function opcoes(): array
    {
        $opcoes = [];
        foreach (self::cases() as $regime) {
            $opcoes[$regime->value] = $regime->label();
        }

        return $opcoes;
    }
}
