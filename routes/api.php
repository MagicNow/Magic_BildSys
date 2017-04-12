<?php

use Illuminate\Http\Request;

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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

// Fetch all roles
Route::get('roles', [
    'as' => 'manage.roles',
    'uses' => 'RolesController@index'
]);
Route::post('roles/store',[
    'as' => 'manage.roles.store',
    'uses' => 'RolesController@store'
]);

Route::get('permissions', [
    'as' => 'manage.permissions',
    'uses' => 'PermissionsController@index'
]);
Route::post('permissions/store', [
    'as' => 'manage.permissions.store',
    'uses' =>'PermissionsController@store'
]);

// OrdemDeCompras
Route::get('/listagem-ordens-de-compras', 'ListagemOCController@index');

Route::post('users', 'UsersController@index');
Route::post('users/{id}', 'UsersController@show');