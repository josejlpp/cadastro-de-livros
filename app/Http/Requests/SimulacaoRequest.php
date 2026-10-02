<?php

namespace App\Http\Requests;

use Biblioteca\Simulador\Domain\Entity\Contato;
use Biblioteca\Simulador\Domain\Entity\PerfilTributario;
use Biblioteca\Simulador\Domain\Enum\RegimeTributario;
use Biblioteca\Simulador\Domain\Enum\Segmento;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SimulacaoRequest extends FormRequest
{
    private const VALOR_MAXIMO = 100000000000;

    public const CAMPOS_NUMERICOS = [
        'faturamento_mensal',
        'folha_pagamento_mensal',
        'energia_mensal',
        'combustivel_mensal',
        'icms_mensal',
        'iss_mensal',
        'percentual_monofasico',
    ];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Converte valores digitados no formato brasileiro ("R$ 1.234,56") para numérico.
     */
    protected function prepareForValidation(): void
    {
        $valores = [];
        foreach (self::CAMPOS_NUMERICOS as $campo) {
            if ($this->has($campo)) {
                $valores[$campo] = self::paraNumero($this->input($campo));
            }
        }

        $this->merge($valores);
    }

    private static function paraNumero(mixed $valor): mixed
    {
        if (!is_string($valor)) {
            return $valor;
        }

        $valor = trim(str_replace(['R$', '%', ' ', "\u{00A0}"], '', $valor));
        if ($valor === '') {
            return null;
        }

        if (str_contains($valor, ',')) {
            return str_replace(['.', ','], ['', '.'], $valor);
        }

        // Sem vírgula, ponto seguido de 3 dígitos é separador de milhar: "1.500" ou "1.250.000"
        if (preg_match('/^\d{1,3}(\.\d{3})+$/', $valor)) {
            return str_replace('.', '', $valor);
        }

        return $valor;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Limite de R$ 100 bilhões/mês mantém os totais dentro de decimal(15,2)
        $valorMonetario = 'nullable|numeric|min:0|max:' . self::VALOR_MAXIMO;

        return [
            'regime' => ['required', Rule::enum(RegimeTributario::class)],
            'segmento' => ['required', Rule::enum(Segmento::class)],
            'faturamento_mensal' => 'required|numeric|gt:0|max:' . self::VALOR_MAXIMO,
            'folha_pagamento_mensal' => $valorMonetario,
            'energia_mensal' => $valorMonetario,
            'combustivel_mensal' => $valorMonetario,
            'icms_mensal' => $valorMonetario,
            'iss_mensal' => $valorMonetario,
            'percentual_monofasico' => 'nullable|numeric|min:0|max:100',
            'meses' => 'required|integer|min:1|max:' . PerfilTributario::MESES_MAXIMO,
            'nome' => 'required|string|min:3|max:100',
            'email' => 'required|email|max:150',
            'telefone' => ['required', 'string', 'max:20', 'regex:/^\D*(\d\D*){10,11}$/'],
            'empresa' => 'required|string|min:2|max:150',
            'cnpj' => [
                'nullable',
                'string',
                'max:18',
                function (string $attribute, mixed $value, Closure $fail) {
                    if (!Contato::cnpjValido($value)) {
                        $fail('O CNPJ informado é inválido');
                    }
                },
            ],
            'aceite' => 'accepted',
        ];
    }

    public function messages(): array
    {
        $mensagens = [];
        foreach (self::CAMPOS_NUMERICOS as $campo) {
            $mensagens["{$campo}.numeric"] = 'O campo :attribute deve ser um valor numérico';
            $mensagens["{$campo}.min"] = 'O campo :attribute não pode ser negativo';
            $mensagens["{$campo}.max"] = 'O campo :attribute excede o valor máximo permitido';
        }

        return array_merge($mensagens, [
            'regime.required' => 'Selecione o regime tributário',
            'regime.enum' => 'Regime tributário inválido',
            'segmento.required' => 'Selecione o segmento de atuação',
            'segmento.enum' => 'Segmento de atuação inválido',
            'faturamento_mensal.required' => 'O campo faturamento mensal é obrigatório',
            'faturamento_mensal.gt' => 'O faturamento mensal deve ser maior que zero',
            'percentual_monofasico.max' => 'O percentual de produtos monofásicos deve ser no máximo 100%',
            'meses.required' => 'Selecione o período de análise',
            'meses.integer' => 'Período de análise inválido',
            'meses.min' => 'Período de análise inválido',
            'meses.max' => 'O período de análise é de no máximo ' . PerfilTributario::MESES_MAXIMO . ' meses',
            'nome.required' => 'O campo nome é obrigatório',
            'nome.min' => 'O campo nome deve ter no mínimo 3 caracteres',
            'nome.max' => 'O campo nome deve ter no máximo 100 caracteres',
            'email.required' => 'O campo e-mail é obrigatório',
            'email.email' => 'Informe um e-mail válido',
            'email.max' => 'O campo e-mail deve ter no máximo 150 caracteres',
            'telefone.required' => 'O campo telefone é obrigatório',
            'telefone.max' => 'Informe um telefone válido com DDD',
            'telefone.regex' => 'Informe um telefone válido com DDD',
            'empresa.required' => 'O campo empresa é obrigatório',
            'empresa.min' => 'O campo empresa deve ter no mínimo 2 caracteres',
            'empresa.max' => 'O campo empresa deve ter no máximo 150 caracteres',
            'cnpj.max' => 'O CNPJ informado é inválido',
            'aceite.accepted' => 'É necessário autorizar o contato para ver o resultado',
        ]);
    }

    public function attributes(): array
    {
        return [
            'faturamento_mensal' => 'faturamento mensal',
            'folha_pagamento_mensal' => 'folha de pagamento',
            'energia_mensal' => 'gasto com energia elétrica',
            'combustivel_mensal' => 'gasto com combustíveis',
            'icms_mensal' => 'ICMS mensal',
            'iss_mensal' => 'ISS mensal',
            'percentual_monofasico' => 'percentual de produtos monofásicos',
        ];
    }
}
