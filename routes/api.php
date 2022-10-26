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


Route::get('/v1/events/', 'API\V1\EventController@index');
Route::get('/v1/events/{id}', 'API\V1\EventController@show');
Route::post('/v1/events/store', 'API\V1\EventController@store');
Route::put('/v1/events/{id}', 'API\V1\EventController@update');
Route::delete('/v1/events/{id}', 'API\V1\EventController@destroy');


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});