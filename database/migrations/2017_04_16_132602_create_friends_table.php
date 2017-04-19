<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFriendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friends', function (Blueprint $table) {
	        $table->increments('id');

	        $table->integer('user_id')->unsigned();
	        $table->foreign('user_id')->references('id')->on('users')
		        ->onUpdate('cascade')->onDelete('cascade');

	        $table->integer('user_friend_id')->unsigned();
	        $table->foreign('user_friend_id')->references('id')->on('users')
		        ->onUpdate('cascade')->onDelete('cascade');

	        $table->unique(['user_id', 'user_friend_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('friends');
    }
}
