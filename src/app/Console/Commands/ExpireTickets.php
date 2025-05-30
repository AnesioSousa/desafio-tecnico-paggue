<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;

class ExpireTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-tickets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Ticket::where('status', 'active')
            ->whereHas('order.event', fn($q) => $q->where('date', '<', now()))
            ->update(['status' => 'expired']);

        $this->info('Tickets expired successfully.');
    }
}
