<?php

namespace App\Services;

use App\Enums\TripType;
use App\Exceptions\AirportNotFoundException;
use App\Exceptions\FlightNotFoundException;
use App\Models\Airport;
use App\Models\Flight;

class TripService
{
    public function getNearestAirPortWithinDistance($location, $vicinityTreshold = 50 /* In km */){
        return Airport::withinDistanceTo($location, $vicinityTreshold)
            ->orderByDistanceTo($location)
            ->first();
    }

    public function extractFlightsDefinitionFromPayload($flightsPayload){
        $flights = array_map(function($flight){
            $departureAirport = $this->getNearestAirPortWithinDistance($flight['departure_location']);
            if(!$departureAirport){
                throw new AirportNotFoundException("No departure airport found around the location ({$flight['departure_location']['latitude']}, {$flight['departure_location']['longitude']})");
            }

            $arrivalAirport = $this->getNearestAirPortWithinDistance($flight['arrival_location']);
            if(!$arrivalAirport){
                throw new AirportNotFoundException("No arrival airport found around the location ({$flight['arrival_location']['latitude']}, {$flight['arrival_location']['longitude']})");
            }

            $flightModel = Flight::where('departure_airport', $departureAirport->code)
                ->where('arrival_airport', $arrivalAirport->code)
                ->first();

            if(!$flightModel){
                throw new FlightNotFoundException("No flight found from {$departureAirport->code} to {$arrivalAirport->code}!");
            }

            return ['id' => $flightModel->id, 'departure_date' => $flight['departure_date']];
        }, $flightsPayload['flights']);

        return ['flights' => $flights];
    }

    public function orderFlightsDefinition(array &$flights)
    {
        usort($flights, function($f1, $f2){
            return $f1['departure_date'] > $f2['departure_date'];
        });
    }

    public function getTypeFromFlights(array $flights)
    {
        if (count($flights) == 0) {
            return TripType::Unsupported;
        } elseif (count($flights) == 1) {
            return TripType::OneWay;
        } elseif (count($flights) == 2) {
            $flight1 = Flight::with(['departureAirport', 'arrivalAirport'])->find($flights[0]['id']);
            if(!$flight1){
                throw new FlightNotFoundException('Flight '.$flights[0]['id'].' cannot be found!');
            }

            $flight2 = Flight::with(['departureAirport', 'arrivalAirport'])->find($flights[1]['id']);
            if(!$flight2){
                throw new FlightNotFoundException('Flight '.$flights[1]['id'].' cannot be found!');
            }

            if ($flight1->departureAirport->city != $flight2->arrivalAirport->city) {
                return TripType::Unsupported;
            } elseif ($flight1->arrivalAirport->city == $flight2->departureAirport->city) {
                return TripType::RoundTrip;
            } else {
                return TripType::OpenJaw;
            }
        } elseif (count($flights) <= 5) {
            for($i = 0; $i < count($flights) - 1; $i++){
                $flight1 = Flight::with(['departureAirport', 'arrivalAirport'])->find($flights[$i]['id']);
                if(!$flight1){
                    throw new FlightNotFoundException('Flight '.$flights[$i]['id'].' cannot be found!');
                }

                $flight2 = Flight::with(['departureAirport', 'arrivalAirport'])->find($flights[$i + 1]['id']);
                if(!$flight2){
                    throw new FlightNotFoundException('Flight '.$flights[$i + 1]['id'].' cannot be found!');
                }

                if ($flight1->arrivalAirport->city != $flight2->departureAirport->city) {
                    return TripType::Unsupported;
                }
            }

            return TripType::MultiCity;
        } else {
            return TripType::Unsupported;
        }
    }
}
