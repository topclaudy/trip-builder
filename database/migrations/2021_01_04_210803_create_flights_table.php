<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->string('airline', 10);
            $table->foreign('airline')->references('code')->on('airlines')->onDelete('cascade');

            $table->integer('number');

            $table->string('departure_airport', 10);
            $table->foreign('departure_airport')->references('code')->on('airports')->onDelete('cascade');

            $table->time('departure_time');

            $table->string('arrival_airport', 10);
            $table->foreign('arrival_airport')->references('code')->on('airports')->onDelete('cascade');

            $table->time('arrival_time');
            $table->decimal('price', 11);
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
        Schema::dropIfExists('flights');
    }
}
