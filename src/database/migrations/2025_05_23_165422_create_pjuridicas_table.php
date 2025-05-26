<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
//src/migrations/2025_05_23_165422_create_pjuridicas_table.php
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pjuridicas', function (Blueprint $table) {
            $table->foreignId('id_cliente')          // cria a coluna id_cliente como unsignedBigInteger
                  ->constrained('clientes', 'id_cliente')
                  ->cascadeOnDelete()
                  ->primary();                       // se quiser que seja PK também
        
            $table->string('cnpj')->unique();
            $table->string('inscricao_estadual');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pjuridicas');
    }
};
