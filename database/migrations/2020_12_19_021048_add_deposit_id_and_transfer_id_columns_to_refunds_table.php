<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDepositIdAndTransferIdColumnsToRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('refunds', function (Blueprint $table) {
            $table->bigInteger('deposit_id')->unsigned()->nullable()->after('date_expired');
            $table->bigInteger('transfer_id')->unsigned()->nullable()->after('deposit_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('refunds', function (Blueprint $table) {
            $table->dropColumn('deposit_id');
            $table->dropColumn('transfer_id');
        });
    }
}
