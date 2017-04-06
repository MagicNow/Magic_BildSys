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
Auth::routes();

$router->group(['prefix' => '/', 'middleware' => ['auth']], function () use ($router) {

    $router->get('/', 'HomeController@index');
    $router->get('/home', 'HomeController@index');

    $router->resource('ordemDeCompras', 'OrdemDeCompraController');

    $router->get('compras', 'OrdemDeCompraController@compras');
    
    $router->get('planejamentos/lembretes', 'PlanejamentoController@lembretes');


    Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function () {
        Route::get('/home', 'Admin\HomeController@index');
        Route::get('/', 'Admin\HomeController@index');
        Route::get('/putsession', 'Admin\CodesController@putSession');
        Route::get('/checksession', 'Admin\CodesController@checkSession');
        Route::get('/getForeignKey', 'Admin\CodesController@getForeignKey');

        Route::resource('users', 'Admin\UserController');
    });


    $router->get('compras/insumos', 'OrdemDeCompraController@insumos');

});