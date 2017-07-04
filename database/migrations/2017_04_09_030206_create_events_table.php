<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
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

            $table->string('name');

            $table->integer('media_id')->unsigned()->nullable();
            $table->foreign('media_id')->references('id')->on('medias')
                ->onDelete('set null');

            $table->integer('owner')->unsigned();
            $table->foreign('owner')->references('id')->on('users')
	            ->onDelete('cascade');

            $table->enum('type', ['futebol', 'basketball', 'vólei', 'futsal']);
	        $table->date('date');
            $table->integer('minimumUsers');
            $table->integer('maximumUsers');
            $table->double('valueToBePaid');
            $table->boolean('isEventConfirmed')->default(false);
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
