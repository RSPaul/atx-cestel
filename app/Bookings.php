<?php

namespace App;

use Illuminate\Notifications\Notifiable;

class Bookings extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'service_type', 'service_categories', 'service_beds', 'service_day', 'service_time', 'service_laundress', 'service_package', 'service_amount', 'service_job_details', 'service_folding_details', 'service_hanging_details', 'service_washing_details', 'service_description', 'status'
    ];
}
