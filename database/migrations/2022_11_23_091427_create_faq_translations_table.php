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
        Schema::create('faq_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faq_id')->unsigned();
            $table->string('locale')->index();

            $table->string('title', 100)->nullable()->index();
            $table->text('descriptions')->nullable();;
            $table->timestamps();

            $table->unique(['faq_id', 'locale']);
            $table->foreign('faq_id')->references('id')->on('faq')->onDelete('cascade');


        });

        Schema::table('faq', function (Blueprint $table) {
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
        Schema::dropIfExists('faq_translations');
    }
};
