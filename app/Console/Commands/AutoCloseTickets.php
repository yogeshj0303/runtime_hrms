<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;
use App\Models\TicketHistory;
use Carbon\Carbon;

class AutoCloseTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:auto-close';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically close tickets that have been resolved for over a week.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $oneWeekAgo = Carbon::now()->subDays(7);
        
        $tickets = Ticket::where('status', 'Resolved')
            ->where('updated_at', '<', $oneWeekAgo)
            ->get();
            
        $count = 0;
        foreach ($tickets as $ticket) {
            $ticket->status = 'Closed';
            $ticket->closed_at = now();
            $ticket->save();
            
            TicketHistory::create([
                'ticket_id' => $ticket->id,
                'actor_id' => 0, // System
                'action' => 'Auto-Closed',
                'old_value' => 'Resolved',
                'new_value' => 'Closed',
            ]);
            $count++;
        }
        
        $this->info("Successfully auto-closed $count tickets.");
    }
}
