<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id1')->unsigned(); 
            $table->foreign('user_id1')->references('id')->on('users'); 
            $table->bigInteger('user_id2')->unsigned(); 
            $table->foreign('user_id2')->references('id')->on('users');
            $table->tinyInteger('status')->default(1)->comment('1-pending | 2-accept | 3-decline ');
            $table->bigInteger('action_user_id');
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
        Schema::dropIfExists('match_users');
    }
}
