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
        Schema::create('store_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->unsigned();
            $table->string('locale')->index();

            $table->string('name', 100)->index();
            $table->string('descriptions')->nullable();
            $table->text('content')->nullable();
            $table->string('store_type','20')->nullable()->index();
            $table->timestamps();

            $table->unique(['store_id', 'locale']);
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');

        });

        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('descriptions');
            $table->dropColumn('content');
            $table->dropColumn('store_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_translations');
    }
};
