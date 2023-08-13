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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->unsigned();
            $table->double('total_amount');
            $table->string('stripe_transaction_id', 100);
            $table->double('driving_mileage');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->enum('payment_status', ['failed','success','pending','onhold','inprogress','cancel'])->default('pending');
            $table->string('frequency', 10);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
