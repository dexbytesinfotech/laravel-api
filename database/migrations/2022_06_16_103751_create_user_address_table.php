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
        Schema::create('user_address', function (Blueprint $table) {
            
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('line_1_number_building', 50)->nullable();
            $table->string('line_2_number_street', 50)->nullable();
            $table->string('line_3_area_locality', 50)->nullable();
            $table->string('address', 255);
            $table->string('floor_number', 50)->nullable();
            $table->string('apartment_number', 50)->nullable();;
            $table->string('city')->nullable();
            $table->string('zip_postcode', 25)->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('phone', 45)->nullable();
            $table->string('addrees_type', 11)->nullable();
            $table->decimal('latitude', 11, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('additional_information', 255)->nullable();
            $table->tinyInteger('is_primary')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id', 'fk_address_users')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_address');
    }
};
