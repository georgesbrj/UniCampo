<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClienteTest extends TestCase
{

    public function test_existe_ao_menos_um_cliente()
    {
        $this->assertTrue(Cliente::count() > 0, 'Nenhum cliente encontrado no banco.');
    }

    public function test_consulta_cliente_por_email()
    {
        $cliente = Cliente::whereNotNull('email')->first();
        $this->assertNotNull($cliente, 'Nenhum cliente com e-mail encontrado.');
    }

    public function test_cliente_tem_endereco_relacionado()
    {
        $cliente = Cliente::has('endereco')->first();
        $this->assertNotNull($cliente, 'Nenhum cliente com endereço relacionado encontrado.');
    }

    public function test_cliente_tem_profissao_relacionada()
    {
        $cliente = Cliente::has('profissao')->first();
        $this->assertNotNull($cliente, 'Nenhum cliente com profissão relacionada encontrado.');
    }

    /** @test */
    public function cliente_model_tem_fillable_definidos()
    {
        $cliente = new Cliente();

        $this->assertEquals([
            'nome',
            'data_nascimento',
            'tipo_pessoa',
            'cpf_cnpj',
            'email',
            'telefone',
            'id_endereco',
            'id_profissao',
            'status',
        ], $cliente->getFillable());
    }
}
