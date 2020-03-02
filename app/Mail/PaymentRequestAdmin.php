<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class PaymentRequestAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $laundress;
    public $price;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($laundress, $price)
    {
        $this->laundress = $laundress;
        $this->price = $price;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       return $this->from(env('FROM_EMAIL'),env('FROM_NAME'))
                ->subject('User Requested Payment')
                ->view('emails.payment-request-admin');
    }
}
