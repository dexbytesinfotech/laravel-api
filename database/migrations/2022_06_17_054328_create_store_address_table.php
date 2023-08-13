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
        Schema::create('store_address', function (Blueprint $table) {

            $table->id('id');
            $table->unsignedBigInteger('store_id')->index();
            $table->string('address_line_1', 100);
            $table->string('address_line_2', 100)->nullable();
            $table->string('address_line_3', 100)->nullable();
            $table->string('landmark', 100);
            $table->string('city', 50)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('country', 50);
            $table->string('zip_post_code', 11)->nullable();
            $table->boolean('is_primary')->default(0);
            $table->boolean('status')->default(1);
            $table->string('addrees_type', 50)->nullable();
            $table->decimal('latitude', 11, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('store_id', 'fk_store_address_stores')->references('id')->on('stores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_address');
    }
};
