<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuestPlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_players', function (Blueprint $table) {
        	$table->increments('id');

           $table->integer('game_id')->unsigned();
           $table->foreign('game_id')->references('id')->on('games')
	           ->onUpdate('cascade')->onDelete('cascade');

           $table->integer('user_id')->unsigned();
           $table->foreign('user_id')->references('id')->on('users')
	           ->onUpdate('cascade')->onDelete('cascade');

           $table->boolean('confirmParticipation')->default(false);

           $table->timestamps();

           $table->unique(['game_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guest_players');
    }
}
