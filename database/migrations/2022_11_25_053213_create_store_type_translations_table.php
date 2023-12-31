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
        Schema::create('store_type_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_type_id')->unsigned();
            $table->string('locale')->index();

            $table->string('name', 100)->nullable()->index();
            $table->timestamps();

            $table->unique(['store_type_id', 'locale']);
            $table->foreign('store_type_id')->references('id')->on('store_types')->onDelete('cascade');

        });
        Schema::table('store_types', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_type_translations');
    }
};
