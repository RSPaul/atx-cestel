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
            $table->text('service_address');
            $table->text('service_categories');
            $table->text('service_quantity');
            $table->string('service_day');
            $table->string('service_time');
            $table->string('service_laundress');
            $table->string('service_package');
            $table->string('service_amount');
            $table->text('service_job_details')->nullable();
            $table->text('service_folding_details')->nullable();
            $table->text('service_hanging_details')->nullable();
            $table->text('service_washing_details')->nullable();
            $table->text('service_description')->nullable();
            $table->text('transfer_group')->nullable();
            $table->string('status');
            $table->text('payment_request')->nullable();
            $table->text('service_reminder_sent')->nullable();
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
