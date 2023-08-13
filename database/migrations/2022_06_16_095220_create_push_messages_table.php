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
        Schema::create('push_messages', function (Blueprint $table) {
            $table->id();
            $table->enum('target_devices', ['all', 'android', 'ios'])->default('all');
            $table->text('title')->nullable();
            $table->text('text')->nullable();
            $table->boolean('with_image')->nullable();
            $table->string('custom_image', 255)->nullable();
            $table->string('action_value', 515)->nullable();
            $table->decimal('latitude', 11, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('radius', 7, 2)->nullable();
            $table->enum('app_name', ['all', 'provider', 'customer'])->default('all');
            $table->enum('send_to', ['all', 'specific_users'])->default('all');
            $table->dateTime('send_at')->nullable();
            $table->dateTime('send_until')->nullable();
            $table->dateTime('delivered_at')->nullable();
            $table->boolean('is_silent')->nullable();
            $table->char('status', 11)->nullable()->default('queue');
            $table->text('error_text')->nullable();
            $table->boolean('should_visible')->default(1);

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
        Schema::dropIfExists('push_messages');
    }
};
