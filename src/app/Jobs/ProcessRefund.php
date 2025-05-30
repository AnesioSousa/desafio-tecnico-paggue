<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class ProcessRefund implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $orderId;

    /**
     * Create a new job instance.
     *
     * @param  int  $orderId
     */
    public function __construct(int $orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $order = Order::findOrFail($this->orderId);

        // mark all tickets on this order as cancelled
        $order->tickets()->update(['status' => 'cancelled']);

        // optionally mark the order itself as refunded/cancelled
        $order->update(['status' => 'cancelled']);
    }
}
