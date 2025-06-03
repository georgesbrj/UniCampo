<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->date('data_nascimento');
            $table->enum('tipo_pessoa', ['física', 'jurídica']);
            $table->string('cpf_cnpj')->unique();
            $table->string('email')->unique();
            $table->string('telefone');
            $table->foreignId('id_endereco')->constrained('enderecos')->onDelete('cascade');
            $table->foreignId('id_profissao')->constrained('profissoes')->onDelete('cascade');
            $table->enum('status', ['ativo', 'inativo'])->default('ativo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
