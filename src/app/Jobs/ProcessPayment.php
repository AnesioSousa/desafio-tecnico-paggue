<?php
// src/app/Jobs/ProcessPayment.php

namespace App\Jobs;

use App\Models\Payment;
use App\Jobs\NotifyAdminOfPayment;
use App\Jobs\NotifyCustomerOfTickets;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $paymentId;

    public function __construct(int $paymentId)
    {
        $this->paymentId = $paymentId;
    }

    public function handle(): void
    {
        $payment = Payment::with('order')->findOrFail($this->paymentId);
        $order = $payment->order;

        // Idempotência: se já pago, sai
        if ($order->status === 'paid') {
            return;
        }

        // 1) Marca o pedido como pago
        $order->update(['status' => 'paid']);

        // 2) Dispara notificações
        //NotifyAdminOfPayment::dispatch($order);
        //NotifyCustomerOfTickets::dispatch($order);
    }
}
