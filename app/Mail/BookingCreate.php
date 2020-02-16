<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class BookingCreate extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
				 return $this->from(env('FROM_EMAIL'),env('FROM_NAME'))
                ->subject('Booking Successful')
                ->view('emails.booking_create');
    }
}
