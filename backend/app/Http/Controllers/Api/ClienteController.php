<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClienteRequest;
use App\Models\Cliente;
use App\Models\Endereco;
use App\Models\Profissao;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Rules\CpfOuCnpj;

/**
 * @OA\Info(
 *     title="API de Gerenciamento de Clientes - UNICAMPO",
 *     version="1.0.0",
 *     description="Documentação da API desenvolvida para a avaliação técnica da UNICAMPO.",
 *     @OA\Contact(
 *         name="George Bezerra",
 *         
 *     )
 * )
 */
class ClienteController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/clientes",
     *     summary="Listar clientes com filtros e paginação",
     *     tags={"Clientes"},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filtrar por status (ativo/inativo)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Buscar por nome, email ou CPF/CNPJ",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista paginada de clientes"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = Cliente::with(['endereco', 'profissao']);

        // Filtro por status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtro de busca (nome, email, cpf_cnpj)
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('cpf_cnpj', 'like', "%{$search}%");
            });
        }

        // Ordenação
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        $query->orderBy($sort, $order);

        return response()->json($query->paginate(10));
    }

    /**
     * @OA\Post(
     *     path="/api/clientes",
     *     summary="Criar um novo cliente",
     *     tags={"Clientes"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Cliente")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Cliente criado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação"
     *     )
     * )
     */
    public function store(StoreClienteRequest $request)
    {
        $endereco = Endereco::create($request->endereco);

        $cliente = Cliente::create([
            'nome' => trim($request->nome),
            'data_nascimento' => $request->data_nascimento,
            'tipo_pessoa' => $request->tipo_pessoa,
            'cpf_cnpj' => $request->cpf_cnpj,
            'email' => trim($request->email),
            'telefone' => $request->telefone,
            'id_endereco' => $endereco->id,
            'id_profissao' => $request->id_profissao,
            'status' => $request->status,
        ]);

        return response()->json($cliente->load('endereco', 'profissao'), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/clientes/{id}",
     *     summary="Buscar detalhes de um cliente",
     *     tags={"Clientes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do cliente",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Dados do cliente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cliente não encontrado"
     *     )
     * )
     */
    public function show(string $id)
    {
        $cliente = Cliente::with(['endereco', 'profissao'])->findOrFail($id);
        return response()->json($cliente);
    }

    /**
     * @OA\Put(
     *     path="/api/clientes/{id}",
     *     summary="Atualizar um cliente existente",
     *     tags={"Clientes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do cliente",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Cliente")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cliente atualizado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação"
     *     )
     * )
     */
    public function update(Request $request, string $id)
    {
        $cliente = Cliente::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nome' => 'sometimes|string|max:255',
            'data_nascimento' => 'sometimes|date',
            'tipo_pessoa' => ['sometimes', Rule::in(['física', 'jurídica'])],
            'cpf_cnpj' => [
                'sometimes',
                'string',
                new CpfOuCnpj, // ou new CpfOuCnpjPorTipo($request->tipo_pessoa)
                Rule::unique('clientes')->ignore($cliente->id),
            ],
            'email' => [
                'sometimes',
                'email',
                Rule::unique('clientes')->ignore($cliente->id),
            ],
            'telefone' => ['sometimes', 'regex:/^(\(\d{2}\) \d{4,5}-\d{4}|[0-9]{10,11})$/'],
            'id_profissao' => 'sometimes|exists:profissoes,id',
            'status' => ['sometimes', Rule::in(['ativo', 'inativo'])],
        ], [
            'cpf_cnpj.unique' => 'Este CPF/CNPJ já está cadastrado.',
            'email.unique' => 'Este e-mail já está em uso.',
            'telefone.regex' => 'Formato de telefone inválido.',
            'tipo_pessoa.in' => 'Tipo de pessoa deve ser "física" ou "jurídica".',
        ]);

        if ($validator->fails()) {
            return response()->json(['erros' => $validator->errors()], 422);
        }

        $cliente->update($request->only([
            'nome',
            'data_nascimento',
            'tipo_pessoa',
            'cpf_cnpj',
            'email',
            'telefone',
            'id_profissao',
            'status',
        ]));

        return response()->json($cliente->load('endereco', 'profissao'));
    }

    /**
     * @OA\Delete(
     *     path="/api/clientes/{id}",
     *     summary="Excluir um cliente",
     *     tags={"Clientes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do cliente",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cliente excluído com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cliente não encontrado"
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return response()->json(['mensagem' => 'Cliente excluído com sucesso']);
    }

    /**
     * @OA\Get(
     *     path="/api/professions",
     *     summary="Listar profissões disponíveis",
     *     tags={"Profissões"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de profissões"
     *     )
     * )
     */
    public function getProfessions()
    {
        $profissoes = Profissao::all();
        return response()->json($profissoes);
    }

    /**
     * @OA\Get(
     *     path="/api/clientes/search",
     *     summary="Buscar clientes por nome, email ou CPF/CNPJ",
     *     tags={"Clientes"},
     *     @OA\Parameter(
     *         name="nome",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="cpf_cnpj",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de clientes filtrada"
     *     )
     * )
     */
    public function searchClient(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'cpf_cnpj' => 'sometimes|string|max:20'
        ]);

        $query = Cliente::with(['endereco', 'profissao']);

        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $validated['nome'] . '%');
        }

        if ($request->filled('cpf_cnpj')) {
            $query->where('cpf_cnpj', 'like', '%' . $validated['cpf_cnpj'] . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $validated['email'] . '%');
        }

        return response()->json($query->get());
    }

    /**
     * Search for clients with advanced filters.
     */
    protected function applySearchFilters($query, $request)
    {
        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->nome . '%');
        }

        if ($request->filled('cpf_cnpj')) {
            $query->where('cpf_cnpj', 'like', '%' . $request->cpf_cnpj . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }
    }
}
