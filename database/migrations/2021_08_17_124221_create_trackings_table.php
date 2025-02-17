<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trackings', function (Blueprint $table) {
            $table->id();
            $table->string('HostName')->nullable();
            $table->string('ip')->index()->nullable();
            $table->string('port')->nullable();
            $table->integer('GameID')->nullable();
            $table->string('game')->nullable();
            $table->string('Map')->nullable();
            $table->string('os')->nullable();
            $table->integer('MaxPlayers')->nullable();
            $table->integer('Players')->nullable();
            $table->integer('Bots')->nullable();
            $table->integer('AppID')->index()->nullable();
            $table->integer('Version')->nullable();
            $table->bigInteger('SteamID')->index()->nullable();
            $table->longText('GameTags')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trackings');
    }
}
