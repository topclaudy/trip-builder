<?php

namespace App\Providers;

use App\Enums\TripType;
use App\Exceptions\FlightNotFoundException;
use App\Models\Flight;
use App\Services\TripService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(TripService $tripService)
    {
        Validator::extend('flight_exists', function($attr, $value, $params) {
            return Flight::find($value);
        });

        Validator::extend('flight_date_valid', function($attr, $value, $params) {
            $date = Carbon::createFromFormat('Y-m-d', $value);

            return $date->gte(now());
        });

        Validator::extend('trip_departure_date_valid', function($attr, $value, $params) use ($tripService) {
            $flights = $value;

            $tripService->orderFlightsDefinition($flights);

            $date = Carbon::createFromFormat('Y-m-d', $value[0]['departure_date']);

            return $date->gte(now()) && $date->lte(now()->addDays(365));
        });

        Validator::extend('trip_supported', function($attr, $value, $params)  use ($tripService)  {
            $flights = $value;

            $tripService->orderFlightsDefinition($flights);

            try {
                return $tripService->getTypeFromFlights($flights) != TripType::Unsupported;
            } catch(FlightNotFoundException $e){
                return false;
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
