<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWalletColumnToHistoryWithdrawTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('history_withdraw', function (Blueprint $table) {
            $table->string('wallet', 10)->default('main')->after('user_id');
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
            $table->dropColumn('wallet');
        });
    }
}
