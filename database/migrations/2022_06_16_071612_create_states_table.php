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
        Schema::create('states', function (Blueprint $table) {
            
            $table->id();
            $table->string('name', 100);
            $table->unsignedBigInteger('country_id')->index();
            $table->char('abbreviation', 2)->nullable();
            $table->boolean('is_default')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('order')->default(0);            
            $table->timestamps();

            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
      
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('states');
    }
};
