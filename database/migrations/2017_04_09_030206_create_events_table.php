<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('owner')->unsigned();
            $table->foreign('owner')->references('id')->on('users')
	            ->onDelete('cascade');

            $table->enum('type', []);
	        $table->date('date');
            $table->integer('minimumUsers');
            $table->integer('maximumUsers');
            $table->double('valueToBePaid');
            $table->double('costByUser');
            $table->boolean('isEventConfirmed')->default(true);
            $table->string('address');
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
        Schema::dropIfExists('events');
    }
}
