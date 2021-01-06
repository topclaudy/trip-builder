<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    return 'Welcome to the Trip Builder. The API documentation can be found <a href="https://documenter.getpostman.com/view/10051607/TVzNHet1">here</a>';
});
