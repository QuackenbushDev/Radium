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
    Route::get('/edit/{id}', ['as' => 'edit', 'uses' => 'UserController@edit']);
    Route::get('/create', ['as' => 'create', 'uses' => 'UserController@create']);
    Route::post('/create', ['as' => 'save', 'uses' => 'UserController@save']);

    Route::get('/disable/{id}', ['as' => 'disable', 'uses' => 'UserController@disableUser']);
    Route::get('/enable/{id}', ['as' => 'enable', 'uses' => 'UserController@enableUser']);

    Route::get('/disconnect/{id}', ['as' => 'disconnect', 'uses' => 'UserController@disconnectiFrame']);
    Route::post('/disconnect/{id}', ['as' => 'doDisconnect', 'uses' => 'UserController@disconnectUser']);

    Route::get('/test/{id}', ['as' => 'test', 'uses' => 'UserController@testiFrame']);
    Route::post('/test/{id}', ['as' => 'doTest', 'uses' => 'UserController@testUser']);

    Route::get('/', ['as' => 'index', 'uses' => 'UserController@index']);
    Route::put('/{id}', ['as' => 'update', 'uses' => 'UserController@store']);
    Route::get('/{id}', ['as' => 'show', 'uses' => 'UserController@show']);
});

Route::group(['prefix' => 'nas', 'as' => 'nas::'], function() {
    Route::get('/edit/{id}', ['as' => 'edit', 'uses' => 'NasController@edit']);
    Route::get('/create', ['as' => 'create', 'uses' => 'NasController@create']);
    Route::post('/create', ['as' => 'save', 'uses' => 'NasController@save']);

    Route::get('/', ['as' => 'index', 'uses' => 'NasController@index']);
    Route::put('/{id}', ['as' => 'update', 'uses' => 'NasController@store']);
    Route::get('/{id}', ['as' => 'show', 'uses' => 'NasController@show']);
});

Route::group(['prefix' => 'api', 'as' => 'api::'], function() {
    Route::get('/bandwidthUsage', ['as' => 'bandwidthUsage', 'uses' => 'APIController@bandwidthUsage']);
    Route::get('/connectionCount', ['as' => 'connectionCount', 'uses' => 'APIController@connectionCount']);
});

Route::group(['prefix' => 'portal', 'as' => 'portal::'], function() {
    Route::get('/login', ['as' => 'login', 'uses' => 'PortalController@login']);
    Route::post('/login', ['as' => 'login', 'uses' => 'PortalController@doLogin']);

    Route::get('/profile', ['as' => 'profile', 'uses' => 'PortalController@profile']);
    Route::put('/profile', ['as' => 'saveProfile', 'uses' => 'PortalController@saveProfile']);

    Route::get('/', ['as' => 'dashboard', 'uses' => 'PortalController@dashboard']);
});