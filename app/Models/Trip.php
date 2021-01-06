<?php

namespace App\Models;

use App\Enums\TripType;
use App\Services\TripService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Trip extends Model
{
    use HasFactory;

    protected $appends = [
        'steps',
        'price',
        'type',
        'departure_date_formatted'
    ];

    protected $visible = [
        'steps',
        'price',
        'type',
        'departure_date_formatted'
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('withDepartureDate', function (Builder $builder) {
            $builder->addSelect(['departure_date' => DB::table('flight_trip')
                ->select('departure_date')
                ->whereColumn('trip_id', 'trips.id')
                ->orderBy('departure_date')
                ->take(1)
            ])->withCasts(['departure_date' => 'date']);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    |
    */

    public function flights(){
        return $this->belongsToMany(Flight::class)
            ->withPivot(['departure_date'])
            ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    |
    */

    public function getPriceAttribute(){
        return $this->flights()->sum('price');
    }

    public function getOrderedFlightsAttribute(){
        return $this->flights->sort(function($f1, $f2){ //Order flights by departure date
            return $f1->pivot->departure_date > $f2->pivot->departure_date;
        });
    }

    public function getDepartureDateFormattedAttribute(){
        $firstFlight = $this->orderedFlights->first();

        if($firstFlight){
            return $firstFlight->pivot->departure_date;
        }

        return null;
    }

    public function getStepsAttribute(){
        $steps = [];

        foreach($this->orderedFlights as $f){
            $steps[] = [
                'departure_date' => $f->pivot->departure_date,
                'info'           => $f->info,
                'price'          => $f->price,
            ];
        }

        return $steps;
    }

    public function getTypeAttribute(){
        $tripService = resolve(TripService::class);
        $type = $tripService->getTypeFromFlights($this->flights->toArray());

        return TripType::getDescription($type);
    }
}
