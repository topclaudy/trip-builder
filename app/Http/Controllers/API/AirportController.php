<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Airport;
use Illuminate\Http\Request;

class AirportController extends Controller
{
    public function index(Request $request){
        return Airport::all();
    }
}
