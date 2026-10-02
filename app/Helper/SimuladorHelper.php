<?php

namespace App\Helper;

use Biblioteca\Simulador\Domain\Dto\SimulacaoDto;
use Illuminate\Support\HtmlString;

class SimuladorHelper
{
    public static function buildSimulacaoDtoFromRequest(array $request): SimulacaoDto
    {
        return new SimulacaoDto(
            nome: trim($request['nome']),
            email: trim($request['email']),
            telefone: trim($request['telefone']),
            empresa: trim($request['empresa']),
            cnpj: $request['cnpj'] ?? null,
            regime: $request['regime'],
            segmento: $request['segmento'],
            faturamentoMensal: (float) $request['faturamento_mensal'],
            folhaPagamentoMensal: (float) ($request['folha_pagamento_mensal'] ?? 0),
            energiaMensal: (float) ($request['energia_mensal'] ?? 0),
            combustivelMensal: (float) ($request['combustivel_mensal'] ?? 0),
            icmsMensal: (float) ($request['icms_mensal'] ?? 0),
            issMensal: (float) ($request['iss_mensal'] ?? 0),
            percentualMonofasico: (float) ($request['percentual_monofasico'] ?? 0),
            meses: (int) $request['meses']
        );
    }

    /**
     * Valor monetário reenviado ao formulário (old input) no formato brasileiro, para a
     * máscara não confundir o ponto decimal normalizado com separador de milhar.
     */
    public static function valorAntigo(string $campo): string|null
    {
        $valor = old($campo);

        return is_numeric($valor) ? number_format((float) $valor, 2, ',', '.') : $valor;
    }

    public static function moeda(float|int|null $valor): string
    {
        return 'R$ ' . number_format((float) $valor, 2, ',', '.');
    }

    public static function faixaMoeda(float|int|null $minimo, float|int|null $maximo): string
    {
        if (round((float) $minimo, 2) === round((float) $maximo, 2)) {
            return self::moeda($minimo);
        }

        return self::moeda($minimo) . ' a ' . self::moeda($maximo);
    }

    /**
     * Mesma faixa de faixaMoeda, sem quebrar linha dentro de cada valor.
     */
    public static function faixaMoedaHtml(float|int|null $minimo, float|int|null $maximo): HtmlString
    {
        $valores = array_map(
            fn (string $valor) => '<span class="text-nowrap">' . e($valor) . '</span>',
            explode(' a ', self::faixaMoeda($minimo, $maximo))
        );

        return new HtmlString(implode(' a ', $valores));
    }

    public static function percentual(float|int|null $fracao): string
    {
        $valor = rtrim(rtrim(number_format((float) $fracao * 100, 2, ',', '.'), '0'), ',');

        return $valor . '%';
    }

    public static function cnpj(string|null $cnpj): string
    {
        if (!$cnpj || strlen($cnpj) !== 14) {
            return $cnpj ?? '';
        }

        return substr($cnpj, 0, 2) . '.' . substr($cnpj, 2, 3) . '.' . substr($cnpj, 5, 3)
            . '/' . substr($cnpj, 8, 4) . '-' . substr($cnpj, 12, 2);
    }

    public static function linkWhatsapp(string $empresa): string|null
    {
        $numero = preg_replace('/\D/', '', (string) config('simulador.whatsapp'));
        if ($numero === '') {
            return null;
        }

        $mensagem = "Olá! Fiz a simulação de recuperação tributária para a empresa {$empresa} e gostaria de falar com um especialista.";

        return "https://wa.me/{$numero}?text=" . rawurlencode($mensagem);
    }
}
