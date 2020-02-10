<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('event', 255)->nullable();
            $table->string('action', 255)->nullable();
            $table->unsignedInteger('state_id')->nullable();
            $table->unsignedInteger('development_id')->nullable();
            $table->unsignedInteger('product_id')->nullable();
            $table->unsignedInteger('userc_id')->nullable();
            $table->string('description', 255)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('state_id')->references('id')->on('states');
            $table->foreign('development_id')->references('id')->on('developments');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('userc_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
}
