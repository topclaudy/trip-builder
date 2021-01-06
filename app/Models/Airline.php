<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airline extends Model
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

    public function flights(){
        return $this->hasMany(Flight::class, 'airline', 'code');
    }
}
