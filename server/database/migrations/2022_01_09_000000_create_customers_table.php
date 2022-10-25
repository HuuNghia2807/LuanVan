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
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email', 100)->unique();
            $table->string('password', 100);
            $table->integer('user_status_id')->unsigned();
            $table->foreign('user_status_id')->references('id')->on('users_status');
            $table->integer('user_detail_id')->unsigned();
            $table->foreign('user_detail_id')->references('id')->on('users_detail');
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
        Schema::dropIfExists('customers');
    }
};
