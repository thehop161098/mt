<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('candle_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->string('wallet', 10);
            $table->string('coin', 20);
            $table->tinyInteger('type')->unsigned();
            $table->double('amount')->unsigned();
            $table->double('profit')->nullable();
            $table->double('open')->unsigned()->nullable();
            $table->double('close')->unsigned()->nullable();
            $table->string('date');
            $table->timestamps();
            $table->index(['candle_id', 'user_id', 'date']);
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
}
