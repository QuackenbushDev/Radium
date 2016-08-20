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

Route::group(['prefix' => 'group', 'as' => 'group::'], function() {
    Route::get('/edit/{id}', ['as' => 'edit', 'uses' => 'GroupController@edit']);
    Route::get('/create', ['as' => 'create', 'uses' => 'GroupController@create']);
    Route::post('/create', ['as' => 'save', 'uses' => 'GroupController@save']);

    Route::get('/', ['as' => 'index', 'uses' => 'GroupController@index']);
    Route::put('/{id}', ['as' => 'update', 'uses' => 'GroupController@store']);
    Route::get('/{id}', ['as' => 'show', 'uses' => 'GroupController@show']);
});

Route::group(['prefix' => 'ip', 'as' => 'ip::'], function() {
    Route::get('/edit/{id}', ['as' => 'edit', 'uses' => 'IPController@edit']);
    Route::get('/create', ['as' => 'create', 'uses' => 'IPController@create']);
    Route::post('/create', ['as' => 'save', 'uses' => 'IPController@save']);

    Route::get('/', ['as' => 'index', 'uses' => 'IPController@index']);
    Route::put('/{id}', ['as' => 'update', 'uses' => 'IPController@store']);
    Route::get('/{id}', ['as' => 'show', 'uses' => 'IPController@show']);
});

Route::group(['prefix' => 'proxy', 'as' => 'proxy::'], function() {
    Route::get('/edit/{id}', ['as' => 'edit', 'uses' => 'ProxyController@edit']);
    Route::get('/create', ['as' => 'create', 'uses' => 'ProxyController@create']);
    Route::post('/create', ['as' => 'save', 'uses' => 'ProxyController@save']);

    Route::get('/', ['as' => 'index', 'uses' => 'ProxyController@index']);
    Route::put('/{id}', ['as' => 'update', 'uses' => 'ProxyController@store']);
    Route::get('/{id}', ['as' => 'show', 'uses' => 'ProxyController@show']);
});

Route::group(['prefix' => 'graph', 'as' => 'graph::'], function() {
    Route::get('/user', ['as' => 'user', 'uses' => 'GraphController@user']);
    Route::get('/statistics', ['as' => 'statistics', 'uses' => 'GraphController@statistics']);
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

Route::group(['prefix' => 'report', 'as' => 'report::'], function() {
    Route::get('/accounting', ['as' => 'accounting', 'uses' => 'ReportController@accounting']);
    Route::get('/bandwidth', ['as' => 'bandwidth', 'uses' => 'ReportController@bandwidth']);
    Route::get('/onlineUsers', ['as' => 'onlineUsers', 'uses' => 'ReportController@onlineUsers']);
    Route::get('/connectionAttempts', ['as' => 'connectionAttempts', 'uses' => 'ReportController@connectionAttempts']);
});