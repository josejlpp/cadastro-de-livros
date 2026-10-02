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
        Schema::create('simulacoes', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('nome', 100);
            $table->string('email', 150);
            $table->string('telefone', 20);
            $table->string('empresa', 150);
            $table->string('cnpj', 14)->nullable();
            $table->string('regime', 30);
            $table->string('segmento', 30);
            $table->decimal('faturamento_mensal', 15, 2);
            $table->decimal('folha_pagamento_mensal', 15, 2)->default(0);
            $table->decimal('energia_mensal', 15, 2)->default(0);
            $table->decimal('combustivel_mensal', 15, 2)->default(0);
            $table->decimal('icms_mensal', 15, 2)->default(0);
            $table->decimal('iss_mensal', 15, 2)->default(0);
            $table->decimal('percentual_monofasico', 5, 2)->default(0);
            $table->unsignedTinyInteger('meses');
            $table->decimal('total_minimo', 15, 2);
            $table->decimal('total_maximo', 15, 2);
            $table->json('resultado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simulacoes');
    }
};
