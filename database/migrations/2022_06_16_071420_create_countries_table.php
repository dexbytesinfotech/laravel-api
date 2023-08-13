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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->char('country_ios_code', 2)->nullable();
            $table->string('country_code', 5)->nullable();
            $table->string('nationality', 100)->nullable();
            $table->tinyInteger('order')->default(0);
            $table->boolean('is_default')->default(false);;
            $table->tinyInteger('status')->default(0);
            
            
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
        Schema::dropIfExists('countries');
    }
};
