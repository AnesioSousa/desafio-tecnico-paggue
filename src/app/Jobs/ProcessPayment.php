<?php

namespace App\Jobs;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $paymentId;

    /**
     * Create a new job instance.
     */
    public function __construct($paymentId)
    {
        $this->paymentId = $paymentId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $payment = Payment::findOrFail($this->paymentId);
        $order = Order::where('id', $payment->order_id)->firstOrFail();

        // 1) Mark order paid
        $order->update(['status' => 'paid']);

        // 2) Generate tickets (if not already)
        if ($order->tickets()->count() === 0) {
            // e.g. batch logic or reuse the nested-store example
            $order->tickets()->createMany($payment->metadata['tickets']);
        }

        // 3) Notify customer & admin
        //NotifyAdminOfPayment::dispatch($order);
        //NotifyCustomerOfTickets::dispatch($order);
    }
}
