<?php

namespace App\Http\Controllers\Api;

/**
 * @OA\Schema(
 *     schema="Cliente",
 *     required={"nome", "data_nascimento", "tipo_pessoa", "cpf_cnpj", "email", "telefone", "endereco", "id_profissao", "status"},
 *     @OA\Property(property="nome", type="string"),
 *     @OA\Property(property="data_nascimento", type="string", format="date"),
 *     @OA\Property(property="tipo_pessoa", type="string", enum={"física", "jurídica"}),
 *     @OA\Property(property="cpf_cnpj", type="string"),
 *     @OA\Property(property="email", type="string", format="email"),
 *     @OA\Property(property="telefone", type="string"),
 *     @OA\Property(property="endereco", ref="#/components/schemas/Endereco"),
 *     @OA\Property(property="id_profissao", type="integer"),
 *     @OA\Property(property="status", type="string", enum={"ativo", "inativo"})
 * )
 *
 * @OA\Schema(
 *     schema="Endereco",
 *     required={"endereco", "numero", "bairro", "cidade", "uf"},
 *     @OA\Property(property="endereco", type="string"),
 *     @OA\Property(property="numero", type="string"),
 *     @OA\Property(property="bairro", type="string"),
 *     @OA\Property(property="cidade", type="string"),
 *     @OA\Property(property="uf", type="string", maxLength=2),
 *     @OA\Property(property="complemento", type="string")
 * )
 *
 * @OA\Schema(
 *     schema="Profissao",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="nome_profissao", type="string")
 * )
 */
class SwaggerDefinitionsController
{
    // Este controller existe apenas para armazenar os schemas do Swagger.
}
