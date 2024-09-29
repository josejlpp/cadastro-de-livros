<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LivroRequest extends FormRequest
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
            'titulo' => 'required|string|max:40|min:3',
            'editora' => 'required|string|max:40|min:3',
            'anoPublicacao' => 'required|integer|min:1900|max:2024',
            'valor' => 'required|numeric|min:10',
            'edicao' => 'required|integer|min:1',
            'autor_id' => 'required',
            'assunto_id' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required' => 'O campo título é obrigatório',
            'titulo.string' => 'O campo título deve ser uma string',
            'titulo.max' => 'O campo título deve ter no máximo 40 caracteres',
            'titulo.min' => 'O campo título deve ter no mínimo 3 caracteres',
            'editora.required' => 'O campo editora é obrigatório',
            'editora.string' => 'O campo editora deve ser uma string',
            'editora.max' => 'O campo editora deve ter no máximo 40 caracteres',
            'editora.min' => 'O campo editora deve ter no mínimo 3 caracteres',
            'anoPublicacao.required' => 'O campo ano de publicação é obrigatório',
            'anoPublicacao.integer' => 'O campo ano de publicação deve ser um número inteiro',
            'anoPublicacao.min' => 'O campo ano de publicação deve ser no mínimo 1900',
            'anoPublicacao.max' => 'O campo ano de publicação deve ser no máximo 2024',
            'valor.required' => 'O campo valor é obrigatório',
            'valor.numeric' => 'O campo valor deve ser um número',
            'valor.min' => 'O campo valor deve ser no mínimo 10',
            'edicao.required' => 'O campo edição é obrigatório',
            'edicao.integer' => 'O campo edição deve ser um número inteiro',
            'edicao.min' => 'O campo edição deve ser no mínimo 1',
            'autor_id.required' => 'O campo autor é obrigatório',
            'assunto_id.required' => 'O campo assunto é obrigatório',
        ];
    }
}
