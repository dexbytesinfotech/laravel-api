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
        Schema::create('slider_image_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('slider_image_id')->unsigned();
            $table->string('locale')->index();

            $table->string('title', 100)->index();
            $table->string('descriptions')->nullable();
            $table->timestamps();

            $table->unique(['slider_image_id', 'locale']);
            $table->foreign('slider_image_id')->references('id')->on('slider_images')->onDelete('cascade');
        });

        Schema::table('slider_images', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('descriptions'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slider_image_translations');
    }
};
