<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AlterTransactionsTableModifyDate extends Migration
{

    public function up()
    {
        DB::raw("ALTER TABLE transactions MODIFY date datetime(3) NOT NULL;");
    }


    public function down()
    {
        DB::raw("ALTER TABLE transactions MODIFY date datetime(0) NOT NULL;");
    }
}
