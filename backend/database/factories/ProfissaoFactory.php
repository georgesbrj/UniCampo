<?php

namespace Database\Factories;

use App\Models\Profissao;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfissaoFactory extends Factory
{
    protected $model = Profissao::class;

    public function definition(): array
    {
        return [
            'nome' => $this->faker->jobTitle,
        ];
    }
}
