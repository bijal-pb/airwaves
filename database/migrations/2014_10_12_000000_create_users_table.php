<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('current_team_id')->nullable();
            $table->text('profile_photo_path')->nullable();
            $table->tinyInteger('gender')->comment('1-Male | 2 - Female');
            $table->text('bio')->nullable();
            $table->string('phone')->nullable();
            $table->string('device_type')->nullable();
            $table->string('device_id')->nullable();
            $table->text('device_token')->nullable();
            $table->string('lat')->nullable();
            $table->text('lat_address')->nullable();
            $table->string('lang')->nullable();
            $table->text('lang_address')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1-Active | 2 - Inactive');
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
        Schema::dropIfExists('users');
    }
}
