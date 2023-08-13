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
        Schema::create('agency_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->unsigned();
            $table->foreignId('device_id')->unsigned();
            $table->enum('status', ['installed', 'uninstalled','assigned','cancel', 'onhold', 'defected'])->default('uninstalled');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('agency_id')->references('id')->on('agencies')->onDelete('cascade');
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
        Schema::dropIfExists('agency_devices');
    }
};
