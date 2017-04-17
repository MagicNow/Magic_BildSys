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

$router->get('/teste', function (){
    $grupos_mega = \App\Models\MegaInsumoGrupo::select([
        'GRU_IDE_ST_CODIGO',
        'GRU_IN_CODIGO',
        'GRU_ST_NOME',])
        ->where('gru_ide_st_codigo' , '07')
        ->first();
    dd($grupos_mega);
});

Auth::routes();

$router->group(['prefix' => '/', 'middleware' => ['auth']], function () use ($router) {

    $router->get('/', 'HomeController@index');
    $router->get('/home', 'HomeController@index');

    Route::get('/getForeignKey', 'CodesController@getForeignKey');

    $router->get('/ordens-de-compra/detalhes/{id}', 'OrdemDeCompraController@detalhe');
    $router->get('/ordens-de-compra/carrinho', 'OrdemDeCompraController@carrinho');
    $router->get('/ordens-de-compra/fechar-carrinho', 'OrdemDeCompraController@fechaCarrinho');
    $router->post('/ordens-de-compra/altera-item/{id}', 'OrdemDeCompraController@alteraItem');
    $router->post('/ordens-de-compra/upload-anexos/{id}', 'OrdemDeCompraController@uploadAnexos');
    $router->get('/ordens-de-compra/remover-anexo/{id}', 'OrdemDeCompraController@removerAnexo');
    $router->resource('ordens-de-compra', 'OrdemDeCompraController');


    $router->resource('retroalimentacaoObras', 'RetroalimentacaoObraController');

    $router->get('compras', 'OrdemDeCompraController@compras');
    $router->get('filter-json-ordem-compra', 'OrdemDeCompraController@filterJsonOrdemCompra');

    $router->get('planejamentos/lembretes', 'PlanejamentoController@lembretes');

    $router->group(['prefix' => 'admin', 'middleware' => ['auth', 'needsPermission:dashboard.access']], function () use ($router) {

        $router->resource('workflowReprovacaoMotivos', 'OrdemDeCompraController');

        $router->get('users/busca', 'Admin\Manage\UsersController@busca');

        Route::get('/home', 'Admin\HomeController@index');
        Route::get('/', 'Admin\HomeController@index');

        Route::resource('users', 'Admin\Manage\UsersController');

        #importação de planilhas de orçamentos
        Route::get('orcamento/', ['as'=> 'admin.orcamento.index', 'uses' => 'Admin\OrcamentoController@index']);
        Route::post('orcamento/importar', ['as'=> 'admin.orcamento.importar', 'uses' => 'Admin\OrcamentoController@import']);
        Route::get('orcamento/importar/checkIn', ['as'=> 'admin.orcamento.checkIn', 'uses' => 'Admin\OrcamentoController@checkIn']);
        Route::post('orcamento/importar/save', ['as'=> 'admin.orcamento.save', 'uses' => 'Admin\OrcamentoController@save']);
        Route::get('orcamento/importar/selecionaCampos', 'Admin\OrcamentoController@selecionaCampos');

        #importação de planilhas de planejamentos
        Route::get('planejamento/', ['as'=> 'admin.planejamentos.indexImport', 'uses' => 'Admin\PlanejamentoController@indexImport']);
        Route::post('planejamento/importar', ['as'=> 'admin.planejamentos.importar', 'uses' => 'Admin\PlanejamentoController@import']);
        Route::get('planejamento/importar/checkIn', ['as'=> 'admin.planejamentos.checkIn', 'uses' => 'Admin\PlanejamentoController@checkIn']);
        Route::post('planejamento/importar/save', ['as'=> 'admin.planejamentos.save', 'uses' => 'Admin\PlanejamentoController@save']);
        Route::get('planejamento/importar/selecionaCampos', 'Admin\PlanejamentoController@selecionaCampos');

        $router->group(['prefix' => 'planejamentos'], function () use ($router) {
            # Planejamentos
            $router->get('atividade', ['as' => 'admin.planejamentos.index', 'uses' => 'Admin\PlanejamentoController@index']);
            $router->post('atividade', ['as' => 'admin.planejamentos.store', 'uses' => 'Admin\PlanejamentoController@store']);
            $router->get('atividade/create', ['as' => 'admin.planejamentos.create', 'uses' => 'Admin\PlanejamentoController@create']);
            $router->put('atividade/{planejamentos}', ['as' => 'admin.planejamentos.update', 'uses' => 'Admin\PlanejamentoController@update']);
            $router->patch('atividade/{planejamentos}', ['as' => 'admin.planejamentos.update', 'uses' => 'Admin\PlanejamentoController@update']);
            $router->delete('atividade/{planejamentos}', ['as' => 'admin.planejamentos.destroy', 'uses' => 'Admin\PlanejamentoController@destroy']);
            $router->get('atividade/{planejamentos}', ['as' => 'admin.planejamentos.show', 'uses' => 'Admin\PlanejamentoController@show']);
            $router->get('atividade/{planejamentos}/edit', ['as' => 'admin.planejamentos.edit', 'uses' => 'Admin\PlanejamentoController@edit']);
            $router->get('atividade/grupos/{id}', 'Admin\PlanejamentoController@getGrupos');
            $router->get('atividade/servicos/{id}', 'Admin\PlanejamentoController@getServicos');

            # Lembretes
            $router->get('lembretes', ['as' => 'admin.lembretes.index', 'uses' => 'Admin\LembreteController@index']);
            $router->post('lembretes', ['as' => 'admin.lembretes.store', 'uses' => 'Admin\LembreteController@store']);
            $router->get('lembretes/create', ['as' => 'admin.lembretes.create', 'uses' => 'Admin\LembreteController@create']);
            $router->put('lembretes/{lembretes}', ['as' => 'admin.lembretes.update', 'uses' => 'Admin\LembreteController@update']);
            $router->patch('lembretes/{lembretes}', ['as' => 'admin.lembretes.update', 'uses' => 'Admin\LembreteController@update']);
            $router->delete('lembretes/{lembretes}', ['as' => 'admin.lembretes.destroy', 'uses' => 'Admin\LembreteController@destroy']);
            $router->get('lembretes/{lembretes}', ['as' => 'admin.lembretes.show', 'uses' => 'Admin\LembreteController@show']);
            $router->get('lembretes/{lembretes}/edit', ['as' => 'admin.lembretes.edit', 'uses' => 'Admin\LembreteController@edit']);
            $router->get('lembretes/filtro/busca', ['as' => 'admin.lembretes.busca', 'uses' => 'Admin\LembreteController@busca']);
        });

        # Contratos
        $router->get('contratos', ['as'=> 'admin.contratos.index', 'uses' => 'Admin\ContratosController@index']);
        $router->post('contratos', ['as'=> 'admin.contratos.store', 'uses' => 'Admin\ContratosController@store']);
        $router->get('contratos/create', ['as'=> 'admin.contratos.create', 'uses' => 'Admin\ContratosController@create']);
        $router->put('contratos/{contratos}', ['as'=> 'admin.contratos.update', 'uses' => 'Admin\ContratosController@update']);
        $router->patch('contratos/{contratos}', ['as'=> 'admin.contratos.update', 'uses' => 'Admin\ContratosController@update']);
        $router->delete('contratos/{contratos}', ['as'=> 'admin.contratos.destroy', 'uses' => 'Admin\ContratosController@destroy']);
        $router->get('contratos/{contratos}', ['as'=> 'admin.contratos.show', 'uses' => 'Admin\ContratosController@show']);
        $router->get('contratos/{contratos}/edit', ['as'=> 'admin.contratos.edit', 'uses' => 'Admin\ContratosController@edit']);
        $router->get('insumo/valor_total', 'Admin\ContratosController@calcularValorTotalInsumo');
        $router->get('insumo/delete', 'Admin\ContratosController@deleteInsumo');

        $router->get('obras', ['as'=> 'admin.obras.index', 'uses' => 'Admin\ObraController@index']);
        $router->post('obras', ['as'=> 'admin.obras.store', 'uses' => 'Admin\ObraController@store']);
        $router->get('obras/create', ['as'=> 'admin.obras.create', 'uses' => 'Admin\ObraController@create']);
        $router->put('obras/{obras}', ['as'=> 'admin.obras.update', 'uses' => 'Admin\ObraController@update']);
        $router->patch('obras/{obras}', ['as'=> 'admin.obras.update', 'uses' => 'Admin\ObraController@update']);
        $router->delete('obras/{obras}', ['as'=> 'admin.obras.destroy', 'uses' => 'Admin\ObraController@destroy']);
        $router->get('obras/{obras}', ['as'=> 'admin.obras.show', 'uses' => 'Admin\ObraController@show']);
        $router->get('obras/{obras}/edit', ['as'=> 'admin.obras.edit', 'uses' => 'Admin\ObraController@edit']);

        # Verifica Notificações
        $router->post('verifyNotification', 'Admin\HomeController@verifyNotifications');
        # Update Notificações visualizadas
        $router->get('updateNotification/{id}', 'Admin\NotificacaoController@updateNotification');



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

            $router->get('workflow-alcadas', ['as'=> 'admin.workflowAlcadas.index', 'uses' => 'Admin\WorkflowAlcadaController@index']);
            $router->post('workflow-alcadas', ['as'=> 'admin.workflowAlcadas.store', 'uses' => 'Admin\WorkflowAlcadaController@store']);
            $router->get('workflow-alcadas/create', ['as'=> 'admin.workflowAlcadas.create', 'uses' => 'Admin\WorkflowAlcadaController@create']);
            $router->put('workflow-alcadas/{workflowAlcadas}', ['as'=> 'admin.workflowAlcadas.update', 'uses' => 'Admin\WorkflowAlcadaController@update']);
            $router->patch('workflow-alcadas/{workflowAlcadas}', ['as'=> 'admin.workflowAlcadas.update', 'uses' => 'Admin\WorkflowAlcadaController@update']);
            $router->delete('workflow-alcadas/{workflowAlcadas}', ['as'=> 'admin.workflowAlcadas.destroy', 'uses' => 'Admin\WorkflowAlcadaController@destroy']);
            $router->get('workflow-alcadas/{workflowAlcadas}', ['as'=> 'admin.workflowAlcadas.show', 'uses' => 'Admin\WorkflowAlcadaController@show']);
            $router->get('workflow-alcadas/{workflowAlcadas}/edit', ['as'=> 'admin.workflowAlcadas.edit', 'uses' => 'Admin\WorkflowAlcadaController@edit']);

        });
    });

    $router->group(['prefix' => 'compras'], function () use ($router) {
        $router->get('{planejamento}/insumos/{insumoGrupo}', 'OrdemDeCompraController@insumos')->name('compraInsumo');
        $router->get('{planejamento}/insumosJson', 'OrdemDeCompraController@insumosJson');
        $router->get('{planejamento}/insumosFilters', 'OrdemDeCompraController@insumosFilters');
        $router->post('{planejamento}/insumosAdd', 'OrdemDeCompraController@insumosAdd');

        $router->get('{planejamento}/obrasInsumos/{insumoGrupo}', 'OrdemDeCompraController@obrasInsumos');
        $router->get('{planejamento}/obrasInsumosFilters', 'OrdemDeCompraController@obrasInsumosFilters');
        $router->get('{planejamento}/obrasInsumosJson/{insumoGrupo}', 'OrdemDeCompraController@obrasInsumosJson');
    });

    $router->group(['prefix' => 'workflow'], function () use ($router) {
        $router->get('aprova-reprova', 'WorkflowController@aprovaReprova');
        $router->get('aprova-reprova-tudo', 'WorkflowController@aprovaReprovaTudo');
    });
});



