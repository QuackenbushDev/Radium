<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'DashboardController@index');

Route::group(['prefix' => 'user', 'as' => 'user::'], function() {
    Route::get('/', ['as' => 'index', 'uses' => 'UserController@index']);
    Route::get('/{id}', ['as' => 'show', 'uses' => 'UserController@show']);
});
