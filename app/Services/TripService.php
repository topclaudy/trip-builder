<?php

namespace App\Services;

use App\Enums\TripType;
use App\Exceptions\AirportNotFoundException;
use App\Exceptions\FlightNotFoundException;
use App\Models\Airport;
use App\Models\Flight;

class TripService
{
    /**
     * Get the nearest airport within distance from a location
     *
     * @param $location array location coordinate, ex: ['latitude' => 43.5, 'longitude' => -73.3]
     * @param $vicinitySearchRadius float the search radius in km
     * @return Airport|null
     */
    public function getNearestAirPortWithinDistance($location, $vicinitySearchRadius = 50){
        return Airport::withinDistanceTo($location, $vicinitySearchRadius) //The `withinDistanceTo` is a local scope
            ->orderByDistanceTo($location) //The `orderByDistanceTo` is a local scope
            ->first();
    }

    /**
     * Get airport from a location
     *
     * @param $location array|string location coordinate or city name, ex: ['latitude' => 43.5, 'longitude' => -73.3] or 'Montreal'
     * @param $vicinitySearchRadius float the search radius in km (not used if location is a city name)
     * @return Airport|null
     */
    public function getAirPortForLocation($location, $vicinitySearchRadius = 50){
        if(is_array($location) && isset($location['latitude']) && isset($location['longitude'])) {
            return $this->getNearestAirPortWithinDistance($location, $vicinitySearchRadius);
        } elseif(is_string($location)){
            return Airport::whereCity($location)->first();
        }

        return null;
    }

    /**
     * Structure the payload in a format that is easier to process (We call this the `flights definition` of the trip).
     *
     * @return array An array of flight IDs mapped to departure dates
     */
    public function extractFlightsDefinitionFromPayload($flightsPayload){
        $flights = array_map(function($flight){
            $departureAirport = $this->getAirPortForLocation($flight['departure_location']);
            if(!$departureAirport){
                throw new AirportNotFoundException("No departure airport found around the location ({$flight['departure_location']['latitude']}, {$flight['departure_location']['longitude']})");
            }

            $arrivalAirport = $this->getAirPortForLocation($flight['arrival_location']);
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

    /**
     * Order the flights definition by departure dates in ascendant order
     *
     * @param $flights array The flights to order
     */
    public function orderFlightsDefinition(array &$flights)
    {
        usort($flights, function($f1, $f2){
            return $f1['departure_date'] > $f2['departure_date'];
        });
    }

    /**
     * Get the type of the trip from the flights definition
     *
     * @param $flights array The flights definition
     */
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
