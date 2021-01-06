<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAirportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airports', function (Blueprint $table) {
            $table->string('code', 10)->primary();
            $table->string('city_code', 10);
            $table->string('name');
            $table->string('city', 100);
            $table->string('country_code', 3);
            $table->string('region_code', 10);
            $table->decimal('latitude', 11, 6);
            $table->decimal('longitude', 11, 6);
            $table->string('timezone', 100);
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
        Schema::dropIfExists('airports');
    }
}
