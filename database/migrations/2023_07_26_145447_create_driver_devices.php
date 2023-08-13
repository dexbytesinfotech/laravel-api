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
        Schema::create('driver_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->unsigned();
            $table->foreignId('vehicle_id')->unsigned();
            $table->foreignId('device_id')->unsigned();
            $table->enum('status',['installed','uninstalled','defected','onhold','cancel'])->default('uninstalled');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('driver_devices');
    }
};
