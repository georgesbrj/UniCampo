<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfissoesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profissoes = [
            'Administração',
            'Consultor',
            'Contabilidade',
            'Engenheiro de Software',
            'Logística',
            'Recursos Humanos',
        ];

        foreach ($profissoes as $profissao) {
            DB::table('profissoes')->insert([
                'nome_profissao' => $profissao,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
