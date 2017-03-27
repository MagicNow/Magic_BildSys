<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Auth::routes();

Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function () {
    Route::get('/home', 'Admin\HomeController@index');
    Route::get('/', 'Admin\HomeController@index');

    Route::resource('users', 'Admin\UserController');

    Route::get('/putsession', 'Admin\CodesController@putSession');
});


