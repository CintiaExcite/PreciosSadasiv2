<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('development_id');
            $table->string('code', 255);
            $table->string('product', 255);
            $table->string('image_sys', 255)->nullable();
            $table->integer('comming_soon');
            $table->string('release_date');
            $table->integer('available');
            $table->integer('status');
            $table->string('salesup_tkproducto', 255)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('development_id')->references('id')->on('developments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
