<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdentityCardToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('verify')->after('phone')->default(false);
            $table->string('portrait')->after('phone')->nullable();
            $table->string('identity_card_before')->after('phone')->nullable();
            $table->string('identity_card_after')->after('phone')->nullable();
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
            $table->dropColumn('verify');
            $table->dropColumn('portrait');
            $table->dropColumn('identity_card_before');
            $table->dropColumn('identity_card_after');
        });
    }
}
