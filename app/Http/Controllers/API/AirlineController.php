<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Airline;
use Illuminate\Http\Request;

class AirlineController extends Controller
{
    public function index(Request $request){
        return Airline::all();
    }
}
