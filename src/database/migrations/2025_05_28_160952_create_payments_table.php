<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');

            // FK para o pedido
            $table->foreignId('order_id')
                ->constrained('orders')
                ->onDelete('cascade');

            // UUID para correlacionar com external_id da Paggue
            $table->uuid('external_id')->unique();

            // Dados da cobrança
            $table->string('payer_name');
            $table->unsignedBigInteger('amount'); // em centavos
            $table->string('description')->nullable();

            // Payload bruto retornado pela Paggue
            $table->json('pix_payload')->nullable();

            // Se a Paggue retornar transaction_id separado
            $table->string('pix_transaction_id')->nullable();

            // Código PIX em si (qr code/string)
            $table->text('payment')->nullable();

            // Identificadores extras
            $table->string('end_to_end_id')->nullable();
            $table->string('reference')->nullable();

            // Status do fluxo interno
            $table->enum('status', ['waiting', 'success', 'failed'])
                ->default('waiting');

            // Quando foi pago / expirou
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expiration_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
