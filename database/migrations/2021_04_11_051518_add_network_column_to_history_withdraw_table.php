<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNetworkColumnToHistoryWithdrawTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('history_withdraw', function (Blueprint $table) {
            $table->string('network')->after('coin')->default('ERC20');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('history_withdraw', function (Blueprint $table) {
            $table->dropColumn('network');
        });
    }
}
