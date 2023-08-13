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
        Schema::create('push_delivered_message', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_id');
            $table->unsignedBigInteger('user_id')->index();
            $table->text('device_uid')->nullable();
            $table->enum('device_type', ['android', 'ios'])->nullable();
            $table->integer('message_id')->nullable();
            $table->string('status')->nullable();
            $table->text('error_msg')->nullable();
            $table->string('is_read')->default('no');
            $table->string('is_displayed')->default('no');
            $table->dateTime('delivered_at')->nullable();
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('device_id', 'fk_push_delivered_message')->references('id')->on('push_devices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('push_delivered_message');
    }
};
