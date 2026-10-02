<?php

namespace Biblioteca\Simulador\Domain\Enum;

enum Segmento: string
{
    case Comercio = 'comercio';
    case Industria = 'industria';
    case Servicos = 'servicos';
    case Saude = 'saude';
    case Transporte = 'transporte';

    public function label(): string
    {
        return match ($this) {
            self::Comercio => 'Comércio (varejo / atacado)',
            self::Industria => 'Indústria',
            self::Servicos => 'Prestação de serviços',
            self::Saude => 'Saúde (clínicas, laboratórios, hospitais)',
            self::Transporte => 'Transporte e logística',
        };
    }

    public static function opcoes(): array
    {
        $opcoes = [];
        foreach (self::cases() as $segmento) {
            $opcoes[$segmento->value] = $segmento->label();
        }

        return $opcoes;
    }
}
