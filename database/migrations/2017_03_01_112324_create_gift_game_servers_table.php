<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGiftGameServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('core_gift_game_servers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id');
            $table->integer('game_id');
            $table->integer('server_id');
            $table->integer('gift_code_type');
            $table->string('gift_code');
            $table->integer('status')->default(0);
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
        Schema::table('core_gift_game_servers', function (Blueprint $table) {
            //
        });
    }
}
