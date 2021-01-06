<?php

namespace Database\Seeders;

use App\Models\Flight;
use Illuminate\Database\Seeder;

class FlightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Flight::create([ //Montreal to Vancouver
            'id'                => 1,
            'airline'           => 'AC',
            'number'            => 301,
            'departure_airport' => 'YUL',
            'departure_time'    => '07:35',
            'arrival_airport'   => 'YVR',
            'arrival_time'      => '10:05',
            'price'             => 273.23,
        ]);

        Flight::create([ //Vancouver to Montreal
            'id'                => 2,
            'airline'           => 'AC',
            'number'            => 302,
            'departure_airport' => 'YVR',
            'departure_time'    => '11:30',
            'arrival_airport'   => 'YUL',
            'arrival_time'      => '19:11',
            'price'             => 220.63,
        ]);

        Flight::create([ //Vancouver to Ottawa
            'id'                => 3,
            'airline'           => 'AC',
            'number'            => 303,
            'departure_airport' => 'YVR',
            'departure_time'    => '11:30',
            'arrival_airport'   => 'YOW',
            'arrival_time'      => '19:11',
            'price'             => 220.63,
        ]);

        Flight::create([ //Ottawa to Calgary
            'id'                => 4,
            'airline'           => 'TS',
            'number'            => 402,
            'departure_airport' => 'YOW',
            'departure_time'    => '11:30',
            'arrival_airport'   => 'YYC',
            'arrival_time'      => '09:10',
            'price'             => 240.90,
        ]);

        Flight::create([ //Calgary to Ottawa
            'id'                => 5,
            'airline'           => 'TS',
            'number'            => 403,
            'departure_airport' => 'YYC',
            'departure_time'    => '11:30',
            'arrival_airport'   => 'YOW',
            'arrival_time'      => '20:15',
            'price'             => 250.43,
        ]);

        Flight::create([ //Calgary to Montreal
            'id'                => 6,
            'airline'           => 'TS',
            'number'            => 404,
            'departure_airport' => 'YYC',
            'departure_time'    => '11:30',
            'arrival_airport'   => 'YUL',
            'arrival_time'      => '20:15',
            'price'             => 300.43,
        ]);
    }
}
