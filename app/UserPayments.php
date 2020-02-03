<?php

namespace App;

use Illuminate\Notifications\Notifiable;

class UserPayments extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'transaction_id', 'service_payment_type', 'user_name', 'user_email', 'user_address', 'user_city', 'user_state', 'user_zip'
    ];
}
