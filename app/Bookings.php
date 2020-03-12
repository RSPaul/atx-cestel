<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Bookings extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'service_type', 'service_address', 'service_categories', 'service_quantity', 'service_day', 'service_time', 'service_laundress', 'service_package','service_tax', 'service_amount', 'service_job_details', 'service_folding_details', 'service_hanging_details', 'service_washing_details', 'service_description', 'transfer_group', 'status', 'payment_request', 'service_reminder_sent'
    ];
}
