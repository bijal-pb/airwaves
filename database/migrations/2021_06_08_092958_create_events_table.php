<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->bigInteger('group_id')->unsigned()->nullable(); 
            $table->foreign('group_id')->references('id')->on('groups');
            $table->string('name');
            $table->text('description')->nullable();;
            $table->text('location')->nullable();
            $table->text('lat')->nullable();
            $table->text('lang')->nullable();
            $table->time('event_time')->nullable();
            $table->date('event_date')->nullable();	
            $table->text('event_photo')->nullable();
            $table->softDeletes();
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
