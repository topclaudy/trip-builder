<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/')->name('api.home')->uses('TripController@index');

Route::group(['prefix' => 'airline'], function () {
    Route::get('/')
        ->name('api.airline.index')
        ->uses('AirlineController@index');
});

Route::group(['prefix' => 'airport'], function () {
    Route::get('/')
        ->name('api.airport.index')
        ->uses('AirportController@index');
});

Route::group(['prefix' => 'trip'], function () {
    Route::get('/')
        ->name('api.trip.index')
        ->uses('TripController@index');

    Route::post('/store')
        ->name('api.trip.store.one-way')
        ->uses('TripController@store');
});

Route::group(['prefix' => 'flight'], function () {
    Route::get('/')
        ->name('api.flight.index')
        ->uses('FlightController@index');
});
