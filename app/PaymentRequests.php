<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PaymentRequests extends Authenticatable
{
    use Notifiable;

    protected $table = 'payment_requests';
    
   protected $fillable = [
		'laundress_id', 'amount', 'booking_ids', 'status'
    ];
}
