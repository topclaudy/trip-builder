<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddDistancePointsLatLonFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('

            DROP FUNCTION IF EXISTS DISTANCE_POINTS_LAT_LON;
            CREATE FUNCTION DISTANCE_POINTS_LAT_LON(
                lat1 REAL,
                lng1 REAL,
                lat2 REAL,
                lng2 REAL
            ) RETURNS REAL NO SQL RETURN ATAN2(
                SQRT(
                    POW(COS(RADIANS(lat2)) * SIN(RADIANS(lng1 - lng2)), 2) +
                    POW(COS(RADIANS(lat1)) * SIN(RADIANS(lat2)) - SIN(RADIANS(lat1)) * COS(RADIANS(lat2)) * COS(RADIANS(lng1 - lng2)), 2)
                ),
                (
                    SIN(RADIANS(lat1)) * SIN(RADIANS(lat2)) + COS(RADIANS(lat1)) * COS(RADIANS(lat2)) * COS(RADIANS(lng1 - lng2))
                )
            ) * 6372.795;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
