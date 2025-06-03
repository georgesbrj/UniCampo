<?php

namespace App\Http\Requests;

use App\Rules\CpfOuCnpjPorTipo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClienteRequest extends FormRequest
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
            'nome' => 'required|string|max:255',
            'data_nascimento' => 'required|date',
            'tipo_pessoa' => ['required', Rule::in(['física', 'jurídica'])],
            'cpf_cnpj' => ['required', new CpfOuCnpjPorTipo($this->tipo_pessoa), Rule::unique('clientes')],
            'email' => 'required|email|unique:clientes',
            'telefone' => ['required', 'regex:/^(\(\d{2}\) \d{4,5}-\d{4}|[0-9]{10,11})$/'],
            'endereco' => 'required|array',
            'endereco.endereco' => 'required|string',
            'endereco.numero' => 'required|string',
            'endereco.bairro' => 'required|string',
            'endereco.cidade' => 'required|string',
            'endereco.uf' => 'required|string|size:2',
            'endereco.complemento' => 'nullable|string',
            'id_profissao' => 'required|exists:profissoes,id',
            'status' => ['required', Rule::in(['ativo', 'inativo'])]
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'cpf_cnpj.cpf_ou_cnpj_por_tipo' => 'CPF ou CNPJ inválido conforme o tipo de pessoa.',
            'cpf_cnpj.unique' => 'Este CPF/CNPJ já está cadastrado em nosso sistema.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'O campo email deve ser um endereço de email válido.',
            'email.unique' => 'Este email já está cadastrado em nosso sistema.',
            'telefone' => 'Formato de telefone inválido.',
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.string' => 'O nome deve ser um texto.',
            'nome.max' => 'O nome não pode ter mais que 255 caracteres.',
        ];
    }
} 
