<?php

namespace Database\Seeders;

use App\Models\Flight;
use App\Models\Trip;
use Illuminate\Database\Seeder;

class TripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tripsData = [
            [
                1 => ['departure_date' => '2021-06-01']
            ],

            [
                2 => ['departure_date' => '2021-07-01']
            ],

            [
                2 => ['departure_date' => '2021-07-04']
            ],

            [
                2 => ['departure_date' => '2021-07-06']
            ],

            [
                3 => ['departure_date' => '2021-07-02']
            ],

            [
                1 => ['departure_date' => '2021-07-15'],
                2 => ['departure_date' => '2021-07-17']
            ],

            [
                6 => ['departure_date' => '2021-08-02'],
                4 => ['departure_date' => '2021-08-02']
            ],

            [
                1 => ['departure_date' => '2021-09-02'],
                3 => ['departure_date' => '2021-09-15'],
                4 => ['departure_date' => '2021-09-20']
            ],

            [
                1 => ['departure_date' => '2021-10-15'],
                2 => ['departure_date' => '2021-10-17']
            ],

            [
                3 => ['departure_date' => '2021-10-20']
            ],

            [
                6 => ['departure_date' => '2021-10-22']
            ],

            [
                5 => ['departure_date' => '2021-10-24']
            ],

            [
                5 => ['departure_date' => '2021-11-24']
            ],

            [
                1 => ['departure_date' => '2021-11-29']
            ],

            [
                4 => ['departure_date' => '2021-12-03']
            ],

            [
                3 => ['departure_date' => '2021-12-04']
            ],

            [
                2 => ['departure_date' => '2021-12-08']
            ],

            [
                1 => ['departure_date' => '2021-12-10'],
                2 => ['departure_date' => '2021-12-12']
            ],

            [
                6 => ['departure_date' => '2021-12-14'],
                4 => ['departure_date' => '2021-12-16']
            ],

            [
                6 => ['departure_date' => '2021-12-18']
            ],

            [
                2 => ['departure_date' => '2021-12-20']
            ],

            [
                1 => ['departure_date' => '2021-12-22'],
                3 => ['departure_date' => '2021-12-23'],
                4 => ['departure_date' => '2021-12-24']
            ],
        ];

        foreach($tripsData as $data){
            $trip = new Trip();
            $trip->user_id = 1;
            $trip->save();

            $trip->flights()->sync($data);
        }
    }
}
