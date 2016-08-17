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
Route::get('/accounting', ['as' => 'accounting::index', 'uses' => 'AccountingController@index']);

Route::group(['prefix' => 'user', 'as' => 'user::'], function() {
    Route::get('/', ['as' => 'index', 'uses' => 'UserController@index']);
    Route::put('/{id}', ['as' => 'update', 'uses' => 'UserController@save']);
    Route::get('/{id}', ['as' => 'show', 'uses' => 'UserController@show']);
    Route::get('/edit/{id}', ['as' => 'edit', 'uses' => 'UserController@edit']);
});

Route::group(['prefix' => 'nas', 'as' => 'nas::'], function() {
    Route::get('/', ['as' => 'index', 'uses' => 'NasController@index']);
    Route::put('/{id}', ['as' => 'update', 'uses' => 'NasController@save']);
    Route::get('/{id}', ['as' => 'show', 'uses' => 'NasController@show']);
    Route::get('/edit/{id}', ['as' => 'edit', 'uses' => 'NasController@edit']);
});
