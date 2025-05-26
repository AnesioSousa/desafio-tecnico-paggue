<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//src/migrations/2025_05_23_163048_create_pfisicas_table.php

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pfisicas', function (Blueprint $table) {
            $table->foreignId('id_cliente')
                  ->constrained('clientes', 'id_cliente')  // aqui, indicamos a coluna correta
                  ->cascadeOnDelete()
                  ->primary();
        
            $table->string('cpf', 14)->unique();
            $table->enum('sexo', ['M', 'F', 'O'])->nullable()->comment('M = Masculino, F = Feminino, O = Outro');
            $table->date('data_nascimento');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pfisicas');
    }
};
