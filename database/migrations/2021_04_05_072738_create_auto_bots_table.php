<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutoBotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auto_bots', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->integer('price');
            $table->double('commission_f1')->default(0);
            $table->double('commission_7');
            $table->double('commission_21');
            $table->double('commission_30');
            $table->double('commission_90');
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
        Schema::dropIfExists('auto_bots');
    }
}
