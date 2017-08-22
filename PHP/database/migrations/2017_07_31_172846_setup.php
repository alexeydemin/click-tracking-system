<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Setup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('folders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('name', 255);           
            $table->timestamps();
			
        });
		Schema::table('folders', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users');			
		});

		Schema::create('placements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('name', 255);           
            $table->timestamps();			
        });
		Schema::table('placements', function(Blueprint $table){
			$table->foreign('user_id')->references('id')->on('users');	
		});
		
		
		Schema::create('clicks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('folder_id')->unsigned();
			$table->integer('placement_id')->unsigned();
			$table->integer('placement_payout')->unsigned();
			$table->integer('folder_cost')->unsigned();
            $table->timestamps();			
        });
		Schema::table('clicks', function (Blueprint $table) {
			$table->foreign('folder_id')->references('id')->on('folders');
			$table->foreign('placement_id')->references('id')->on('placements');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clicks');
		Schema::dropIfExists('folders');
		Schema::dropIfExists('placements');
    }
}
