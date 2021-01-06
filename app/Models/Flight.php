<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    use HasFactory;

    protected $appends = [
        'departure_location',
        'arrival_location',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'departureAirport',
        'arrivalAirport'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    |
    */

    public function airline(){
        return $this->belongsTo(Airline::class, 'airline', 'code');
    }

    public function departureAirport(){
        return $this->belongsTo(Airport::class, 'departure_airport', 'code');
    }

    public function arrivalAirport(){
        return $this->belongsTo(Airport::class, 'arrival_airport', 'code');
    }

    public function trips(){
        return $this->belongsToMany(Trip::class)
            ->withPivot(['departure_date'])
            ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    |
    */
    public function getCodeAttribute(){
        return $this->airline.$this->number;
    }

    public function getInfoAttribute(){
        return "{$this->code} from {$this->departure_airport} to {$this->arrival_airport} departs at {$this->departure_time} ({$this->departureAirport->city}) and arrives at {$this->arrival_time} ({$this->arrivalAirport->city})";
    }

    public function getDepartureLocationAttribute(){
        return $this->departureAirport->location;
    }

    public function getArrivalLocationAttribute(){
        return $this->arrivalAirport->location;
    }
}
