<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDayBalancesTable extends Migration
{
    public function up()
    {
        Schema::create('day_balances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('transaction_type');
            $table->date('date');
            $table->bigInteger('amount');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('day_balances');
    }
}
