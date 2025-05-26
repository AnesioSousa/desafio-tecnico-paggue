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
        Schema::create('ingresso_compra', function (Blueprint $table) {
            $table->foreignId('id_compra')->constrained('compras')->onDelete('cascade');
            $table->foreignId('id_ingresso')->constrained('ingressos')->onDelete('cascade');
            $table->primary(['id_compra', 'id_ingresso']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingresso_compra');
    }
};
