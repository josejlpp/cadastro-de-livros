<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssuntoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'descricao' => 'required|string|max:20|min:3',
        ];
    }

    public function messages(): array
    {
        return [
            'descricao.required' => 'O campo descrição é obrigatório',
            'descricao.string' => 'O campo descrição deve ser uma string',
            'descricao.max' => 'O campo descrição deve ter no máximo 20 caracteres',
            'descricao.min' => 'O campo descrição deve ter no mínimo 3 caracteres',
        ];
    }
}
