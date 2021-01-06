<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function index(Request $request){
        return Flight::all();
    }
}
