<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawsTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraws_token', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->double('type');
            $table->string('coin');
            $table->double('amount');
            $table->double('status');
            $table->string('txHash')->nullable();
            $table->double('fee');
            $table->string('from_wallet_address');
            $table->string('wallet_address');
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
        Schema::dropIfExists('withdraws_token');
    }
}
