<?php

namespace App\Console\Commands;

use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


class MarkRentalsOverdue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rentals:mark-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To mark rentals overdue and send communication regarding the same';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
{
    $overdueRentals = Rental::where('is_overdue', 0)
                            ->where('due_date', '<', Carbon::now())
                            ->get();
    // $rentals = Rental::with('user', 'book')->get();

     //dd($overdueRentals);

    if ($overdueRentals->isEmpty()) {
        echo "No overdue rentals found";
        return;
    }

    foreach ($overdueRentals as $rental) {
        if ($rental->user) {
            $rental->is_overdue = 1;
            $rental->save();
            
            // Trigger email notification
            $this->sendOverdueNotification($rental);
        } else {
            echo "Rental ID {$rental->id} does not have an associated user.";
        }
    }
}

protected function sendOverdueNotification($rental)
{
    try {
        Mail::to($rental->user->email)->send(new \App\Mail\OverdueRentalNotification($rental));
    } catch (\Exception $e) {
        Log::error("Failed to send email to {$rental->user->email} for rental ID {$rental->id}. Error: " . $e->getMessage());
    }
}


}
