<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TweetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tweets', function(Blueprint $table){
            $table->increments('id');
            $table->string('id_tweet');
            $table->string('username');
            $table->string('tweet');
            $table->string('date_tweet');
            $table->datetime('date_get_tweet');
            $table->rememberToken();
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
        Schema::drop('tweets');
    }
}
