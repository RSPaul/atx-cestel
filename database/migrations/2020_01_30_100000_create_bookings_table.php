<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_id');
            $table->string('service_type');
            $table->text('service_categories');
            $table->string('service_beds');
            $table->string('service_day');
            $table->string('service_time');
            $table->string('service_laundress');
            $table->string('service_package');
            $table->string('service_amount');
            $table->text('service_job_details');
            $table->text('service_folding_details');
            $table->text('service_hanging_details');
            $table->text('service_washing_details');
            $table->text('service_description');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
