<?php

namespace Database\Seeders;

use App\Models\Airport;
use Illuminate\Database\Seeder;

class AirportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Airport::create([
            'code'         => 'YUL',
            'city_code'    => 'YMQ',
            'name'         => 'Pierre Elliott Trudeau International',
            'city'         => 'Montreal',
            'country_code' => 'CA',
            'region_code'  => 'QC',
            'latitude'     => 45.457714,
            'longitude'    => -73.749908,
            'timezone'     => 'America/Montreal',
        ]);

        Airport::create([
            'code'         => 'YVR',
            'city_code'    => 'YVR',
            'name'         => 'Vancouver International',
            'city'         => 'Vancouver',
            'country_code' => 'CA',
            'region_code'  => 'BC',
            'latitude'     => 49.194698,
            'longitude'    => -123.179192,
            'timezone'     => 'America/Vancouver',
        ]);

        Airport::create([
            'code'         => 'YOW',
            'city_code'    => 'YOW',
            'name'         => 'Macdonald-Cartier International Airport',
            'city'         => 'Ottawa',
            'country_code' => 'CA',
            'region_code'  => 'ON',
            'latitude'     => 45.320165386,
            'longitude'    => -75.668163994,
            'timezone'     => 'America/Toronto',
        ]);

        Airport::create([
            'code'         => 'YYC',
            'city_code'    => 'YYC',
            'name'         => 'Calgary International Airport',
            'city'         => 'Calgary',
            'country_code' => 'CA',
            'region_code'  => 'AB',
            'latitude'     => 51.131470,
            'longitude'    => -114.010559,
            'timezone'     => 'America/Edmonton',
        ]);

        Airport::create([
            'code'         => 'YWG',
            'city_code'    => 'YWG',
            'name'         => 'Winnipeg International Airport',
            'city'         => 'Winnipeg',
            'country_code' => 'CA',
            'region_code'  => 'MB',
            'latitude'     => 49.905996,
            'longitude'    => -97.237332,
            'timezone'     => 'America/Winnipeg'
        ]);
    }
}
