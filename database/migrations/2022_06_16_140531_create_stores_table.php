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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('descriptions')->nullable();
            $table->string('phone', 13)->index();
            $table->string('email')->nullable();
            $table->text('content')->nullable();
            $table->integer('country_code');
            $table->string('store_type','20')->nullable();
            $table->integer('number_of_branch')->default(1)->nullable();
            $table->string('logo_path')->nullable();
            $table->string('background_image_path')->nullable();
            $table->boolean('is_primary')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->boolean('is_open')->default(0);
            $table->string('application_status')->default('waiting');
            $table->char('is_searchable', 1)->default(0)->index();
            $table->char('is_features', 1)->default(0);
            $table->char('order_number', 1)->default(0);
            $table->string('commission_type', 50)->default('percentage');
            $table->string('commission_value', 50)->nullable()->default(0);
            $table->tinyInteger('is_global_commission')->default(1);
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
    }
};
