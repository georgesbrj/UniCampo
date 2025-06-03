<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

    public function definition(): array
    {
        return [
            'nome' => $this->faker->name,
            'data_nascimento' => $this->faker->date(),
            'tipo_pessoa' => $this->faker->randomElement(['físico', 'Juridico']),
            'cpf_cnpj' => $this->faker->numerify('###.###.###-##'),
            'email' => $this->faker->unique()->safeEmail,
            'telefone' => $this->faker->phoneNumber,
            'id_endereco' => \App\Models\Endereco::factory(),
            'id_profissao' => \App\Models\Profissao::factory(),
            'status' => $this->faker->boolean,
        ];
    }
}
