<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradesHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trades_history', function (Blueprint $table) {
            $table->id();
            //$table->integer('user_id');
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');
            
            $table->integer('owner_id');
            $table->json('bid');
            $table->json('trades');
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
        Schema::dropIfExists('trades_history');
    }
}
