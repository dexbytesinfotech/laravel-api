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
        Schema::create('store_metadata', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('store_id');
            $table->string('key', 100)->index();
            $table->text('value')->nullable();

            $table->timestamps();

            $table->foreign('store_id', 'fk_store_metadata_stores')->references('id')->on('stores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_metadata');
    }
};
