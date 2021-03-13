<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('profile_id')->unsigned();

            $table->string('login')->unique();
            $table->string('email')->unique();
            $table->string('password');

            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();

            $table->foreign('profile_id')->references('id')->on('profiles');

//            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
