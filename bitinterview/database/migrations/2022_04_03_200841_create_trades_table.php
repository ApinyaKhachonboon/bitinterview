<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('market_post_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('currency_id')->unsigned();
            $table->bigInteger('to_user_id')->unsigned()->nullable();
            $table->bigInteger('from_user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('market_post_id')->references('id')->on('market_posts');
            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->enum('action', ['buy', 'sell', 'transfer', 'receive']);
            $table->string('outer_name', '255')->nullable();
            $table->double('amount', 15, 8)->unsigned();
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
        Schema::dropIfExists('trades');
    }
}
