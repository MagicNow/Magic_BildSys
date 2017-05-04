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

##### ADMIN #####
$router->group(['prefix' => 'admin', 'middleware' => ['auth', 'needsPermission:dashboard.access']], function () use ($router) {

    $router->resource('retroalimentacaoObras', 'RetroalimentacaoObraController');

    $router->get('users/busca', 'Admin\Manage\UsersController@busca');

    Route::get('/home', 'Admin\HomeController@index');
    Route::get('/', 'Admin\HomeController@index');

    # Verifica Notificações
    $router->post('verifyNotification', 'Admin\HomeController@verifyNotifications');
    # Update Notificações visualizadas
    $router->get('updateNotification/{id}', 'Admin\NotificacaoController@updateNotification');

    #importação de planilhas de orçamentos
    $router->group(['middleware' => 'needsPermission:orcamentos.import'], function () use ($router) {
        Route::get('orcamento/', ['as' => 'admin.orcamentos.indexImport', 'uses' => 'Admin\OrcamentoController@indexImport']);
        Route::post('orcamento/importar', ['as' => 'admin.orcamentos.importar', 'uses' => 'Admin\OrcamentoController@import']);
        Route::get('orcamento/importar/checkIn', ['as' => 'admin.orcamentos.checkIn', 'uses' => 'Admin\OrcamentoController@checkIn']);
        Route::post('orcamento/importar/save', ['as' => 'admin.orcamentos.save', 'uses' => 'Admin\OrcamentoController@save']);
        Route::get('orcamento/importar/selecionaCampos', 'Admin\OrcamentoController@selecionaCampos');
    });

    # Orçamentos
    $router->group(['middleware' => 'needsPermission:orcamentos.list'], function () use ($router) {
        Route::get('orcamentos', ['as' => 'admin.orcamentos.index', 'uses' => 'Admin\OrcamentoController@index']);
        Route::post('orcamentos', ['as' => 'admin.orcamentos.store', 'uses' => 'Admin\OrcamentoController@store']);
        Route::get('orcamentos/create', ['as' => 'admin.orcamentos.create', 'uses' => 'Admin\OrcamentoController@create']);
        Route::put('orcamentos/{orcamentos}', ['as' => 'admin.orcamentos.update', 'uses' => 'Admin\OrcamentoController@update']);
        Route::patch('orcamentos/{orcamentos}', ['as' => 'admin.orcamentos.update', 'uses' => 'Admin\OrcamentoController@update']);
        Route::delete('orcamentos/{orcamentos}', ['as' => 'admin.orcamentos.destroy', 'uses' => 'Admin\OrcamentoController@destroy']);
        Route::get('orcamentos/{orcamentos}', ['as' => 'admin.orcamentos.show', 'uses' => 'Admin\OrcamentoController@show']);
        Route::get('orcamentos/{orcamentos}/edit', ['as' => 'admin.orcamentos.edit', 'uses' => 'Admin\OrcamentoController@edit']);
    });

    #importação de planilhas de planejamentos
    $router->group(['middleware' => 'needsPermission:planejamento.import'], function () use ($router) {
        Route::get('planejamento/', ['as' => 'admin.planejamentos.indexImport', 'uses' => 'Admin\PlanejamentoController@indexImport']);
        Route::post('planejamento/importar', ['as' => 'admin.planejamentos.importar', 'uses' => 'Admin\PlanejamentoController@import']);
        Route::get('planejamento/importar/checkIn', ['as' => 'admin.planejamentos.checkIn', 'uses' => 'Admin\PlanejamentoController@checkIn']);
        Route::post('planejamento/importar/save', ['as' => 'admin.planejamentos.save', 'uses' => 'Admin\PlanejamentoController@save']);
        Route::get('planejamento/importar/selecionaCampos', 'Admin\PlanejamentoController@selecionaCampos');
    });

    $router->group(['prefix' => 'planejamentos'], function () use ($router) {
        # Planejamentos
        $router->group(['middleware' => 'needsPermission:cronograma_de_obras.list'], function () use ($router) {
            $router->get('atividade', ['as' => 'admin.planejamentos.index', 'uses' => 'Admin\PlanejamentoController@index']);
            $router->post('atividade', ['as' => 'admin.planejamentos.store', 'uses' => 'Admin\PlanejamentoController@store']);
            $router->get('atividade/create', ['as' => 'admin.planejamentos.create', 'uses' => 'Admin\PlanejamentoController@create']);
            $router->put('atividade/{planejamentos}', ['as' => 'admin.planejamentos.update', 'uses' => 'Admin\PlanejamentoController@update']);
            $router->patch('atividade/{planejamentos}', ['as' => 'admin.planejamentos.update', 'uses' => 'Admin\PlanejamentoController@update']);
            $router->delete('atividade/{planejamentos}', ['as' => 'admin.planejamentos.destroy', 'uses' => 'Admin\PlanejamentoController@destroy']);
            $router->get('atividade/{planejamentos}', ['as' => 'admin.planejamentos.show', 'uses' => 'Admin\PlanejamentoController@show'])->middleware("needsPermission:cronograma_de_obras.view");
            $router->get('atividade/{planejamentos}/edit', ['as' => 'admin.planejamentos.edit', 'uses' => 'Admin\PlanejamentoController@edit'])->middleware("needsPermission:cronograma_de_obras.edit");
            $router->get('atividade/grupos/{id}', 'Admin\PlanejamentoController@getGrupos');
            $router->get('atividade/servicos/{id}', 'Admin\PlanejamentoController@getServicos');
            $router->get('atividade/servico/insumo/relacionados', 'Admin\PlanejamentoController@GrupoRelacionados');
            $router->get('atividade/servico/insumo/{id}', 'Admin\PlanejamentoController@getServicoInsumos');
            $router->post('atividade/insumos', ['as' => 'admin.planejamentos.insumos', 'uses' => 'Admin\PlanejamentoController@planejamentoCompras']);
            $router->get('atividade/planejamentocompras/{id}', 'Admin\PlanejamentoController@destroyPlanejamentoCompra');
        });

        Route::get('planejamentoOrcamentos', ['as'=> 'admin.planejamentoOrcamentos.index', 'uses' => 'Admin\PlanejamentoOrcamentoController@index']);
        Route::post('planejamentoOrcamentos', ['as'=> 'admin.planejamentoOrcamentos.store', 'uses' => 'Admin\PlanejamentoOrcamentoController@store']);
        Route::get('planejamentoOrcamentos/create', ['as'=> 'admin.planejamentoOrcamentos.create', 'uses' => 'Admin\PlanejamentoOrcamentoController@create']);
        Route::put('planejamentoOrcamentos/{planejamentoOrcamentos}', ['as'=> 'admin.planejamentoOrcamentos.update', 'uses' => 'Admin\PlanejamentoOrcamentoController@update']);
        Route::patch('planejamentoOrcamentos/{planejamentoOrcamentos}', ['as'=> 'admin.planejamentoOrcamentos.update', 'uses' => 'Admin\PlanejamentoOrcamentoController@update']);
        Route::delete('planejamentoOrcamentos/{planejamentoOrcamentos}', ['as'=> 'admin.planejamentoOrcamentos.destroy', 'uses' => 'Admin\PlanejamentoOrcamentoController@destroy']);
        Route::get('planejamentoOrcamentos/{planejamentoOrcamentos}', ['as'=> 'admin.planejamentoOrcamentos.show', 'uses' => 'Admin\PlanejamentoOrcamentoController@show']);
        Route::get('planejamentoOrcamentos/{planejamentoOrcamentos}/edit', ['as'=> 'admin.planejamentoOrcamentos.edit', 'uses' => 'Admin\PlanejamentoOrcamentoController@edit']);
        Route::get('planejamentoOrcamentos/orcamentos/relacionados', 'Admin\PlanejamentoOrcamentoController@GrupoRelacionados');
        Route::get('planejamentoOrcamentos/planejamento/{id}', 'Admin\PlanejamentoOrcamentoController@getPlanejamentos');
        Route::get('planejamentoOrcamentos/orcamento/{id}', 'Admin\PlanejamentoOrcamentoController@getOrcamentos');

        # Lembretes
        $router->group(['middleware' => 'needsPermission:lembretes.list'], function () use ($router) {
            $router->get('lembretes', ['as' => 'admin.lembretes.index', 'uses' => 'Admin\LembreteController@index']);
            $router->post('lembretes', ['as' => 'admin.lembretes.store', 'uses' => 'Admin\LembreteController@store']);
            $router->get('lembretes/create', ['as' => 'admin.lembretes.create', 'uses' => 'Admin\LembreteController@create'])->middleware("needsPermission:lembretes.create");
            $router->put('lembretes/{lembretes}', ['as' => 'admin.lembretes.update', 'uses' => 'Admin\LembreteController@update']);
            $router->patch('lembretes/{lembretes}', ['as' => 'admin.lembretes.update', 'uses' => 'Admin\LembreteController@update']);
            $router->delete('lembretes/{lembretes}', ['as' => 'admin.lembretes.destroy', 'uses' => 'Admin\LembreteController@destroy']);
            $router->get('lembretes/{lembretes}', ['as' => 'admin.lembretes.show', 'uses' => 'Admin\LembreteController@show'])->middleware("needsPermission:lembretes.view");
            $router->get('lembretes/{lembretes}/edit', ['as' => 'admin.lembretes.edit', 'uses' => 'Admin\LembreteController@edit'])->middleware("needsPermission:lembretes.edit");
            $router->get('lembretes/filtro/busca', ['as' => 'admin.lembretes.busca', 'uses' => 'Admin\LembreteController@busca']);
        });

        $router->get('tipo-lembretes', ['as'=> 'admin.lembreteTipos.index', 'uses' => 'Admin\LembreteTipoController@index']);
        $router->post('tipo-lembretes', ['as'=> 'admin.lembreteTipos.store', 'uses' => 'Admin\LembreteTipoController@store']);
        $router->get('tipo-lembretes/create', ['as'=> 'admin.lembreteTipos.create', 'uses' => 'Admin\LembreteTipoController@create']);
        $router->put('tipo-lembretes/{lembreteTipos}', ['as'=> 'admin.lembreteTipos.update', 'uses' => 'Admin\LembreteTipoController@update']);
        $router->patch('tipo-lembretes/{lembreteTipos}', ['as'=> 'admin.lembreteTipos.update', 'uses' => 'Admin\LembreteTipoController@update']);
        $router->delete('tipo-lembretes/{lembreteTipos}', ['as'=> 'admin.lembreteTipos.destroy', 'uses' => 'Admin\LembreteTipoController@destroy']);
        $router->get('tipo-lembretes/{lembreteTipos}', ['as'=> 'admin.lembreteTipos.show', 'uses' => 'Admin\LembreteTipoController@show']);
        $router->get('tipo-lembretes/{lembreteTipos}/edit', ['as'=> 'admin.lembreteTipos.edit', 'uses' => 'Admin\LembreteTipoController@edit']);

    });

    # Contratos
    $router->group(['middleware' => 'needsPermission:contratos.list'], function () use ($router) {
        $router->get('contratos', ['as' => 'admin.catalogo_contratos.index', 'uses' => 'Admin\CatalogoContratoController@index']);
        $router->post('contratos', ['as' => 'admin.catalogo_contratos.store', 'uses' => 'Admin\CatalogoContratoController@store']);
        $router->get('contratos/create', ['as' => 'admin.catalogo_contratos.create', 'uses' => 'Admin\CatalogoContratoController@create'])->middleware("needsPermission:contratos.create");
        $router->put('contratos/{contratos}', ['as' => 'admin.catalogo_contratos.update', 'uses' => 'Admin\CatalogoContratoController@update']);
        $router->patch('contratos/{contratos}', ['as' => 'admin.catalogo_contratos.update', 'uses' => 'Admin\CatalogoContratoController@update']);
        $router->delete('contratos/{contratos}', ['as' => 'admin.catalogo_contratos.destroy', 'uses' => 'Admin\CatalogoContratoController@destroy']);
        $router->get('contratos/{contratos}', ['as' => 'admin.catalogo_contratos.show', 'uses' => 'Admin\CatalogoContratoController@show'])->middleware("needsPermission:contratos.view");
        $router->get('contratos/{contratos}/edit', ['as' => 'admin.catalogo_contratos.edit', 'uses' => 'Admin\CatalogoContratoController@edit'])->middleware("needsPermission:contratos.edit");
        $router->get('contratos/buscar/busca_fornecedores', ['as' => 'admin.catalogo_contratos.busca_fornecedores', 'uses' => 'Admin\CatalogoContratoController@buscaFornecedor']);
        $router->get('contratos/buscar/busca_insumos', ['as' => 'admin.catalogo_contratos.busca_insumos', 'uses' => 'Admin\CatalogoContratoController@buscaInsumos']);
        $router->get('insumo/delete', 'Admin\CatalogoContratoController@deleteInsumo');
    });

    # Obras
    $router->group(['middleware' => 'needsPermission:obras.list'], function () use ($router) {
        $router->get('obras', ['as' => 'admin.obras.index', 'uses' => 'Admin\ObraController@index']);
        $router->post('obras', ['as' => 'admin.obras.store', 'uses' => 'Admin\ObraController@store']);
        $router->get('obras/create', ['as' => 'admin.obras.create', 'uses' => 'Admin\ObraController@create'])->middleware("needsPermission:obras.create");
        $router->put('obras/{obras}', ['as' => 'admin.obras.update', 'uses' => 'Admin\ObraController@update']);
        $router->patch('obras/{obras}', ['as' => 'admin.obras.update', 'uses' => 'Admin\ObraController@update']);
        $router->delete('obras/{obras}', ['as' => 'admin.obras.destroy', 'uses' => 'Admin\ObraController@destroy']);
        $router->get('obras/{obras}', ['as' => 'admin.obras.show', 'uses' => 'Admin\ObraController@show'])->middleware("needsPermission:obras.view");
        $router->get('obras/{obras}/edit', ['as' => 'admin.obras.edit', 'uses' => 'Admin\ObraController@edit'])->middleware("needsPermission:obras.edit");
    });

    # Template de importação de planilha
    $router->group(['middleware' => 'needsPermission:template_planilhas.list'], function () use ($router) {
        Route::get('templatePlanilhas', ['as' => 'admin.templatePlanilhas.index', 'uses' => 'Admin\TemplatePlanilhaController@index']);
        Route::post('templatePlanilhas', ['as' => 'admin.templatePlanilhas.store', 'uses' => 'Admin\TemplatePlanilhaController@store']);
        Route::get('templatePlanilhas/create', ['as' => 'admin.templatePlanilhas.create', 'uses' => 'Admin\TemplatePlanilhaController@create']);
        Route::put('templatePlanilhas/{templatePlanilhas}', ['as' => 'admin.templatePlanilhas.update', 'uses' => 'Admin\TemplatePlanilhaController@update']);
        Route::patch('templatePlanilhas/{templatePlanilhas}', ['as' => 'admin.templatePlanilhas.update', 'uses' => 'Admin\TemplatePlanilhaController@update']);
        Route::delete('templatePlanilhas/{templatePlanilhas}', ['as' => 'admin.templatePlanilhas.destroy', 'uses' => 'Admin\TemplatePlanilhaController@destroy']);
        Route::get('templatePlanilhas/{templatePlanilhas}', ['as' => 'admin.templatePlanilhas.show', 'uses' => 'Admin\TemplatePlanilhaController@show']);
        Route::get('templatePlanilhas/{templatePlanilhas}/edit', ['as' => 'admin.templatePlanilhas.edit', 'uses' => 'Admin\TemplatePlanilhaController@edit']);
    });

    # Tipo equalização tecnicas
    Route::get('tipoEqualizacaoTecnicas', ['as'=> 'admin.tipoEqualizacaoTecnicas.index', 'uses' => 'Admin\TipoEqualizacaoTecnicaController@index']);
    Route::post('tipoEqualizacaoTecnicas', ['as'=> 'admin.tipoEqualizacaoTecnicas.store', 'uses' => 'Admin\TipoEqualizacaoTecnicaController@store']);
    Route::get('tipoEqualizacaoTecnicas/create', ['as'=> 'admin.tipoEqualizacaoTecnicas.create', 'uses' => 'Admin\TipoEqualizacaoTecnicaController@create']);
    Route::put('tipoEqualizacaoTecnicas/{tipoEqualizacaoTecnicas}', ['as'=> 'admin.tipoEqualizacaoTecnicas.update', 'uses' => 'Admin\TipoEqualizacaoTecnicaController@update']);
    Route::patch('tipoEqualizacaoTecnicas/{tipoEqualizacaoTecnicas}', ['as'=> 'admin.tipoEqualizacaoTecnicas.update', 'uses' => 'Admin\TipoEqualizacaoTecnicaController@update']);
    Route::delete('tipoEqualizacaoTecnicas/{tipoEqualizacaoTecnicas}', ['as'=> 'admin.tipoEqualizacaoTecnicas.destroy', 'uses' => 'Admin\TipoEqualizacaoTecnicaController@destroy']);
    Route::get('tipoEqualizacaoTecnicas/{tipoEqualizacaoTecnicas}', ['as'=> 'admin.tipoEqualizacaoTecnicas.show', 'uses' => 'Admin\TipoEqualizacaoTecnicaController@show']);
    Route::get('tipoEqualizacaoTecnicas/{tipoEqualizacaoTecnicas}/edit', ['as'=> 'admin.tipoEqualizacaoTecnicas.edit', 'uses' => 'Admin\TipoEqualizacaoTecnicaController@edit']);

    #Cronograma por obra
    Route::get('planejamentoCronogramas', ['as'=> 'admin.planejamentoCronogramas.index', 'uses' => 'Admin\PlanejamentoCronogramaController@index'])->middleware("needsPermission:cronograma_por_obras.list");

        # Fornecedores
        Route::get('fornecedores', ['as'=> 'admin.fornecedores.index', 'uses' => 'Admin\FornecedoresController@index']);
        Route::post('fornecedores', ['as'=> 'admin.fornecedores.store', 'uses' => 'Admin\FornecedoresController@store']);
        Route::get('fornecedores/create', ['as'=> 'admin.fornecedores.create', 'uses' => 'Admin\FornecedoresController@create']);
        Route::put('fornecedores/{fornecedores}', ['as'=> 'admin.fornecedores.update', 'uses' => 'Admin\FornecedoresController@update']);
        Route::patch('fornecedores/{fornecedores}', ['as'=> 'admin.fornecedores.update', 'uses' => 'Admin\FornecedoresController@update']);
        Route::delete('fornecedores/{fornecedores}', ['as'=> 'admin.fornecedores.destroy', 'uses' => 'Admin\FornecedoresController@destroy']);
        Route::get('fornecedores/{fornecedores}', ['as'=> 'admin.fornecedores.show', 'uses' => 'Admin\FornecedoresController@show']);
        Route::get('fornecedores/{fornecedores}/edit', ['as'=> 'admin.fornecedores.edit', 'uses' => 'Admin\FornecedoresController@edit']);
        Route::get('fornecedores/buscacep/{cep}', 'Admin\FornecedoresController@buscaPorCep');
        Route::get('valida-documento', 'Admin\FornecedoresController@validaCnpj');

    $router->group(['middleware' => 'needsPermission:users.list'], function() use ($router) {
        Route::resource('users', 'Admin\Manage\UsersController');

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

        $router->group(['middleware' => 'needsPermission:motivos_reprovacao.list'], function() use ($router) {
            $router->resource('workflowReprovacaoMotivos', 'OrdemDeCompraController');
            $router->get('reprovacao-motivos', ['as' => 'admin.workflowReprovacaoMotivos.index', 'uses' => 'Admin\WorkflowReprovacaoMotivoController@index']);
            $router->post('reprovacao-motivos', ['as' => 'admin.workflowReprovacaoMotivos.store', 'uses' => 'Admin\WorkflowReprovacaoMotivoController@store']);
            $router->get('reprovacao-motivos/create', ['as' => 'admin.workflowReprovacaoMotivos.create', 'uses' => 'Admin\WorkflowReprovacaoMotivoController@create'])->middleware("needsPermission:motivos_reprovacao.create");
            $router->put('reprovacao-motivos/{workflowReprovacaoMotivos}', ['as' => 'admin.workflowReprovacaoMotivos.update', 'uses' => 'Admin\WorkflowReprovacaoMotivoController@update']);
            $router->patch('reprovacao-motivos/{workflowReprovacaoMotivos}', ['as' => 'admin.workflowReprovacaoMotivos.update', 'uses' => 'Admin\WorkflowReprovacaoMotivoController@update']);
            $router->delete('reprovacao-motivos/{workflowReprovacaoMotivos}', ['as' => 'admin.workflowReprovacaoMotivos.destroy', 'uses' => 'Admin\WorkflowReprovacaoMotivoController@destroy']);
            $router->get('reprovacao-motivos/{workflowReprovacaoMotivos}', ['as' => 'admin.workflowReprovacaoMotivos.show', 'uses' => 'Admin\WorkflowReprovacaoMotivoController@show'])->middleware("needsPermission:motivos_reprovacao.view");
            $router->get('reprovacao-motivos/{workflowReprovacaoMotivos}/edit', ['as' => 'admin.workflowReprovacaoMotivos.edit', 'uses' => 'Admin\WorkflowReprovacaoMotivoController@edit'])->middleware("needsPermission:motivos_reprovacao.edit");
        });

        $router->group(['middleware' => 'needsPermission:alcadas.list'], function() use ($router) {
            $router->get('workflow-alcadas', ['as' => 'admin.workflowAlcadas.index', 'uses' => 'Admin\WorkflowAlcadaController@index']);
            $router->post('workflow-alcadas', ['as' => 'admin.workflowAlcadas.store', 'uses' => 'Admin\WorkflowAlcadaController@store']);
            $router->get('workflow-alcadas/create', ['as' => 'admin.workflowAlcadas.create', 'uses' => 'Admin\WorkflowAlcadaController@create'])->middleware("needsPermission:alcadas.create");
            $router->put('workflow-alcadas/{workflowAlcadas}', ['as' => 'admin.workflowAlcadas.update', 'uses' => 'Admin\WorkflowAlcadaController@update']);
            $router->patch('workflow-alcadas/{workflowAlcadas}', ['as' => 'admin.workflowAlcadas.update', 'uses' => 'Admin\WorkflowAlcadaController@update']);
            $router->delete('workflow-alcadas/{workflowAlcadas}', ['as' => 'admin.workflowAlcadas.destroy', 'uses' => 'Admin\WorkflowAlcadaController@destroy']);
            $router->get('workflow-alcadas/{workflowAlcadas}', ['as' => 'admin.workflowAlcadas.show', 'uses' => 'Admin\WorkflowAlcadaController@show'])->middleware("needsPermission:alcadas.view");
            $router->get('workflow-alcadas/{workflowAlcadas}/edit', ['as' => 'admin.workflowAlcadas.edit', 'uses' => 'Admin\WorkflowAlcadaController@edit'])->middleware("needsPermission:alcadas.edit");
        });

    });

    $router->group(['middleware' => 'needsPermission:insumos.list'], function() use ($router) {
        $router->get('insumos', ['as' => 'admin.insumos.index', 'uses' => 'Admin\InsumoController@index']);
        $router->post('insumos', ['as' => 'admin.insumos.store', 'uses' => 'Admin\InsumoController@store']);
        $router->get('insumos/create', ['as' => 'admin.insumos.create', 'uses' => 'Admin\InsumoController@create']);
        $router->put('insumos/{insumos}', ['as' => 'admin.insumos.update', 'uses' => 'Admin\InsumoController@update']);
        $router->patch('insumos/{insumos}', ['as' => 'admin.insumos.update', 'uses' => 'Admin\InsumoController@update']);
        $router->delete('insumos/{insumos}', ['as' => 'admin.insumos.destroy', 'uses' => 'Admin\InsumoController@destroy']);
        $router->get('insumos/{insumos}', ['as' => 'admin.insumos.show', 'uses' => 'Admin\InsumoController@show'])->middleware("needsPermission:insumos.view");
        $router->get('insumos/{insumos}/edit', ['as' => 'admin.insumos.edit', 'uses' => 'Admin\InsumoController@edit']);
    });

    $router->group(['middleware' => 'needsPermission:grupos_insumos.list'], function() use ($router) {
        $router->get('insumoGrupos', ['as' => 'admin.insumoGrupos.index', 'uses' => 'Admin\InsumoGrupoController@index']);
        $router->post('insumoGrupos', ['as' => 'admin.insumoGrupos.store', 'uses' => 'Admin\InsumoGrupoController@store']);
        $router->get('insumoGrupos/create', ['as' => 'admin.insumoGrupos.create', 'uses' => 'Admin\InsumoGrupoController@create']);
        $router->put('insumoGrupos/{insumoGrupos}', ['as' => 'admin.insumoGrupos.update', 'uses' => 'Admin\InsumoGrupoController@update']);
        $router->patch('insumoGrupos/{insumoGrupos}', ['as' => 'admin.insumoGrupos.update', 'uses' => 'Admin\InsumoGrupoController@update']);
        $router->delete('insumoGrupos/{insumoGrupos}', ['as' => 'admin.insumoGrupos.destroy', 'uses' => 'Admin\InsumoGrupoController@destroy']);
        $router->get('insumoGrupos/{insumoGrupos}', ['as' => 'admin.insumoGrupos.show', 'uses' => 'Admin\InsumoGrupoController@show'])->middleware("needsPermission:grupos_insumos.view");
        $router->get('insumoGrupos/{insumoGrupos}/edit', ['as' => 'admin.insumoGrupos.edit', 'uses' => 'Admin\InsumoGrupoController@edit']);
    });

});

##### SITE #####
$router->group(['prefix' => '/', 'middleware' => ['auth']], function () use ($router) {

    $router->get('/', 'HomeController@index');
    $router->get('/home', 'HomeController@index');
    # log do laravel
    $router->get('/console/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

    Route::get('/getForeignKey', 'CodesController@getForeignKey');

    $router->get('compras', 'OrdemDeCompraController@compras')->middleware("needsPermission:compras_lembretes.list");

    $router->group(['middleware' => 'needsPermission:ordens_de_compra.list'], function () use ($router) {
        $router->resource('ordens-de-compra', 'OrdemDeCompraController');

        $router->get('/ordens-de-compra/detalhes/{id}', 'OrdemDeCompraController@detalhe')->middleware("needsPermission:ordens_de_compra.detalhes");
        $router->get('/ordens-de-compra/carrinho', 'OrdemDeCompraController@carrinho');
        $router->get('/ordens-de-compra/carrinho/indicar-contrato', 'OrdemDeCompraController@indicarContrato');
        $router->get('/ordens-de-compra/fechar-carrinho', 'OrdemDeCompraController@fechaCarrinho');
        $router->post('/ordens-de-compra/altera-item/{id}', 'OrdemDeCompraController@alteraItem');
        $router->post('/ordens-de-compra/upload-anexos/{id}', 'OrdemDeCompraController@uploadAnexos');
        $router->get('/ordens-de-compra/remover-anexo/{id}', 'OrdemDeCompraController@removerAnexo');
        $router->get('/ordens-de-compra/carrinho/remove-contrato', 'OrdemDeCompraController@removerContrato');
        $router->get('/ordens-de-compra/reabrir-ordem-de-compra/{id}', 'OrdemDeCompraController@reabrirOrdemDeCompra');
        $router->get('/ordens-de-compra/carrinho/alterar-quantidade/{id}', 'OrdemDeCompraController@alterarQuantidade');
        $router->get('/ordens-de-compra/carrinho/remover-item/{id}', 'OrdemDeCompraController@removerItem');
        $router->get('/ordens-de-compra/insumos-aprovados', 'OrdemDeCompraController@insumosAprovados');

        $router->get('/ordens-de-compra/detalhes-servicos/{servico_id}', 'OrdemDeCompraController@detalhesServicos')->middleware("needsPermission:ordens_de_compra.detalhes_servicos");
    });

    $router->group(['middleware' => 'needsPermission:site.dashboard'], function () use ($router) {
        $router->get('compras/jsonOrdemCompraDashboard', 'OrdemDeCompraController@jsonOrdemCompraDashboard');
        $router->get('compras/dashboard', 'OrdemDeCompraController@dashboard');
    });

    $router->group(['prefix' => 'compras'], function () use ($router) {
        $router->group(['middleware' => 'needsPermission:compras.geral'], function () use ($router) {
            $router->get('{planejamento}/insumos/{insumoGrupo}', 'OrdemDeCompraController@insumos')->name('compraInsumo');
            $router->get('{planejamento}/insumosJson', 'OrdemDeCompraController@insumosJson');
            $router->get('{planejamento}/insumosFilters', 'OrdemDeCompraController@insumosFilters');
            $router->post('{planejamento}/insumosAdd', 'OrdemDeCompraController@insumosAdd');

            $router->get('{planejamento}/obrasInsumos/{insumoGrupo}', 'OrdemDeCompraController@obrasInsumos');
            $router->get('{planejamento}/obrasInsumosFilters', 'OrdemDeCompraController@obrasInsumosFilters');
            $router->get('{planejamento}/obrasInsumosJson/{insumoGrupo}', 'OrdemDeCompraController@obrasInsumosJson');

            $router->get('insumos', 'OrdemDeCompraController@insumos')->name('compraInsumo');
            $router->get('insumosJson', 'OrdemDeCompraController@insumosJson');
            $router->get('insumosFilters', 'OrdemDeCompraController@insumosFilters');
            $router->post('insumosAdd', 'OrdemDeCompraController@insumosAdd');

            $router->get('obrasInsumos','OrdemDeCompraController@obrasInsumos');
            $router->get('obrasInsumosFilters', 'OrdemDeCompraController@obrasInsumosFilters');
            $router->get('obrasInsumosJson', 'OrdemDeCompraController@obrasInsumosJson');

            $router->get('trocaInsumos', 'OrdemDeCompraController@trocaInsumos');
            $router->get('{planejamento}/trocaInsumosFilters', 'OrdemDeCompraController@trocaInsumosFilters');
            $router->get('trocaInsumosJsonPai/{insumo}', 'OrdemDeCompraController@trocaInsumosJsonPai');
            $router->get('trocaInsumosJsonFilho', 'OrdemDeCompraController@trocaInsumosJsonFilho');
            $router->post('trocaInsumoAction', 'OrdemDeCompraController@trocaInsumoAction');
            $router->post('{obra}/{planejamento}/addCarrinho', 'OrdemDeCompraController@addCarrinho');
            $router->post('{obra}/addCarrinho', 'OrdemDeCompraController@addCarrinho');
            $router->get('removerInsumoPlanejamento/{planejamentoCompra}', 'OrdemDeCompraController@removerInsumoPlanejamento');
        });
    });

    $router->group(['prefix' => 'workflow'], function () use ($router) {
        $router->get('aprova-reprova', 'WorkflowController@aprovaReprova');
        $router->get('aprova-reprova-tudo', 'WorkflowController@aprovaReprovaTudo');
    });

    $router->get('create-realimentacao', 'RetroalimentacaoObraController@create');

    $router->get('filter-json-ordem-compra', 'OrdemDeCompraController@filterJsonOrdemCompra');

    $router->get('planejamentosByObra', 'PlanejamentoController@getPlanejamentosByObra');

    $router->get('planejamentos/lembretes', 'PlanejamentoController@lembretes');

    $router->get('workflow/aprova-reprova', 'WorkflowController@aprovaReprova');
    $router->get('workflow/aprova-reprova-tudo', 'WorkflowController@aprovaReprovaTudo');

    $router->post('quadro-de-concorrencia/criar', function (){
        # Validação básica
        validator(request()->all(),
            ['ordem_de_compra_itens'=>'required'],
            ['ordem_de_compra_itens.required'=>'É necessário escolher ao menos um item!']
        )->validate();
        dd('@TODO pegar ids e montar novo Q.C. ',request()->get('ordem_de_compra_itens'));
    });
    
    $router->get('/teste', function (){
        $grupos_mega = \App\Models\MegaInsumoGrupo::select([
            'GRU_IDE_ST_CODIGO',
            'GRU_IN_CODIGO',
            'GRU_ST_NOME',])
            ->where('gru_ide_st_codigo' , '07')
            ->first();
        dd($grupos_mega);
    });
});