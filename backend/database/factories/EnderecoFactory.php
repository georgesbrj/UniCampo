<?php

namespace Database\Factories;

use App\Models\Endereco;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnderecoFactory extends Factory
{
    protected $model = Endereco::class;

    public function definition(): array
    {
        return [
            'endereco' => $this->faker->streetName,
            'numero' => $this->faker->buildingNumber,
            'cidade' => $this->faker->city,
            'estado' => $this->faker->stateAbbr,
            'cep' => $this->faker->postcode,
        ];
    }
}
