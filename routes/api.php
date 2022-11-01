<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v1'], function () {
    /* --------------------------- Login and Register --------------------------- */
    Route::post('register', 'API\V1\AuthController@register');
    Route::post('login', 'API\V1\AuthController@login');

    /* ----------------------------- Get Data events ---------------------------- */
    Route::get('events', 'API\V1\EventController@index');
    Route::get('events/{id}', 'API\V1\EventController@show');

    /* ------------------------- Middleware after login ------------------------- */
    Route::middleware('auth:api')->group(function () {
        /* --------------------------------- logout --------------------------------- */
        route::post('logout', 'API\V1\AuthController@logout');
        /* -------------------------------- Data User ------------------------------- */
        Route::get('users/{id}', 'API\V1\UserController@show');
        Route::put('users/{id}', 'API\V1\UserController@update');


        /* ------------------------------- Role admin ------------------------------- */
        Route::middleware('admin')->group(function () {
            /* ---------------------------- Management events --------------------------- */
            Route::post('events/store', 'API\V1\EventController@store');
            Route::put('events/{id}', 'API\V1\EventController@update');
            Route::delete('events/{id}', 'API\V1\EventController@destroy');

            /* -------------------------------- Management user ------------------------------- */
            Route::get('users', 'API\V1\UserController@index');
            Route::delete('users/{id}', 'API\V1\UserController@destroy');
        });
    });
});