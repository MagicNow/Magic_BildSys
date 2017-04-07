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

$router->get('/', function () {
    return view('welcome');
});

Auth::routes();

$router->group(['prefix' => '/', 'middleware' => ['auth']], function () use ($router) {

    $router->get('/', 'HomeController@index');
    $router->get('/home', 'HomeController@index');

    $router->resource('ordemDeCompras', 'OrdemDeCompraController');

    $router->get('compras', 'OrdemDeCompraController@compras');

    $router->get('planejamentos/lembretes', 'PlanejamentoController@lembretes');

    $router->group(['prefix' => 'admin', 'middleware' => ['auth', 'needsPermission:dashboard.access']], function () use ($router) {
        Route::get('/home', 'Admin\HomeController@index');
        Route::get('/', 'Admin\HomeController@index');

        Route::resource('users', 'Admin\UserController');

        Route::get('/putsession', 'Admin\CodesController@putSession');
        Route::get('/checksession', 'Admin\CodesController@checkSession');
        Route::get('/getForeignKey', 'Admin\CodesController@getForeignKey');

        $router->group(['middleware' => 'needsPermission:users.list'], function() use ($router) {
            #Manage ACL
            $router->get('/manage', [
                'as' => 'manage.index',
                'uses' => 'Admin\Manage\DashboardController@index'
            ])->middleware("needsPermission:users.list");

            #Manage Users
            $router->get('/manage/users', [
                'as' => 'manage.users',
                'uses' => 'Admin\Manage\UsersController@index'
            ])->middleware("needsPermission:users.list");

            $router->get('/manage/users/create', [
                'as' => 'manage.users.create',
                'uses' => 'Admin\Manage\UsersController@create'
            ])->middleware("needsPermission:users.create");

            $router->post('/manage/users/store', [
                'as' => 'manage.users.store',
                'uses' => 'Admin\Manage\UsersController@store'
            ])->middleware("needsPermission:users.create");

            $router->get('/manage/users/{users}', [
                'as' => 'manage.users.show',
                'uses' => 'Admin\Manage\UsersController@show'
            ])->middleware("needsPermission:users.view");

            $router->get('/manage/users/{users}/edit', [
                'as' => 'manage.users.edit',
                'uses' => 'Admin\Manage\UsersController@edit'
            ])->middleware("needsPermission:users.edit");

            $router->patch('/manage/users/{users}/deactivate', [
                'as' => 'manage.users.deactivate',
                'uses' => 'Admin\Manage\UsersController@deactivate'
            ])->middleware("needsPermission:users.deactivate");

            $router->patch('/manage/users/{users}/activate', [
                'as' => 'manage.users.activate',
                'uses' => 'Admin\Manage\UsersController@activate'
            ])->middleware("needsPermission:users.deactivate");

            $router->delete('/manage/users/{users}/destroy', [
                'as' => 'manage.users.destroy',
                'uses' => 'Admin\Manage\UsersController@destroy'
            ])->middleware("needsPermission:users.deactivate");
            #End Manage Users

            /* Roles routes */
            $router->get('/manage/roles', [
                'as' => 'manage.roles',
                'uses' => 'Admin\Manage\RolesController@index'
            ])->middleware("needsPermission:roles.list");

            $router->post('/manage/users/{users}/roles/add', [
                'as' => 'manage.users.add.roles',
                'uses' => 'Admin\Manage\UsersController@addRole'
            ])->middleware("needsPermission:roles.create");

            $router->delete('/manage/users/{users}/roles/remove/{id}', [
                'as' => 'manage.users.remove.roles',
                'uses' => 'Admin\Manage\UsersController@removeRole'
            ])->middleware("needsPermission:permissions.create");

            $router->get('/manage/roles/{roles}', [
                'as' => 'manage.roles.show',
                'uses' => 'Admin\Manage\RolesController@show'
            ])->middleware("needsPermission:roles.view");

            $router->post('/manage/roles', [
                'as' => 'manage.roles.store',
                'uses' => 'Admin\Manage\RolesController@store'
            ])->middleware("needsPermission:roles.create");

            /* Permissions routes */
            $router->get('/manage/permissions', [
                'as' => 'manage.permissions',
                'uses' => 'Admin\Manage\PermissionsController@index'
            ])->middleware("needsPermission:permissions.list");

            $router->post('/manage/users/{users}/permissions/add', [
                'as' => 'manage.users.add.permissions',
                'uses' => 'Admin\Manage\UsersController@addPermission'
            ])->middleware("needsPermission:permissions.create");

            $router->delete('/manage/users/{users}/permissions/remove/{id}', [
                'as' => 'manage.users.remove.permissions',
                'uses' => 'Admin\Manage\UsersController@removePermission'
            ])->middleware("needsPermission:permissions.create");

        });
    });

    $router->get('compras/insumos', 'OrdemDeCompraController@insumos');
});

