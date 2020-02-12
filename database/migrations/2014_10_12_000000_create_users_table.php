<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('user_type');
            $table->text('address')->nullable();
            $table->string('city_state')->nullable();
            $table->string('zip');
            $table->string('phone');
            $table->string('services')->nullable();
            $table->string('heared')->nullable();
            $table->longText('language')->nullable();
            $table->longText('available')->nullable();
            $table->longText('more_questions')->nullable();
            $table->boolean('status');
            $table->string('customer_id')->nullable();
            $table->text('profile_pic')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
