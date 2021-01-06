<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    use HasFactory;

    protected $primaryKey = 'code';
    public $incrementing = false;

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    |
    */

    public function departureFlights(){
        return $this->hasMany(Flight::class, 'departure_airport', 'code');
    }

    public function arrivalFlights(){
        return $this->hasMany(Flight::class, 'arrival_airport', 'code');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    |
    */
    public function getLocationAttribute(){
        return [
            'latitude'  => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    |
    */
    public function scopeWithinDistanceTo($query, array $location, float $distance = 50)
    {
        $query->whereRaw('DISTANCE_POINTS_LAT_LON(
                latitude, longitude, ?, ?
            ) <= ?', [$location['latitude'], $location['longitude'], $distance]
        );
    }

    public function scopeOrderByDistanceTo($query, array $location, string $direction = 'asc')
    {
        $direction = strtolower($direction) === 'asc' ? 'asc' : 'desc';

        $query->orderByRaw('DISTANCE_POINTS_LAT_LON(
                latitude, longitude, ?, ?
            ) '.$direction, [$location['latitude'], $location['longitude']]
        );
    }
}
