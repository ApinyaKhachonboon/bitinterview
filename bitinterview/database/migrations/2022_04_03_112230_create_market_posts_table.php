<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market_posts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('currency_id')->unsigned();
            $table->bigInteger('fiat_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->foreign('fiat_id')->references('id')->on('currencies');
            $table->enum('action', ['buy', 'sell']);
            $table->double('amount', 15, 8)->unsigned();
            $table->double('price', 15, 8)->unsigned();
            $table->double('min', 15, 8)->unsigned();
            $table->double('max', 15, 8)->unsigned();
            $table->enum('status', ['open', 'close'])->default('open');
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
        Schema::dropIfExists('market_posts');
    }
}
