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
        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('assigned_to_user_id')->nullable();

            $table->foreign('category_id')->references('id')->on('ticket_categories')->onUpdate('set null')->onDelete('set null');
            $table->foreign('user_id', 'tickets_user_id_fk_583768')->references('id')->on('users')->onUpdate('set null')->onDelete('set null');
            $table->foreign('assigned_to_user_id', 'tickets_assigned_to_user_fk_583768')->references('id')->on('users')->onUpdate('set null')->onDelete('set null');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            //
        });
    }
};
