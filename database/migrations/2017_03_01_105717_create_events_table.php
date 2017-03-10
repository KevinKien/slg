<?php

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
        Schema::table('core_events', function (Blueprint $table) {
            //
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->string('image');
            $table->string('time_min');
            $table->string('time_max');
            $table->integer('number');
            $table->integer('giftcode_type');
            $table->integer('status');
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
        Schema::table('core_events', function (Blueprint $table) {
            //
        });
    }
}
