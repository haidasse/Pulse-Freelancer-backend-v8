<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_logs', function (Blueprint $table) {
            $table->id();
            $table->morphs('object');
            $table->string('action', '45');
            $table->json('attributes')->nullable();
            $table->json('original')->nullable();
            $table->json('changes')->nullable();
            $table->string('request_method');
            $table->string('request_path');
            // $table->foreignId('api_key_id')->nullable();
            $table->morphs('causer') ;
            $table->datetime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_logs');
    }
}