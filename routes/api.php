<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v1'], function () {
    // Login and Register
    Route::post('register', 'API\V1\AuthController@register');
    Route::post('login', 'API\V1\AuthController@login');

    // Get Data events
    Route::get('events', 'API\V1\EventController@index');
    Route::get('events/{id}', 'API\V1\EventController@show');

    // Get Data events
    Route::middleware('auth:api')->group(function () {
        route::post('logout', 'API\V1\AuthController@logout');

        Route::middleware('admin')->group(function () {
            Route::post('events/store', 'API\V1\EventController@store');
            Route::put('events/{id}', 'API\V1\EventController@update');
            Route::delete('events/{id}', 'API\V1\EventController@destroy');
        });
    });
});