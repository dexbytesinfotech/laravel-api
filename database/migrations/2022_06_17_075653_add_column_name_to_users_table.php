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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('country_code')->nullable()->after('phone');
            $table->dateTime('last_login')->nullable()->after('remember_token');
            $table->boolean('global_notifications')->after('last_login')->default(1);
            $table->char('default_lang', 11)->after('global_notifications')->default('en');
            $table->string('first_name', 100)->nullable()->change();
            $table->string('last_name', 100)->nullable()->change();
            $table->tinyInteger('status')->default(1);
            $table->mediumText('profile_photo')->nullable()->after('country_code');
            $table->string('user_name', 100)->nullable()->after('last_name');
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
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
