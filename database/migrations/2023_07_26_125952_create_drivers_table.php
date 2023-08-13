<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('address', 100);
            $table->string('email', 255)->unique();
            $table->date('date_of_birth');
            $table->string('driver_license_number', 50);
            $table->text('driver_license_photo');
            $table->date('driver_license_expiry_date')->nullable();
            $table->string('phone_number', 20);
            $table->string('city', 50)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('ride_platform',100)->nullable();
            $table->enum('account_status', ['pending','approved','rejected'])->default('pending');
            $table->text('driver_photo');
            $table->tinyInteger('trial_account')->default(0);
            $table->string('stripe_customer_id', 30)->nullable();
            $table->string('damoov_token', 100)->nullable();
            $table->foreignId('user_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('drivers');
    }
};
