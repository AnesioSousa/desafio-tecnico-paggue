<?php
//migrations/2025_05_23_165715_create_compras_table.php
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
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
        
            $table->foreignId('id_cliente')
                  ->constrained('clientes', 'id_cliente')   // <-- coluna correta
                  ->cascadeOnDelete();
        
            $table->date('data');
            $table->string('metodo_pagamento');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
