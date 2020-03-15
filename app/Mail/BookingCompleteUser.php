<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BookingCompleteUser extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $laundress;
    public $booking;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $laundress, $booking)
    {
        $this->user = $user;
        $this->laundress = $laundress;
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
                ->subject('Booking Completed')
                ->view('emails.booking-complete-user');
    }
}
