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
        Schema::create('slider_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('slider_id')->index();
            $table->string('title', 100)->nullable();
            $table->text('descriptions')->nullable();
            $table->text('action_values')->nullable();
            $table->char('order_number', 1)->default(0);
            $table->text('image');
            $table->char('status', 1)->nullable()->default(0);
            $table->dateTime('start_date_time')->nullable();
            $table->dateTime('end_date_time')->nullable();

            $table->timestamps();
            $table->softDeletes();

            //$table->foreign('original_media_id', 'fk_slider_images_store_media')->references('id')->on('media')->onDelete('cascade');
            $table->foreign('slider_id', 'fk_slider_images_sliders')->references('id')->on('sliders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slider_images');
    }
};
