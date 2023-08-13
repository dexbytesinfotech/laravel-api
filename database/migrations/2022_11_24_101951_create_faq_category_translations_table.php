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
        Schema::create('faq_category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faq_category_id')->unsigned();
            $table->string('locale')->index();

            $table->string('name', 100)->nullable()->index();
            $table->timestamps();

            $table->unique(['faq_category_id', 'locale']);
            $table->foreign('faq_category_id')->references('id')->on('faq_category')->onDelete('cascade');

        });

        Schema::table('faq_category', function (Blueprint $table) {
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
        Schema::dropIfExists('faq_category_translations');
    }
};
