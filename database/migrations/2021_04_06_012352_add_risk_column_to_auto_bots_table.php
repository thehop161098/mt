<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRiskColumnToAutoBotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auto_bots', function (Blueprint $table) {
            $table->double('risk')->default(0)->after('commission_90');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auto_bots', function (Blueprint $table) {
            $table->dropColumn('risk');
        });
    }
}
