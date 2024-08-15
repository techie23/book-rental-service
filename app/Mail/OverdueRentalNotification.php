<?php

namespace App\Mail;

use App\Models\Rental;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OverdueRentalNotification extends Mailable
{
    use Queueable, SerializesModels;
    public $rental;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Rental $rental)
    {
        $this->rental = $rental;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.overdue_rental')
                    ->subject('Overdue Rental Notification')
                    ->with([
                        'userName' => $this->rental->user->name,
                        'bookTitle' => $this->rental->book->title,
                        'dueDate' => $this->rental->due_date->toFormattedDateString(),
                    ]);
    }
}
