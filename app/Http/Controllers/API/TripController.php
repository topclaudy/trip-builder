<?php

namespace App\Http\Controllers\API;

use App\Exceptions\AirportNotFoundException;
use App\Exceptions\FlightNotFoundException;
use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Services\TripService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TripController extends Controller
{
    private $tripService;

    public function __construct(TripService $tripService)
    {
        $this->tripService = $tripService;
    }

    public function index(Request $request){
        $query = Trip::query();

        if(strtolower($request->sort) === 'desc'){
            $query = $query->orderByDesc('departure_date');
        } else {
            $query = $query->orderBy('departure_date');
        }

        return $query->with(['flights'])->paginate($request->per_page ?: 10);
    }

    public function store(Request $request)
    {
        try {
            $payload = $request->only(['flights']);
            $payload = $this->tripService->extractFlightsDefinitionFromPayload($payload);

            $validator = Validator::make($payload, [
                'flights.*.id'             => 'flight_exists',
                'flights.*.departure_date' => 'date_format:Y-m-d|flight_date_valid',
                'flights'                  => 'required|trip_departure_date_valid|trip_supported',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->all(), 422);
            }

            $this->tripService->orderFlightsDefinition($payload['flights']);

            return DB::transaction(function () use ($payload) {
                $trip = new Trip();
                $trip->user_id = 1; //We always assumes it's user 1
                $trip->save();

                foreach($payload['flights'] as $flight) {
                    $trip->flights()->attach($flight['id'], [
                        'departure_date' => $flight['departure_date']
                    ]);
                }

                return $trip;
            });
        } catch(AirportNotFoundException $e){
            return response()->json([$e->getMessage()], 422);
        } catch(FlightNotFoundException $e){
            return response()->json([$e->getMessage()], 422);
        }
    }
}
