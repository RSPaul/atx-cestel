<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserCards extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'card_name', 'card_number', 'expiry_month', 'expiry_year', 'security_code', 'zip', 'b_address', 'b_city_state'
    ];
}
