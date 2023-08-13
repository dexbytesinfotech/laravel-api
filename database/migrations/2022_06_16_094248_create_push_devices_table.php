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
        Schema::create('push_devices', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->enum('device_type', ['android', 'ios'])->default('android');
            $table->string('app_version', 25)->nullable();
            $table->enum('app_name', ['provider', 'customer']);
            $table->string('device_uid', 50);
            $table->decimal('last_known_latitude', 11, 8)->nullable();
            $table->decimal('last_known_longitude', 11, 8)->nullable();
            $table->text('device_token_id');
            $table->string('device_name', 100)->nullable();
            $table->string('device_model', 100)->nullable();
            $table->string('device_version', 25)->nullable();
            $table->enum('push_badge', ['disabled', 'enabled'])->default('enabled');
            $table->enum('push_alert', ['disabled', 'enabled'])->default('enabled');
            $table->enum('push_sound', ['disabled', 'enabled'])->default('enabled');
            $table->enum('status', ['active', 'uninstalled', 'inactive'])->default('active');
            $table->integer('error_count')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('push_devices');
    }
};
