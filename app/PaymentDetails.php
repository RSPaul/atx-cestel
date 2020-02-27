<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PaymentDetails extends Authenticatable
{
    use Notifiable;

    protected $table = 'payment_details';
    
   protected $fillable = [
		'user_id', 'account_type', 'routing_number', 'account_number', 'account_id', 'account', 'bank_name', 'last4'
    ];
}
