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
        Schema::create('orders', function (Blueprint $table) {
            $table->id("order_id");
            $table->integer('order_total_price');
            $table->string('order_status');
            $table->string('order_note', 200);
            $table->string('order_time_order', 200);
            $table->string('order_time_receive', 200);
            $table->foreignId('address_id');
            $table->foreignId('payment_id');
            $table->foreignId('user_id');
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
        Schema::dropIfExists('orders');
    }
};
