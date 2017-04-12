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

    $router->resource('ordens-de-compra', 'OrdemDeCompraController');

    $router->resource('retroalimentacaoObras', 'RetroalimentacaoObraController');

    $router->get('compras', 'OrdemDeCompraController@compras');
    $router->get('obras_insumos', 'OrdemDeCompraController@obrasInsumos');
    $router->get('obras_insumos_filter', 'OrdemDeCompraController@obrasInsumosFilters');
    $router->get('filter-json-ordem-compra', 'OrdemDeCompraController@filterJsonOrdemCompra');
    $router->get('insumos_json', 'OrdemDeCompraController@insumosJson');

    $router->get('planejamentos/lembretes', 'PlanejamentoController@lembretes');

    $router->group(['prefix' => 'admin', 'middleware' => ['auth', 'needsPermission:dashboard.access']], function () use ($router) {

        $router->resource('workflowReprovacaoMotivos', 'OrdemDeCompraController');

        $router->get('users/busca', 'Admin\Manage\UsersController@busca');

        Route::get('/home', 'Admin\HomeController@index');
        Route::get('/', 'Admin\HomeController@index');

        Route::resource('users', 'Admin\UserController');
        
        Route::get('/getForeignKey', 'CodesController@getForeignKey');

        #importação de planilhas de orçamentos
        Route::get('orcamento/', ['as'=> 'admin.orcamento.index', 'uses' => 'Admin\OrcamentoController@index']);
        Route::post('orcamento/importar', ['as'=> 'admin.orcamento.importar', 'uses' => 'Admin\OrcamentoController@import']);
        Route::get('orcamento/importar/checkIn', ['as'=> 'admin.orcamento.checkIn', 'uses' => 'Admin\OrcamentoController@checkIn']);
        Route::post('orcamento/importar/save', ['as'=> 'admin.orcamento.save', 'uses' => 'Admin\OrcamentoController@save']);
        Route::get('orcamento/importar/selecionaCampos', 'Admin\OrcamentoController@selecionaCampos');

        #importação de planilhas de planejamentos
        Route::get('planejamento/', ['as'=> 'admin.planejamento.index', 'uses' => 'Admin\PlanejamentoController@index']);
        Route::post('planejamento/importar', ['as'=> 'admin.planejamento.importar', 'uses' => 'Admin\PlanejamentoController@import']);
        Route::get('planejamento/importar/checkIn', ['as'=> 'admin.planejamento.checkIn', 'uses' => 'Admin\PlanejamentoController@checkIn']);
        Route::post('planejamento/importar/save', ['as'=> 'admin.planejamento.save', 'uses' => 'Admin\PlanejamentoController@save']);
        Route::get('planejamento/importar/selecionaCampos', 'Admin\PlanejamentoController@selecionaCampos');

        # Verifica Notificações
        Route::post('verifyNotification', 'Admin\HomeController@verifyNotifications');
        # Update Notificações visualizadas
        Route::get('updateNotification/{id}', 'Admin\NotificacaoController@updateNotification');

        # Lembretes
        Route::get('lembretes', ['as'=> 'admin.lembretes.index', 'uses' => 'Admin\LembreteController@index']);
        Route::post('lembretes', ['as'=> 'admin.lembretes.store', 'uses' => 'Admin\LembreteController@store']);
        Route::get('lembretes/create', ['as'=> 'admin.lembretes.create', 'uses' => 'Admin\LembreteController@create']);
        Route::put('lembretes/{lembretes}', ['as'=> 'admin.lembretes.update', 'uses' => 'Admin\LembreteController@update']);
        Route::patch('lembretes/{lembretes}', ['as'=> 'admin.lembretes.update', 'uses' => 'Admin\LembreteController@update']);
        Route::delete('lembretes/{lembretes}', ['as'=> 'admin.lembretes.destroy', 'uses' => 'Admin\LembreteController@destroy']);
        Route::get('lembretes/{lembretes}', ['as'=> 'admin.lembretes.show', 'uses' => 'Admin\LembreteController@show']);
        Route::get('lembretes/{lembretes}/edit', ['as'=> 'admin.lembretes.edit', 'uses' => 'Admin\LembreteController@edit']);
        Route::get('lembretes/busca', ['as' => 'admin.lembretes.busca', 'uses' => 'Admin\LembreteController@busca']);

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

        $router->group(['prefix' => 'workflow'], function () use ($router) {

            $router->get('reprovacao-motivos', ['as'=> 'admin.workflowReprovacaoMotivos.index', 'uses' => 'Admin\WorkflowReprovacaoMotivoController@index']);
            $router->post('reprovacao-motivos', ['as'=> 'admin.workflowReprovacaoMotivos.store', 'uses' => 'Admin\WorkflowReprovacaoMotivoController@store']);
            $router->get('reprovacao-motivos/create', ['as'=> 'admin.workflowReprovacaoMotivos.create', 'uses' => 'Admin\WorkflowReprovacaoMotivoController@create']);
            $router->put('reprovacao-motivos/{workflowReprovacaoMotivos}', ['as'=> 'admin.workflowReprovacaoMotivos.update', 'uses' => 'Admin\WorkflowReprovacaoMotivoController@update']);
            $router->patch('reprovacao-motivos/{workflowReprovacaoMotivos}', ['as'=> 'admin.workflowReprovacaoMotivos.update', 'uses' => 'Admin\WorkflowReprovacaoMotivoController@update']);
            $router->delete('reprovacao-motivos/{workflowReprovacaoMotivos}', ['as'=> 'admin.workflowReprovacaoMotivos.destroy', 'uses' => 'Admin\WorkflowReprovacaoMotivoController@destroy']);
            $router->get('reprovacao-motivos/{workflowReprovacaoMotivos}', ['as'=> 'admin.workflowReprovacaoMotivos.show', 'uses' => 'Admin\WorkflowReprovacaoMotivoController@show']);
            $router->get('reprovacao-motivos/{workflowReprovacaoMotivos}/edit', ['as'=> 'admin.workflowReprovacaoMotivos.edit', 'uses' => 'Admin\WorkflowReprovacaoMotivoController@edit']);

            Route::get('workflow-alcadas', ['as'=> 'admin.workflowAlcadas.index', 'uses' => 'Admin\WorkflowAlcadaController@index']);
            Route::post('workflow-alcadas', ['as'=> 'admin.workflowAlcadas.store', 'uses' => 'Admin\WorkflowAlcadaController@store']);
            Route::get('workflow-alcadas/create', ['as'=> 'admin.workflowAlcadas.create', 'uses' => 'Admin\WorkflowAlcadaController@create']);
            Route::put('workflow-alcadas/{workflowAlcadas}', ['as'=> 'admin.workflowAlcadas.update', 'uses' => 'Admin\WorkflowAlcadaController@update']);
            Route::patch('workflow-alcadas/{workflowAlcadas}', ['as'=> 'admin.workflowAlcadas.update', 'uses' => 'Admin\WorkflowAlcadaController@update']);
            Route::delete('workflow-alcadas/{workflowAlcadas}', ['as'=> 'admin.workflowAlcadas.destroy', 'uses' => 'Admin\WorkflowAlcadaController@destroy']);
            Route::get('workflow-alcadas/{workflowAlcadas}', ['as'=> 'admin.workflowAlcadas.show', 'uses' => 'Admin\WorkflowAlcadaController@show']);
            Route::get('workflow-alcadas/{workflowAlcadas}/edit', ['as'=> 'admin.workflowAlcadas.edit', 'uses' => 'Admin\WorkflowAlcadaController@edit']);

        });
    });

    $router->get('compras/insumos', 'OrdemDeCompraController@insumos');
    $router->get('compras/insumos/lista', 'OrdemDeCompraController@insumosLista');
    $router->get('obras_insumos', 'OrdemDeCompraController@obrasInsumos');
    $router->get('insumos_json', 'OrdemDeCompraController@insumosJson');
});
