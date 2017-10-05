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

// Notifications
$router->get('/notifications', 'NotificationController@index');
$router->post('/notifications/{id}/mark-as-read', 'NotificationController@markAsRead');

// Detalhes de workflow
$router->get('/workflow/detalhes', 'WorkflowController@detalhes');
$router->get('/workflow/redefinir', 'WorkflowController@redefinir');

// Solicitação de Insumo
$router->get('/solicitar-insumo', 'SolicitacaoInsumoController@create')
    ->name('solicitar_insumo.create');
$router->post('/solicitar-insumo', 'SolicitacaoInsumoController@store')
    ->name('solicitar_insumo.store');

##### Buscas #####
$router->get('/admin/catalogo-acordos/buscar/busca_insumos', ['as' => 'catalogo_contratos.busca_insumos', 'uses' => 'BuscarController@getInsumos']);

$router->get('/buscar/insumo-grupos', 'BuscarController@getInsumoGrupos')
    ->name('buscar.insumo-grupos');
$router->get('/buscar/insumos', 'BuscarController@getInsumos')
    ->name('buscar.insumos');
$router->get('/buscar/carteiras', 'BuscarController@getCarteiras')
    ->name('buscar.carteiras');
$router->get('/buscar/fornecedores', 'BuscarController@getFornecedores')
    ->name('buscar.fornecedores');
$router->get('/buscar/tipo-equalizacao-tecnicas', 'BuscarController@getTipoEqualizacaoTecnicas')
    ->name('buscar.tipo-equalizacao-tecnicas');

$router->get('/admin/users/busca', 'Admin\Manage\UsersController@busca');
$router->get('/getForeignKey', 'CodesController@getForeignKey');
$router->get('/busca-cidade', 'CodesController@buscaCidade');
$router->get('/busca-tipo-orcamentos', 'CodesController@buscaTipoOrcamento');
$router->get('tipos-equalizacoes-tecnicas/busca', 'TipoEqualizacaoTecnicaController@busca');
$router->get('/compras/buscar/planejamentos', ['as' => 'buscaplanejamentos.busca_planejamento', 'uses' => 'OrdemDeCompraController@buscaPlanejamentos']);
$router->get('/compras/buscar/insumogrupos', ['as' => 'buscainsumogrupos.busca_insumo', 'uses' => 'OrdemDeCompraController@buscaInsumoGrupos']);

$router->get('nomeclatura-mapas/json', ['uses' => 'Admin\NomeclaturaMapaController@json']);

##### ADMIN #####
$router->group(['prefix' => 'admin', 'middleware' => ['auth', 'needsPermission:dashboard.access']], function () use ($router) {

    # Home
    $router->get('/home', 'Admin\HomeController@index');
    $router->get('/', 'Admin\HomeController@index');

    # Importação de Planilhas de Orçamentos
    $router->group(['middleware' => 'needsPermission:orcamentos.import'], function () use ($router) {
        $router->get('orcamento/', ['as' => 'admin.orcamentos.indexImport', 'uses' => 'Admin\OrcamentoController@indexImport']);
        $router->post('orcamento/importar', ['as' => 'admin.orcamentos.importar', 'uses' => 'Admin\OrcamentoController@import']);
        $router->get('orcamento/importar/checkIn', ['as' => 'admin.orcamentos.checkIn', 'uses' => 'Admin\OrcamentoController@checkIn']);
        $router->post('orcamento/importar/save', ['as' => 'admin.orcamentos.save', 'uses' => 'Admin\OrcamentoController@save']);
        $router->get('orcamento/importar/selecionaCampos', 'Admin\OrcamentoController@selecionaCampos');
    });

    # Orçamentos
    $router->group(['middleware' => 'needsPermission:orcamentos.list'], function () use ($router) {
        $router->get('orcamentos', ['as' => 'admin.orcamentos.index', 'uses' => 'Admin\OrcamentoController@index']);
        $router->post('orcamentos', ['as' => 'admin.orcamentos.store', 'uses' => 'Admin\OrcamentoController@store']);
        $router->get('orcamentos/create', ['as' => 'admin.orcamentos.create', 'uses' => 'Admin\OrcamentoController@create']);
        $router->put('orcamentos/{orcamentos}', ['as' => 'admin.orcamentos.update', 'uses' => 'Admin\OrcamentoController@update']);
        $router->patch('orcamentos/{orcamentos}', ['as' => 'admin.orcamentos.update', 'uses' => 'Admin\OrcamentoController@update']);
        $router->delete('orcamentos/{orcamentos}', ['as' => 'admin.orcamentos.destroy', 'uses' => 'Admin\OrcamentoController@destroy']);
        $router->get('orcamentos/{orcamentos}', ['as' => 'admin.orcamentos.show', 'uses' => 'Admin\OrcamentoController@show']);
        $router->get('orcamentos/{orcamentos}/edit', ['as' => 'admin.orcamentos.edit', 'uses' => 'Admin\OrcamentoController@edit']);
    });

	# Pré Orçamentos
    $router->group(['middleware' => 'needsPermission:pre_orcamentos.list'], function () use ($router) {
        $router->get('pre_orcamentos', ['as' => 'admin.pre_orcamentos.index', 'uses' => 'Admin\PreOrcamentoController@index']);
		$router->get('pre_orcamentos/exportar_index', ['as' => 'admin.pre_orcamentos.exportar_index', 'uses' => 'Admin\PreOrcamentoController@exportarIndex']);
		$router->get('pre_orcamentos/exportar_plan', ['as' => 'admin.pre_orcamentos.exportar_plan', 'uses' => 'Admin\PreOrcamentoController@exportarPlan']);
        $router->post('pre_orcamentos', ['as' => 'admin.pre_orcamentos.store', 'uses' => 'Admin\PreOrcamentoController@store']);
        $router->get('pre_orcamentos/create', ['as' => 'admin.pre_orcamentos.create', 'uses' => 'Admin\PreOrcamentoController@create']);
        $router->put('pre_orcamentos/{pre_orcamentos}', ['as' => 'admin.pre_orcamentos.update', 'uses' => 'Admin\PreOrcamentoController@update']);
        $router->patch('pre_orcamentos/{pre_orcamentos}', ['as' => 'admin.pre_orcamentos.update', 'uses' => 'Admin\PreOrcamentoController@update']);
        $router->delete('pre_orcamentos/{pre_orcamentos}', ['as' => 'admin.pre_orcamentos.destroy', 'uses' => 'Admin\PreOrcamentoController@destroy']);
        $router->get('pre_orcamentos/{pre_orcamentos}', ['as' => 'admin.pre_orcamentos.show', 'uses' => 'Admin\PreOrcamentoController@show']);
        $router->get('pre_orcamentos/{pre_orcamentos}/edit', ['as' => 'admin.pre_orcamentos.edit', 'uses' => 'Admin\PreOrcamentoController@edit']);
    });

    # Topologia
    $router->group(['middleware' => 'needsPermission:topologia.list'], function () use ($router) {
        $router->get('topologia', ['as' => 'admin.topologia.index', 'uses' => 'Admin\TopologiaController@index']);
        $router->post('topologia', ['as' => 'admin.topologia.store', 'uses' => 'Admin\TopologiaController@store']);
        $router->get('topologia/create', ['as' => 'admin.topologia.create', 'uses' => 'Admin\TopologiaController@create']);
        $router->put('topologia/{topologia}', ['as' => 'admin.topologia.update', 'uses' => 'Admin\TopologiaController@update']);
        $router->patch('topologia/{topologia}', ['as' => 'admin.topologia.update', 'uses' => 'Admin\TopologiaController@update']);
        $router->delete('topologia/{topologia}', ['as' => 'admin.topologia.destroy', 'uses' => 'Admin\TopologiaController@destroy']);
        $router->get('topologia/{topologia}', ['as' => 'admin.topologia.show', 'uses' => 'Admin\TopologiaController@show']);
        $router->get('topologia/{topologia}/edit', ['as' => 'admin.topologia.edit', 'uses' => 'Admin\TopologiaController@edit']);
    });

    # Importação de Planilhas de Planejamentos
    $router->group(['middleware' => 'needsPermission:planejamento.import'], function () use ($router) {
        $router->get('planejamento/', ['as' => 'admin.planejamentos.indexImport', 'uses' => 'Admin\PlanejamentoController@indexImport']);
        $router->post('planejamento/importar', ['as' => 'admin.planejamentos.importar', 'uses' => 'Admin\PlanejamentoController@import']);
        $router->get('planejamento/importar/checkIn', ['as' => 'admin.planejamentos.checkIn', 'uses' => 'Admin\PlanejamentoController@checkIn']);
        $router->post('planejamento/importar/save', ['as' => 'admin.planejamentos.save', 'uses' => 'Admin\PlanejamentoController@save']);
        $router->get('planejamento/importar/selecionaCampos', 'Admin\PlanejamentoController@selecionaCampos');
    });

    # Planejamentos
    $router->group(['prefix' => 'planejamentos'], function () use ($router) {

        $router->group(['middleware' => 'needsPermission:cronograma_de_obras.list'], function () use ($router) {
            $router->get('atividade', ['as' => 'admin.planejamentos.index', 'uses' => 'Admin\PlanejamentoController@index']);
            $router->post('atividade', ['as' => 'admin.planejamentos.store', 'uses' => 'Admin\PlanejamentoController@store']);
            $router->get('atividade/create', ['as' => 'admin.planejamentos.create', 'uses' => 'Admin\PlanejamentoController@create']);
            $router->put('atividade/{planejamentos}', ['as' => 'admin.planejamentos.update', 'uses' => 'Admin\PlanejamentoController@update']);
            $router->patch('atividade/{planejamentos}', ['as' => 'admin.planejamentos.update', 'uses' => 'Admin\PlanejamentoController@update']);
            $router->delete('atividade/{planejamentos}', ['as' => 'admin.planejamentos.destroy', 'uses' => 'Admin\PlanejamentoController@destroy']);
            $router->get('atividade/{planejamentos}', ['as' => 'admin.planejamentos.show', 'uses' => 'Admin\PlanejamentoController@show'])
                ->middleware("needsPermission:cronograma_de_obras.view");
            $router->get('atividade/{planejamentos}/edit', ['as' => 'admin.planejamentos.edit', 'uses' => 'Admin\PlanejamentoController@edit'])
                ->middleware("needsPermission:cronograma_de_obras.edit");
            $router->get('atividade/grupos/{id}', 'Admin\PlanejamentoController@getGrupos');
            $router->get('atividade/servicos/{id}', 'Admin\PlanejamentoController@getServicos');
            $router->get('atividade/servico/insumo/relacionados', 'Admin\PlanejamentoController@GrupoRelacionados');
            $router->get('atividade/servico/insumo/{id}', 'Admin\PlanejamentoController@getServicoInsumos');
            $router->post('atividade/insumos', ['as' => 'admin.planejamentos.insumos', 'uses' => 'Admin\PlanejamentoController@planejamentoCompras']);
            $router->get('atividade/planejamentocompras/{id}', 'Admin\PlanejamentoController@destroyPlanejamentoCompra');
        });

        $router->get('planejamentoOrcamentos', ['as' => 'admin.planejamentoOrcamentos.index', 'uses' => 'Admin\PlanejamentoOrcamentoController@index']);
        $router->post('planejamentoOrcamentos', ['as' => 'admin.planejamentoOrcamentos.store', 'uses' => 'Admin\PlanejamentoOrcamentoController@store']);
        $router->get('planejamentoOrcamentos/create', ['as' => 'admin.planejamentoOrcamentos.create', 'uses' => 'Admin\PlanejamentoOrcamentoController@create']);
        $router->put('planejamentoOrcamentos/{planejamentoOrcamentos}', ['as' => 'admin.planejamentoOrcamentos.update', 'uses' => 'Admin\PlanejamentoOrcamentoController@update']);
        $router->patch('planejamentoOrcamentos/{planejamentoOrcamentos}', ['as' => 'admin.planejamentoOrcamentos.update', 'uses' => 'Admin\PlanejamentoOrcamentoController@update']);
        $router->delete('planejamentoOrcamentos/{planejamentoOrcamentos}', ['as' => 'admin.planejamentoOrcamentos.destroy', 'uses' => 'Admin\PlanejamentoOrcamentoController@destroy']);
        $router->get('planejamentoOrcamentos/{planejamentoOrcamentos}', ['as' => 'admin.planejamentoOrcamentos.show', 'uses' => 'Admin\PlanejamentoOrcamentoController@show']);
        $router->get('planejamentoOrcamentos/{planejamentoOrcamentos}/edit', ['as' => 'admin.planejamentoOrcamentos.edit', 'uses' => 'Admin\PlanejamentoOrcamentoController@edit']);
        $router->get('planejamentoOrcamentos/orcamentos/relacionados', 'Admin\PlanejamentoOrcamentoController@GrupoRelacionados');
        $router->get('planejamentoOrcamentos/planejamento/{id}', 'Admin\PlanejamentoOrcamentoController@getPlanejamentos');
        $router->get('planejamentoOrcamentos/orcamento/{id}', 'Admin\PlanejamentoOrcamentoController@getOrcamentos');
        $router->get('planejamentoOrcamentos/planejamento/orcamento/insumo_grupos', 'Admin\PlanejamentoOrcamentoController@getGrupoInsumos');
        $router->get('planejamentoOrcamentos/planejamento/orcamento/insumo/insumo_grupos', 'Admin\PlanejamentoOrcamentoController@getGrupoInsumoRelacionados');
        $router->get('planejamentoOrcamentos/orcamentos/desvincular', 'Admin\PlanejamentoOrcamentoController@desvincular');
        $router->get('planejamentoOrcamentos/sem-planejamento/view/{obra}', ['as' => 'admin.planejamentoOrcamentos.semplanejamentoview', 'uses' => 'Admin\PlanejamentoOrcamentoController@semPlanejamentoView']);

    });

	#Medição Física
    $router->group(['prefix' => 'medicao_fisicas','middleware' => 'needsPermission:medicao_fisicas.list'], function($router) {
        $router->get('',['as' => 'admin.medicao_fisicas.index', 'uses' => 'Admin\MedicaoFisicaController@index']);
		$router->post('medicao_fisicas', ['as' => 'admin.medicao_fisicas.store', 'uses' => 'Admin\MedicaoFisicaController@store']);
        $router->get('create', ['as' => 'admin.medicao_fisicas.create', 'uses' => 'Admin\MedicaoFisicaController@create']);
        $router->get('/tarefas-por-obra','Admin\MedicaoFisicaController@tarefasPorObra');
		$router->get('/{medicao_fisicas}',['as' => 'admin.medicao_fisicas.show', 'uses' => 'Admin\MedicaoFisicaController@show'])
			->middleware('needsPermission:medicao_fisicas.show');
        $router->get('/{medicao_fisicas}/editar',['as' => 'admin.medicao_fisicas.edit', 'uses' => 'Admin\MedicaoFisicaController@edit']);
        $router->patch('/{medicao_fisicas}/update',['as' => 'admin.medicao_fisicas.update', 'uses' => 'Admin\MedicaoFisicaController@update']);
		$router->delete('medicao_fisicas/{medicao_fisicas}', ['as' => 'admin.medicao_fisicas.destroy', 'uses' => 'Admin\MedicaoFisicaController@destroy']);
    });

	# Importação de Planilhas de cronograma Físico
    $router->group(['middleware' => 'needsPermission:cronogramaFisicos.import'], function () use ($router) {
        $router->get('cronogramaFisico/', ['as' => 'admin.cronogramaFisicos.indexImport', 'uses' => 'Admin\CronogramaFisicoController@indexImport']);
        $router->post('cronogramaFisico/importar', ['as' => 'admin.cronogramaFisicos.importar', 'uses' => 'Admin\CronogramaFisicoController@import']);
        $router->get('cronogramaFisico/importar/checkIn', ['as' => 'admin.cronogramaFisicos.checkIn', 'uses' => 'Admin\CronogramaFisicoController@checkIn']);
        $router->post('cronogramaFisico/importar/save', ['as' => 'admin.cronogramaFisicos.save', 'uses' => 'Admin\CronogramaFisicoController@save']);
        $router->get('cronogramaFisico/importar/selecionaCampos', 'Admin\CronogramaFisicoController@selecionaCampos');
    });

    # Cronograma Físico
    $router->group(['prefix' => 'cronogramaFisicos'], function () use ($router) {
        $router->group(['middleware' => 'needsPermission:cronogramaFisicos.list'], function () use ($router) {
			$router->get('meses-por-obra','Admin\CronogramaFisicoController@mesesPorObra');
			$router->get('semanal-tabelas','Admin\CronogramaFisicoController@semanalCarregarTabelas');
			$router->get('semanal-graficos','Admin\CronogramaFisicoController@semanalCarregarGraficos');
			$router->get('mensal-tabelas','Admin\CronogramaFisicoController@mensalCarregarTabelas');
			$router->get('mensal-graficos','Admin\CronogramaFisicoController@mensalCarregarGraficos');
			$router->get('relSemanal', ['as' => 'admin.cronograma_fisicos.relSemanal', 'uses' => 'Admin\CronogramaFisicoController@relSemanal']);
			$router->get('relMensal', ['as' => 'admin.cronograma_fisicos.relMensal', 'uses' => 'Admin\CronogramaFisicoController@relMensal']);
            $router->get('atividade', ['as' => 'admin.cronograma_fisicos.index', 'uses' => 'Admin\CronogramaFisicoController@index']);
            $router->post('atividade', ['as' => 'admin.cronograma_fisicos.store', 'uses' => 'Admin\CronogramaFisicoController@store']);
            $router->get('atividade/create', ['as' => 'admin.cronograma_fisicos.create', 'uses' => 'Admin\CronogramaFisicoController@create']);
            $router->put('atividade/{cronograma_fisicos}', ['as' => 'admin.cronograma_fisicos.update', 'uses' => 'Admin\CronogramaFisicoController@update']);
            $router->patch('atividade/{cronograma_fisicos}', ['as' => 'admin.cronograma_fisicos.update', 'uses' => 'Admin\CronogramaFisicoController@update']);
            $router->delete('atividade/{cronograma_fisicos}', ['as' => 'admin.cronograma_fisicos.destroy', 'uses' => 'Admin\CronogramaFisicoController@destroy']);
            $router->get('atividade/{cronograma_fisicos}', ['as' => 'admin.cronograma_fisicos.show', 'uses' => 'Admin\CronogramaFisicoController@show']);
            $router->get('atividade/{cronograma_fisicos}/edit', ['as' => 'admin.cronograma_fisicos.edit', 'uses' => 'Admin\CronogramaFisicoController@edit']);
        });

    });

	# Importação de Planilhas de Levantamentos
    $router->group(['middleware' => 'needsPermission:levantamentos.import'], function () use ($router) {
        $router->get('levantamento/', ['as' => 'admin.levantamentos.indexImport', 'uses' => 'Admin\LevantamentoController@indexImport']);
        $router->post('levantamento/importar', ['as' => 'admin.levantamentos.importar', 'uses' => 'Admin\LevantamentoController@import']);
        $router->get('levantamento/importar/checkIn', ['as' => 'admin.levantamentos.checkIn', 'uses' => 'Admin\LevantamentoController@checkIn']);
        $router->post('levantamento/importar/save', ['as' => 'admin.levantamentos.save', 'uses' => 'Admin\LevantamentoController@save']);
        $router->get('levantamento/importar/selecionaCampos', 'Admin\LevantamentoController@selecionaCampos');
    });

    # Levantamentos
    $router->group(['prefix' => 'levantamentos'], function () use ($router) {
        $router->group(['middleware' => 'needsPermission:levantamentos.list'], function () use ($router) {
            $router->get('atividade', ['as' => 'admin.levantamentos.index', 'uses' => 'Admin\LevantamentoController@index']);
            $router->post('atividade', ['as' => 'admin.levantamentos.store', 'uses' => 'Admin\LevantamentoController@store']);
            $router->get('atividade/create', ['as' => 'admin.levantamentos.create', 'uses' => 'Admin\LevantamentoController@create']);
            $router->put('atividade/{levantamentos}', ['as' => 'admin.levantamentos.update', 'uses' => 'Admin\LevantamentoController@update']);
            $router->patch('atividade/{levantamentos}', ['as' => 'admin.levantamentos.update', 'uses' => 'Admin\LevantamentoController@update']);
            $router->delete('atividade/{levantamentos}', ['as' => 'admin.levantamentos.destroy', 'uses' => 'Admin\LevantamentoController@destroy']);
            $router->get('atividade/{levantamentos}', ['as' => 'admin.levantamentos.show', 'uses' => 'Admin\LevantamentoController@show']);
            $router->get('atividade/{levantamentos}/edit', ['as' => 'admin.levantamentos.edit', 'uses' => 'Admin\LevantamentoController@edit']);
        });
    });

	# Tipos de Levantamentos
    $router->group(['middleware' => 'needsPermission:tipoLevantamentos.list'], function () use ($router) {
        $router->get('tipoLevantamentos', ['as' => 'admin.tipo_levantamentos.index', 'uses' => 'Admin\TipoLevantamentoController@index']);
        $router->post('tipoLevantamentos', ['as' => 'admin.tipo_levantamentos.store', 'uses' => 'Admin\TipoLevantamentoController@store']);
        $router->get('tipoLevantamentos/create', ['as' => 'admin.tipo_levantamentos.create', 'uses' => 'Admin\TipoLevantamentoController@create'])
			->middleware("needsPermission:tipoLevantamentos.create");
        $router->put('tipoLevantamentos/{tipoLevantamentos}', ['as' => 'admin.tipo_levantamentos.update', 'uses' => 'Admin\TipoLevantamentoController@update']);
        $router->patch('tipoLevantamentos/{tipoLevantamentos}', ['as' => 'admin.tipo_levantamentos.update', 'uses' => 'Admin\TipoLevantamentoController@update']);
        $router->delete('tipoLevantamentos/{tipoLevantamentos}', ['as' => 'admin.tipo_levantamentos.destroy', 'uses' => 'Admin\TipoLevantamentoController@destroy'])
			->middleware("needsPermission:tipoLevantamentos.delete");
        $router->get('tipoLevantamentos/{tipoLevantamentos}', ['as' => 'admin.tipo_levantamentos.show', 'uses' => 'Admin\TipoLevantamentoController@show']);
        $router->get('tipoLevantamentos/{tipoLevantamentos}/edit', ['as' => 'admin.tipo_levantamentos.edit', 'uses' => 'Admin\TipoLevantamentoController@edit'])
			->middleware("needsPermission:tipoLevantamentos.edit");
    });

	# Máscara Padrão
    $router->group(['middleware' => 'needsPermission:mascara_padrao.list'], function () use ($router) {
        $router->get('mascara_padrao', ['as' => 'admin.mascara_padrao.index', 'uses' => 'Admin\MascaraPadraoController@index']);
        $router->post('mascara_padrao', ['as' => 'admin.mascara_padrao.store', 'uses' => 'Admin\MascaraPadraoController@store']);
        $router->get('mascara_padrao/create', ['as' => 'admin.mascara_padrao.create', 'uses' => 'Admin\MascaraPadraoController@create'])
            ->middleware("needsPermission:mascara_padrao.create");;
        $router->put('mascara_padrao/{mascara_padrao}', ['as' => 'admin.mascara_padrao.update', 'uses' => 'Admin\MascaraPadraoController@update']);
        $router->patch('mascara_padrao/{mascara_padrao}', ['as' => 'admin.mascara_padrao.update', 'uses' => 'Admin\MascaraPadraoController@update']);
        $router->delete('mascara_padrao/{mascara_padrao}', ['as' => 'admin.mascara_padrao.destroy', 'uses' => 'Admin\MascaraPadraoController@destroy'])
            ->middleware("needsPermission:mascara_padrao.delete");;
        $router->get('mascara_padrao/{mascara_padrao}', ['as' => 'admin.mascara_padrao.show', 'uses' => 'Admin\MascaraPadraoController@show']);
        $router->get('mascara_padrao/{mascara_padrao}/edit', ['as' => 'admin.mascara_padrao.edit', 'uses' => 'Admin\MascaraPadraoController@edit'])
            ->middleware("needsPermission:mascara_padrao.edit");
    });

	# Tarefa Padrão
    $router->group(['middleware' => 'needsPermission:tarefa_padrao.list'], function () use ($router) {
        $router->get('tarefa_padrao', ['as' => 'admin.tarefa_padrao.index', 'uses' => 'Admin\TarefaPadraoController@index']);
        $router->post('tarefa_padrao', ['as' => 'admin.tarefa_padrao.store', 'uses' => 'Admin\TarefaPadraoController@store']);
        $router->get('tarefa_padrao/create', ['as' => 'admin.tarefa_padrao.create', 'uses' => 'Admin\TarefaPadraoController@create'])
            ->middleware("needsPermission:tarefa_padrao.create");;
        $router->put('tarefa_padrao/{tarefa_padrao}', ['as' => 'admin.tarefa_padrao.update', 'uses' => 'Admin\TarefaPadraoController@update']);
        $router->patch('tarefa_padrao/{tarefa_padrao}', ['as' => 'admin.tarefa_padrao.update', 'uses' => 'Admin\TarefaPadraoController@update']);
        $router->delete('tarefa_padrao/{tarefa_padrao}', ['as' => 'admin.tarefa_padrao.destroy', 'uses' => 'Admin\TarefaPadraoController@destroy'])
            ->middleware("needsPermission:tarefa_padrao.delete");;
        $router->get('tarefa_padrao/{tarefa_padrao}', ['as' => 'admin.tarefa_padrao.show', 'uses' => 'Admin\TarefaPadraoController@show']);
        $router->get('tarefa_padrao/{tarefa_padrao}/edit', ['as' => 'admin.tarefa_padrao.edit', 'uses' => 'Admin\TarefaPadraoController@edit'])
            ->middleware("needsPermission:tarefa_padrao.edit");
    });

	# Estruturas
    $router->group(['middleware' => 'needsPermission:estruturas.list'], function () use ($router) {
        $router->get('estruturas', ['as' => 'admin.estruturas.index', 'uses' => 'Admin\EstruturaController@index']);
        $router->post('estruturas', ['as' => 'admin.estruturas.store', 'uses' => 'Admin\EstruturaController@store']);
        $router->get('estruturas/create', ['as' => 'admin.estruturas.create', 'uses' => 'Admin\EstruturaController@create'])
            ->middleware("needsPermission:estruturas.create");;
        $router->put('estruturas/{estruturas}', ['as' => 'admin.estruturas.update', 'uses' => 'Admin\EstruturaController@update']);
        $router->patch('estruturas/{estruturas}', ['as' => 'admin.estruturas.update', 'uses' => 'Admin\EstruturaController@update']);
        $router->delete('estruturas/{estruturas}', ['as' => 'admin.estruturas.destroy', 'uses' => 'Admin\EstruturaController@destroy'])
            ->middleware("needsPermission:estruturas.delete");;
        $router->get('estruturas/{estruturas}', ['as' => 'admin.estruturas.show', 'uses' => 'Admin\EstruturaController@show']);
        $router->get('estruturas/{estruturas}/edit', ['as' => 'admin.estruturas.edit', 'uses' => 'Admin\EstruturaController@edit'])
            ->middleware("needsPermission:estruturas.edit");
    });

	# Mascara Insumos
    /*$router->group(['middleware' => 'needsPermission:mascaraInsumos.list'], function () use ($router) {
        $router->get('mascara_insumos', ['as' => 'admin.mascara_insumos.index', 'uses' => 'Admin\MascaraInsumoController@index']);
        $router->post('mascara_insumos', ['as' => 'admin.mascara_insumos.store', 'uses' => 'Admin\MascaraInsumoController@store']);
        $router->get('mascara_insumos/create', ['as' => 'admin.mascara_insumos.create', 'uses' => 'Admin\MascaraInsumoController@create'])
            ->middleware("needsPermission:mascaraInsumos.create");;
        $router->put('mascara_insumos/{mascara_insumos}', ['as' => 'admin.mascara_insumos.update', 'uses' => 'Admin\MascaraInsumoController@update']);
        $router->patch('mascara_insumos/{mascara_insumos}', ['as' => 'admin.mascara_insumos.update', 'uses' => 'Admin\MascaraInsumoController@update']);
        $router->delete('mascara_insumos/{mascara_insumos}', ['as' => 'admin.mascara_insumos.destroy', 'uses' => 'Admin\MascaraInsumoController@destroy'])
            ->middleware("needsPermission:mascaraInsumos.delete");;
        $router->get('mascara_insumos/{mascara_insumos}', ['as' => 'admin.mascara_insumos.show', 'uses' => 'Admin\MascaraInsumoController@show']);
        $router->get('mascara_insumos/{mascara_insumos}/edit', ['as' => 'admin.mascara_insumos.edit', 'uses' => 'Admin\MascaraInsumoController@edit'])
            ->middleware("needsPermission:mascaraInsumos.edit");
    });*/

    # Lembretes
    $router->group(['middleware' => 'needsPermission:lembretes.list'], function () use ($router) {
        $router->get('lembretes/data-minima', 'Admin\LembreteController@lembreteDataMinima');
        $router->get('lembretes', ['as' => 'admin.lembretes.index', 'uses' => 'Admin\LembreteController@index']);
        $router->post('lembretes', ['as' => 'admin.lembretes.store', 'uses' => 'Admin\LembreteController@store']);
        $router->get('lembretes/create', ['as' => 'admin.lembretes.create', 'uses' => 'Admin\LembreteController@create'])
            ->middleware("needsPermission:lembretes.create");
        $router->put('lembretes/{lembretes}', ['as' => 'admin.lembretes.update', 'uses' => 'Admin\LembreteController@update']);
        $router->patch('lembretes/{lembretes}', ['as' => 'admin.lembretes.update', 'uses' => 'Admin\LembreteController@update']);
        $router->delete('lembretes/{lembretes}', ['as' => 'admin.lembretes.destroy', 'uses' => 'Admin\LembreteController@destroy']);
        $router->get('lembretes/{lembretes}', ['as' => 'admin.lembretes.show', 'uses' => 'Admin\LembreteController@show'])
            ->middleware("needsPermission:lembretes.view");
        $router->get('lembretes/{lembretes}/edit', ['as' => 'admin.lembretes.edit', 'uses' => 'Admin\LembreteController@edit'])
            ->middleware("needsPermission:lembretes.edit");
        $router->get('lembretes/filtro/busca', ['as' => 'admin.lembretes.busca', 'uses' => 'Admin\LembreteController@busca']);
    });

    # Template de importação de planilha
    $router->group(['middleware' => 'needsPermission:template_planilhas.list'], function () use ($router) {
        $router->get('templatePlanilhas', ['as' => 'admin.templatePlanilhas.index', 'uses' => 'Admin\TemplatePlanilhaController@index']);
        $router->post('templatePlanilhas', ['as' => 'admin.templatePlanilhas.store', 'uses' => 'Admin\TemplatePlanilhaController@store']);
        $router->get('templatePlanilhas/create', ['as' => 'admin.templatePlanilhas.create', 'uses' => 'Admin\TemplatePlanilhaController@create']);
        $router->put('templatePlanilhas/{templatePlanilhas}', ['as' => 'admin.templatePlanilhas.update', 'uses' => 'Admin\TemplatePlanilhaController@update']);
        $router->patch('templatePlanilhas/{templatePlanilhas}', ['as' => 'admin.templatePlanilhas.update', 'uses' => 'Admin\TemplatePlanilhaController@update']);
        $router->delete('templatePlanilhas/{templatePlanilhas}', ['as' => 'admin.templatePlanilhas.destroy', 'uses' => 'Admin\TemplatePlanilhaController@destroy']);
        $router->get('templatePlanilhas/{templatePlanilhas}', ['as' => 'admin.templatePlanilhas.show', 'uses' => 'Admin\TemplatePlanilhaController@show']);
        $router->get('templatePlanilhas/{templatePlanilhas}/edit', ['as' => 'admin.templatePlanilhas.edit', 'uses' => 'Admin\TemplatePlanilhaController@edit']);
    });

    #Cronograma por obra
    $router->get('planejamentoCronogramas', ['as' => 'admin.planejamentoCronogramas.index', 'uses' => 'Admin\PlanejamentoCronogramaController@index'])
        ->middleware("needsPermission:cronograma_por_obras.list");

    # Comprador de Insumos
    $router->group(['middleware' => 'needsPermission:compradorInsumos.list'], function () use ($router) {
        $router->get('compradorInsumos', ['as' => 'admin.compradorInsumos.index', 'uses' => 'Admin\CompradorInsumoController@index']);
        $router->post('compradorInsumos', ['as' => 'admin.compradorInsumos.store', 'uses' => 'Admin\CompradorInsumoController@store']);
        $router->get('compradorInsumos/create', ['as' => 'admin.compradorInsumos.create', 'uses' => 'Admin\CompradorInsumoController@create'])
            ->middleware("needsPermission:compradorInsumos.create");
        $router->put('compradorInsumos/{compradorInsumos}', ['as' => 'admin.compradorInsumos.update', 'uses' => 'Admin\CompradorInsumoController@update']);
        $router->patch('compradorInsumos/{compradorInsumos}', ['as' => 'admin.compradorInsumos.update', 'uses' => 'Admin\CompradorInsumoController@update']);
        $router->delete('compradorInsumos/{compradorInsumos}', ['as' => 'admin.compradorInsumos.destroy', 'uses' => 'Admin\CompradorInsumoController@destroy'])
            ->middleware("needsPermission:compradorInsumos.delete");
        $router->get('compradorInsumos/{compradorInsumos}', ['as' => 'admin.compradorInsumos.show', 'uses' => 'Admin\CompradorInsumoController@show']);
        $router->get('compradorInsumos/{compradorInsumos}/edit', ['as' => 'admin.compradorInsumos.edit', 'uses' => 'Admin\CompradorInsumoController@edit'])
            ->middleware("needsPermission:compradorInsumos.edit");
        $router->get('compradorInsumos/insumos/{id}', 'Admin\CompradorInsumoController@getInsumos');
        $router->get('compradorInsumos/delete-bloco/view', ['as' => 'admin.compradorInsumos.deleteblocoview', 'uses' => 'Admin\CompradorInsumoController@deleteBlocoView'])
            ->middleware("needsPermission:compradorInsumos.deleteBlocoView");
        $router->get('compradorInsumos/delete-bloco/view/delete', ['as' => 'admin.compradorInsumos.deletebloco', 'uses' => 'Admin\CompradorInsumoController@deleteBloco']);
        $router->get('compradorInsumos/delete-bloco/view/delete/{id}', 'Admin\CompradorInsumoController@buscaGrupoInsumo');
		$router->get('compradorInsumos/sem-insumo/view', ['as' => 'admin.compradorInsumos.seminsumoview', 'uses' => 'Admin\CompradorInsumoController@semInsumoView'])
            ->middleware("needsPermission:compradorInsumos.semInsumoView");
    });

	# Máscara Padrão Insumos
    $router->group(['middleware' => 'needsPermission:mascara_padrao_insumos.list'], function () use ($router) {
        $router->get('mascara_padrao_insumos', ['as' => 'admin.mascara_padrao_insumos.index', 'uses' => 'Admin\MascaraPadraoInsumoController@index']);
        $router->post('mascara_padrao_insumos', ['as' => 'admin.mascara_padrao_insumos.store', 'uses' => 'Admin\MascaraPadraoInsumoController@store']);
        $router->get('mascara_padrao_insumos/create', ['as' => 'admin.mascara_padrao_insumos.create', 'uses' => 'Admin\MascaraPadraoInsumoController@create'])
            ->middleware("needsPermission:mascara_padrao_insumos.create");
        $router->put('mascara_padrao_insumos/{insumos}', ['as' => 'admin.mascara_padrao_insumos.update', 'uses' => 'Admin\MascaraPadraoInsumoController@update']);
        $router->patch('mascara_padrao_insumos/{insumos}', ['as' => 'admin.mascara_padrao_insumos.update', 'uses' => 'Admin\MascaraPadraoInsumoController@update']);
        $router->delete('mascara_padrao_insumos/{insumos}', ['as' => 'admin.mascara_padrao_insumos.destroy', 'uses' => 'Admin\MascaraPadraoInsumoController@destroy'])
            ->middleware("needsPermission:mascara_padrao_insumos.delete");
        $router->get('mascara_padrao_insumos/{insumos}', ['as' => 'admin.mascara_padrao_insumos.show', 'uses' => 'Admin\MascaraPadraoInsumoController@show']);
        $router->get('mascara_padrao_insumos/{insumos}/edit', ['as' => 'admin.mascara_padrao_insumos.edit', 'uses' => 'Admin\MascaraPadraoInsumoController@edit'])
            ->middleware("needsPermission:mascara_padrao_insumos.edit");
        $router->get('mascara_padrao_insumos/insumos/{id}', 'Admin\MascaraPadraoInsumoController@getInsumos');
        $router->get('mascara_padrao_insumos/delete-bloco/view', ['as' => 'admin.mascara_padrao_insumos.deleteblocoview', 'uses' => 'Admin\MascaraPadraoInsumoController@deleteBlocoView'])
            ->middleware("needsPermission:mascara_padrao_insumos.deleteBlocoView");
        $router->get('mascara_padrao_insumos/delete-bloco/view/delete', ['as' => 'admin.mascara_padrao_insumos.deletebloco', 'uses' => 'Admin\MascaraPadraoInsumoController@deleteBloco']);
        $router->get('mascara_padrao_insumos/delete-bloco/view/delete/{id}', 'Admin\MascaraPadraoInsumoController@buscaGrupoInsumo');
		$router->get('mascara_padrao_insumos/sem-insumo/view', ['as' => 'admin.mascara_padrao_insumos.seminsumoview', 'uses' => 'Admin\MascaraPadraoInsumoController@semInsumoView'])
            ->middleware("needsPermission:mascara_padrao_insumos.semInsumoView");
		//$router->get('mascara_padrao_insumos/grupos/{id}', 'MascaraPadraoInsumoController@getGrupos');
        //$router->get('mascara_padrao_insumos/servicos/{id}', 'MascaraPadraoInsumoController@getServicos');
    });

	# Tarefa Padrão / Máscara Padrão
    $router->group(['middleware' => 'needsPermission:tarefa_mascaras.list'], function () use ($router) {
		$router->get('tarefa_mascaras', ['as' => 'admin.tarefa_mascaras.index', 'uses' => 'Admin\TarefaMascarasController@index']);
		$router->post('tarefa_mascaras', ['as' => 'admin.tarefa_mascaras.store', 'uses' => 'Admin\TarefaMascarasController@store']);
		$router->get('tarefa_mascaras/create', ['as' => 'admin.tarefa_mascaras.create', 'uses' => 'Admin\TarefaMascarasController@create']);
		$router->put('tarefa_mascaras/{tarefaMascaras}', ['as' => 'admin.tarefa_mascaras.update', 'uses' => 'Admin\TarefaMascarasController@update']);
		$router->patch('tarefa_mascaras/{tarefaMascaras}', ['as' => 'admin.tarefa_mascaras.update', 'uses' => 'Admin\TarefaMascarasController@update']);
		$router->delete('tarefa_mascaras/{tarefaMascaras}', ['as' => 'admin.tarefa_mascaras.destroy', 'uses' => 'Admin\TarefaMascarasController@destroy']);
		$router->get('tarefa_mascaras/{tarefaMascaras}', ['as' => 'admin.tarefa_mascaras.show', 'uses' => 'Admin\TarefaMascarasController@show']);
		$router->get('tarefa_mascaras/{tarefaMascaras}/edit', ['as' => 'admin.tarefa_mascaras.edit', 'uses' => 'Admin\TarefaMascarasController@edit']);
		$router->get('tarefa_mascaras/mascaras/relacionados', 'Admin\TarefaMascarasController@GrupoRelacionados');
		$router->get('tarefa_mascaras/tarefa/{id}', 'Admin\TarefaMascarasController@getTarefas');
		$router->get('tarefa_mascaras/mascara/{id}', 'Admin\TarefaMascarasController@getOrcamentos');
		$router->get('tarefa_mascaras/tarefa/mascara/insumo_grupos', 'Admin\TarefaMascarasController@getGrupoInsumos');
		$router->get('tarefa_mascaras/tarefa/mascara/insumo/insumo_grupos', 'Admin\TarefaMascarasController@getGrupoInsumoRelacionados');
		$router->get('tarefa_mascaras/mascaras/desvincular', 'Admin\TarefaMascarasController@desvincular');
		$router->get('tarefa_mascaras/sem-tarefa/view/{obra}', ['as' => 'admin.tarefa_mascaras.semplanejamentoview', 'uses' => 'Admin\TarefaMascarasController@semPlanejamentoView']);
	});

	# Carteira de Insumos
    $router->group(['middleware' => 'needsPermission:carteiraInsumos.list'], function () use ($router) {
        $router->get('carteiraInsumos', ['as' => 'admin.carteiraInsumos.index', 'uses' => 'Admin\CarteiraInsumoController@index']);
        $router->post('carteiraInsumos', ['as' => 'admin.carteiraInsumos.store', 'uses' => 'Admin\CarteiraInsumoController@store']);
        $router->get('carteiraInsumos/create', ['as' => 'admin.carteiraInsumos.create', 'uses' => 'Admin\CarteiraInsumoController@create'])
            ->middleware("needsPermission:carteiraInsumos.create");
        $router->put('carteiraInsumos/{carteiraInsumos}', ['as' => 'admin.carteiraInsumos.update', 'uses' => 'Admin\CarteiraInsumoController@update']);
        $router->patch('carteiraInsumos/{carteiraInsumos}', ['as' => 'admin.carteiraInsumos.update', 'uses' => 'Admin\CarteiraInsumoController@update']);
        $router->delete('carteiraInsumos/{carteiraInsumos}', ['as' => 'admin.carteiraInsumos.destroy', 'uses' => 'Admin\CarteiraInsumoController@destroy'])
            ->middleware("needsPermission:carteiraInsumos.delete");
		$router->get('carteiraInsumos/{carteiraInsumos}', ['as' => 'admin.carteiraInsumos.show', 'uses' => 'Admin\CarteiraInsumoController@show']);
        $router->get('carteiraInsumos/{carteiraInsumos}/edit', ['as' => 'admin.carteiraInsumos.edit', 'uses' => 'Admin\CarteiraInsumoController@edit'])
            ->middleware("needsPermission:carteiraInsumos.edit");
        $router->get('carteiraInsumos/insumos/{id}', 'Admin\CarteiraInsumoController@getInsumos');
        $router->get('carteiraInsumos/delete-bloco/view', ['as' => 'admin.carteiraInsumos.deleteblocoview', 'uses' => 'Admin\CarteiraInsumoController@deleteBlocoView'])
            ->middleware("needsPermission:carteiraInsumos.deleteBlocoView");
        $router->get('carteiraInsumos/delete-bloco/view/delete', ['as' => 'admin.carteiraInsumos.deletebloco', 'uses' => 'Admin\CarteiraInsumoController@deleteBloco']);
        $router->get('carteiraInsumos/delete-bloco/view/delete/{id}', 'Admin\CarteiraInsumoController@buscaGrupoInsumo');
		$router->get('carteiraInsumos/sem-carteira/view', ['as' => 'admin.carteiraInsumos.semcarteiraview', 'uses' => 'Admin\CarteiraInsumoController@semCarteiraView'])
            ->middleware("needsPermission:carteiraInsumos.semCarteiraView");
	});

    # Manage users
    $router->group(['middleware' => 'needsPermission:users.list'], function () use ($router) {
        $router->resource('users', 'Admin\Manage\UsersController');

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

    # Workflow
    $router->group(['prefix' => 'workflow'], function () use ($router) {
        $router->group(['middleware' => 'needsPermission:motivos_reprovacao.list'], function () use ($router) {
            $router->resource('workflowReprovacaoMotivos', 'OrdemDeCompraController');
            $router->get('reprovacao-motivos', ['as' => 'admin.workflowReprovacaoMotivos.index', 'uses' => 'Admin\WorkflowReprovacaoMotivoController@index']);
            $router->post('reprovacao-motivos', ['as' => 'admin.workflowReprovacaoMotivos.store', 'uses' => 'Admin\WorkflowReprovacaoMotivoController@store']);
            $router->get('reprovacao-motivos/create', ['as' => 'admin.workflowReprovacaoMotivos.create', 'uses' => 'Admin\WorkflowReprovacaoMotivoController@create'])
                ->middleware("needsPermission:motivos_reprovacao.create");
            $router->put('reprovacao-motivos/{workflowReprovacaoMotivos}', ['as' => 'admin.workflowReprovacaoMotivos.update', 'uses' => 'Admin\WorkflowReprovacaoMotivoController@update']);
            $router->patch('reprovacao-motivos/{workflowReprovacaoMotivos}', ['as' => 'admin.workflowReprovacaoMotivos.update', 'uses' => 'Admin\WorkflowReprovacaoMotivoController@update']);
            $router->delete('reprovacao-motivos/{workflowReprovacaoMotivos}', ['as' => 'admin.workflowReprovacaoMotivos.destroy', 'uses' => 'Admin\WorkflowReprovacaoMotivoController@destroy']);
            $router->get('reprovacao-motivos/{workflowReprovacaoMotivos}', ['as' => 'admin.workflowReprovacaoMotivos.show', 'uses' => 'Admin\WorkflowReprovacaoMotivoController@show'])
                ->middleware("needsPermission:motivos_reprovacao.view");
            $router->get('reprovacao-motivos/{workflowReprovacaoMotivos}/edit', ['as' => 'admin.workflowReprovacaoMotivos.edit', 'uses' => 'Admin\WorkflowReprovacaoMotivoController@edit'])
                ->middleware("needsPermission:motivos_reprovacao.edit");
        });

        $router->group(['middleware' => 'needsPermission:alcadas.list'], function () use ($router) {
            $router->get('workflow-alcadas', ['as' => 'admin.workflowAlcadas.index', 'uses' => 'Admin\WorkflowAlcadaController@index']);
            $router->post('workflow-alcadas', ['as' => 'admin.workflowAlcadas.store', 'uses' => 'Admin\WorkflowAlcadaController@store']);
            $router->get('workflow-alcadas/create', ['as' => 'admin.workflowAlcadas.create', 'uses' => 'Admin\WorkflowAlcadaController@create'])
                ->middleware("needsPermission:alcadas.create");
            $router->put('workflow-alcadas/{workflowAlcadas}', ['as' => 'admin.workflowAlcadas.update', 'uses' => 'Admin\WorkflowAlcadaController@update']);
            $router->patch('workflow-alcadas/{workflowAlcadas}', ['as' => 'admin.workflowAlcadas.update', 'uses' => 'Admin\WorkflowAlcadaController@update']);
            $router->delete('workflow-alcadas/{workflowAlcadas}', ['as' => 'admin.workflowAlcadas.destroy', 'uses' => 'Admin\WorkflowAlcadaController@destroy']);
            $router->get('workflow-alcadas/{workflowAlcadas}', ['as' => 'admin.workflowAlcadas.show', 'uses' => 'Admin\WorkflowAlcadaController@show'])
                ->middleware("needsPermission:alcadas.view");
            $router->get('workflow-alcadas/{workflowAlcadas}/edit', ['as' => 'admin.workflowAlcadas.edit', 'uses' => 'Admin\WorkflowAlcadaController@edit'])
                ->middleware("needsPermission:alcadas.edit");
        });

    });

    # Templates de Contratos
    $router->group(['prefix'=>'contratoTemplates', 'middleware' => 'needsPermission:contratoTemplates.list'], function () use ($router) {
        $router->get('', ['as' => 'admin.contratoTemplates.index', 'uses' => 'Admin\ContratoTemplateController@index']);
        $router->post('', ['as' => 'admin.contratoTemplates.store', 'uses' => 'Admin\ContratoTemplateController@store'])
            ->middleware('needsPermission:contratoTemplates.create');
        $router->get('/create', ['as' => 'admin.contratoTemplates.create', 'uses' => 'Admin\ContratoTemplateController@create'])
            ->middleware('needsPermission:contratoTemplates.create');
        $router->put('/{contratoTemplates}', ['as' => 'admin.contratoTemplates.update', 'uses' => 'Admin\ContratoTemplateController@update'])
            ->middleware('needsPermission:contratoTemplates.edit');
        $router->patch('/{contratoTemplates}', ['as' => 'admin.contratoTemplates.update', 'uses' => 'Admin\ContratoTemplateController@update'])
            ->middleware('needsPermission:contratoTemplates.edit');
        $router->delete('/{contratoTemplates}', ['as' => 'admin.contratoTemplates.destroy', 'uses' => 'Admin\ContratoTemplateController@destroy'])
            ->middleware('needsPermission:contratoTemplates.delete');
        $router->get('/{contratoTemplates}', ['as' => 'admin.contratoTemplates.show', 'uses' => 'Admin\ContratoTemplateController@show']);
        $router->get('/{contratoTemplates}/edit', ['as' => 'admin.contratoTemplates.edit', 'uses' => 'Admin\ContratoTemplateController@edit'])
            ->middleware('needsPermission:contratoTemplates.edit');
    });

    $router->group(['prefix'=>'nomeclaturaMapas', 'middleware' => 'needsPermission:nomeclaturaMapas.list'], function () use ($router) {
        $router->get('', ['as'=> 'admin.nomeclaturaMapas.index', 'uses' => 'Admin\NomeclaturaMapaController@index']);
        $router->post('', ['as'=> 'admin.nomeclaturaMapas.store', 'uses' => 'Admin\NomeclaturaMapaController@store'])
            ->middleware('needsPermission:nomeclaturaMapas.create');
        $router->get('/create', ['as'=> 'admin.nomeclaturaMapas.create', 'uses' => 'Admin\NomeclaturaMapaController@create'])
            ->middleware('needsPermission:nomeclaturaMapas.create');
        $router->put('/{nomeclaturaMapas}', ['as'=> 'admin.nomeclaturaMapas.update', 'uses' => 'Admin\NomeclaturaMapaController@update'])
            ->middleware('needsPermission:nomeclaturaMapas.edit');
        $router->patch('/{nomeclaturaMapas}', ['as'=> 'admin.nomeclaturaMapas.update', 'uses' => 'Admin\NomeclaturaMapaController@update'])
            ->middleware('needsPermission:nomeclaturaMapas.edit');
        $router->delete('/{nomeclaturaMapas}', ['as'=> 'admin.nomeclaturaMapas.destroy', 'uses' => 'Admin\NomeclaturaMapaController@destroy'])
            ->middleware('needsPermission:nomeclaturaMapas.delete');
        $router->get('/{nomeclaturaMapas}', ['as'=> 'admin.nomeclaturaMapas.show', 'uses' => 'Admin\NomeclaturaMapaController@show']);
        $router->get('/{nomeclaturaMapas}/edit', ['as'=> 'admin.nomeclaturaMapas.edit', 'uses' => 'Admin\NomeclaturaMapaController@edit'])
            ->middleware('needsPermission:nomeclaturaMapas.edit');
    });

    # desistenciaMotivos
    $router->group(['middleware' => 'needsPermission:desistenciaMotivos.list'], function () use ($router) {
        $router->get('motivos-declinar-proposta', ['as'=> 'admin.desistenciaMotivos.index', 'uses' => 'Admin\DesistenciaMotivoController@index']);
        $router->post('motivos-declinar-proposta', ['as'=> 'admin.desistenciaMotivos.store', 'uses' => 'Admin\DesistenciaMotivoController@store'])
            ->middleware('needsPermission:desistenciaMotivos.create');
        $router->get('motivos-declinar-proposta/create', ['as'=> 'admin.desistenciaMotivos.create', 'uses' => 'Admin\DesistenciaMotivoController@create'])
            ->middleware('needsPermission:desistenciaMotivos.create');
        $router->put('motivos-declinar-proposta/{desistenciaMotivos}', ['as'=> 'admin.desistenciaMotivos.update', 'uses' => 'Admin\DesistenciaMotivoController@update'])
            ->middleware('needsPermission:desistenciaMotivos.edit');
        $router->patch('motivos-declinar-proposta/{desistenciaMotivos}', ['as'=> 'admin.desistenciaMotivos.update', 'uses' => 'Admin\DesistenciaMotivoController@update'])
            ->middleware('needsPermission:desistenciaMotivos.edit');
        $router->delete('motivos-declinar-proposta/{desistenciaMotivos}', ['as'=> 'admin.desistenciaMotivos.destroy', 'uses' => 'Admin\DesistenciaMotivoController@destroy'])
            ->middleware('needsPermission:desistenciaMotivos.delete');
        $router->get('motivos-declinar-proposta/{desistenciaMotivos}', ['as'=> 'admin.desistenciaMotivos.show', 'uses' => 'Admin\DesistenciaMotivoController@show']);
        $router->get('motivos-declinar-proposta/{desistenciaMotivos}/edit', ['as'=> 'admin.desistenciaMotivos.edit', 'uses' => 'Admin\DesistenciaMotivoController@edit'])
            ->middleware('needsPermission:desistenciaMotivos.edit');
    });

});

##### SITE #####
$router->group(['prefix' => '/', 'middleware' => ['auth']], function () use ($router) {

    # Fornecedores
    $router->group(['middleware' => 'needsPermission:fornecedores.list'], function () use ($router) {
        $router->get('fornecedores/busca-temporarios', ['as' => 'admin.fornecedores.busca_temporarios', 'uses' => 'Admin\FornecedoresController@buscaTemporarios']);
        $router->get('fornecedores', ['as' => 'admin.fornecedores.index', 'uses' => 'Admin\FornecedoresController@index']);
        $router->post('fornecedores', ['as' => 'admin.fornecedores.store', 'uses' => 'Admin\FornecedoresController@store']);
        $router->get('fornecedores/create', ['as' => 'admin.fornecedores.create', 'uses' => 'Admin\FornecedoresController@create'])
            ->middleware("needsPermission:fornecedores.create");;
        $router->put('fornecedores/{fornecedores}', ['as' => 'admin.fornecedores.update', 'uses' => 'Admin\FornecedoresController@update']);
        $router->patch('fornecedores/{fornecedores}', ['as' => 'admin.fornecedores.update', 'uses' => 'Admin\FornecedoresController@update']);
        $router->delete('fornecedores/{fornecedores}', ['as' => 'admin.fornecedores.destroy', 'uses' => 'Admin\FornecedoresController@destroy'])
            ->middleware("needsPermission:fornecedores.delete");;
        $router->get('fornecedores/{fornecedores}', ['as' => 'admin.fornecedores.show', 'uses' => 'Admin\FornecedoresController@show']);
        $router->get('fornecedores/{fornecedores}/edit', ['as' => 'admin.fornecedores.edit', 'uses' => 'Admin\FornecedoresController@edit'])
            ->middleware("needsPermission:fornecedores.edit");
        $router->get('fornecedores/buscacep/{cep}', 'Admin\FornecedoresController@buscaPorCep');
        $router->get('valida-documento', 'Admin\FornecedoresController@validaCnpj');
    });

	# Carteiras
    $router->group(['middleware' => 'needsPermission:carteiras.list'], function () use ($router) {
        $router->get('carteiras/busca-temporarios', ['as' => 'admin.carteiras.busca_temporarios', 'uses' => 'Admin\CarteiraController@buscaTemporarios']);
        $router->get('carteiras', ['as' => 'admin.carteiras.index', 'uses' => 'Admin\CarteiraController@index']);
        $router->post('carteiras', ['as' => 'admin.carteiras.store', 'uses' => 'Admin\CarteiraController@store']);
        $router->get('carteiras/create', ['as' => 'admin.carteiras.create', 'uses' => 'Admin\CarteiraController@create'])
            ->middleware("needsPermission:carteiras.create");;
        $router->put('carteiras/{carteiras}', ['as' => 'admin.carteiras.update', 'uses' => 'Admin\CarteiraController@update']);
        $router->patch('carteiras/{carteiras}', ['as' => 'admin.carteiras.update', 'uses' => 'Admin\CarteiraController@update']);
        $router->delete('carteiras/{carteiras}', ['as' => 'admin.carteiras.destroy', 'uses' => 'Admin\CarteiraController@destroy'])
            ->middleware("needsPermission:carteiras.delete");;
        $router->get('carteiras/{carteiras}', ['as' => 'admin.carteiras.show', 'uses' => 'Admin\CarteiraController@show']);
        $router->get('carteiras/{carteiras}/edit', ['as' => 'admin.carteiras.edit', 'uses' => 'Admin\CarteiraController@edit'])
            ->middleware("needsPermission:carteiras.edit");
        $router->get('carteiras/buscacep/{cep}', 'Admin\CarteiraController@buscaPorCep');
    });

	# Máscara Padrão
    $router->group(['middleware' => 'needsPermission:mascara_padrao.list'], function () use ($router) {
        $router->get('mascara_padrao', ['as' => 'admin.mascara_padrao.index', 'uses' => 'Admin\MascaraPadraoController@index']);
        $router->post('mascara_padrao', ['as' => 'admin.mascara_padrao.store', 'uses' => 'Admin\MascaraPadraoController@store']);
        $router->get('mascara_padrao/create', ['as' => 'admin.mascara_padrao.create', 'uses' => 'Admin\MascaraPadraoController@create'])
            ->middleware("needsPermission:mascara_padrao.create");;
        $router->put('mascara_padrao/{mascara_padrao}', ['as' => 'admin.mascara_padrao.update', 'uses' => 'Admin\MascaraPadraoController@update']);
        $router->patch('mascara_padrao/{mascara_padrao}', ['as' => 'admin.mascara_padrao.update', 'uses' => 'Admin\MascaraPadraoController@update']);
        $router->delete('mascara_padrao/{mascara_padrao}', ['as' => 'admin.mascara_padrao.destroy', 'uses' => 'Admin\MascaraPadraoController@destroy'])
            ->middleware("needsPermission:mascara_padrao.delete");;
        $router->get('mascara_padrao/{mascara_padrao}', ['as' => 'admin.mascara_padrao.show', 'uses' => 'Admin\MascaraPadraoController@show']);
        $router->get('mascara_padrao/{mascara_padrao}/edit', ['as' => 'admin.mascara_padrao.edit', 'uses' => 'Admin\MascaraPadraoController@edit'])
            ->middleware("needsPermission:mascara_padrao.edit");
    });

	# Tarefa Padrão
    $router->group(['middleware' => 'needsPermission:tarefa_padrao.list'], function () use ($router) {
        $router->get('tarefa_padrao', ['as' => 'admin.tarefa_padrao.index', 'uses' => 'Admin\TarefaPadraoController@index']);
        $router->post('tarefa_padrao', ['as' => 'admin.tarefa_padrao.store', 'uses' => 'Admin\TarefaPadraoController@store']);
        $router->get('tarefa_padrao/create', ['as' => 'admin.tarefa_padrao.create', 'uses' => 'Admin\TarefaPadraoController@create'])
            ->middleware("needsPermission:tarefa_padrao.create");;
        $router->put('tarefa_padrao/{tarefa_padrao}', ['as' => 'admin.tarefa_padrao.update', 'uses' => 'Admin\TarefaPadraoController@update']);
        $router->patch('tarefa_padrao/{tarefa_padrao}', ['as' => 'admin.tarefa_padrao.update', 'uses' => 'Admin\TarefaPadraoController@update']);
        $router->delete('tarefa_padrao/{tarefa_padrao}', ['as' => 'admin.tarefa_padrao.destroy', 'uses' => 'Admin\TarefaPadraoController@destroy'])
            ->middleware("needsPermission:tarefa_padrao.delete");;
        $router->get('tarefa_padrao/{tarefa_padrao}', ['as' => 'admin.tarefa_padrao.show', 'uses' => 'Admin\TarefaPadraoController@show']);
        $router->get('tarefa_padrao/{tarefa_padrao}/edit', ['as' => 'admin.tarefa_padrao.edit', 'uses' => 'Admin\TarefaPadraoController@edit'])
            ->middleware("needsPermission:tarefa_padrao.edit");
    });

	# Solicitação de Insumos
    $router->get('solicitacaoInsumos/create', ['as' => 'admin.solicitacaoInsumos.create', 'uses' => 'Admin\SolicitacaoInsumoController@create'])
        ->middleware("needsPermission:solicitacaoInsumos.create");
    $router->group(['middleware' => 'needsPermission:solicitacaoInsumos.list'], function () use ($router) {
        $router->get('solicitacaoInsumos', ['as' => 'admin.solicitacaoInsumos.index', 'uses' => 'Admin\SolicitacaoInsumoController@index']);
        $router->post('solicitacaoInsumos', ['as' => 'admin.solicitacaoInsumos.store', 'uses' => 'Admin\SolicitacaoInsumoController@store']);
        $router->put('solicitacaoInsumos/{solicitacaoInsumos}', ['as' => 'admin.solicitacaoInsumos.update', 'uses' => 'Admin\SolicitacaoInsumoController@update'])
            ->middleware("needsPermission:solicitacaoInsumos.edit");
        $router->patch('solicitacaoInsumos/{solicitacaoInsumos}', ['as' => 'admin.solicitacaoInsumos.update', 'uses' => 'Admin\SolicitacaoInsumoController@update'])
            ->middleware("needsPermission:solicitacaoInsumos.edit");
        $router->delete('solicitacaoInsumos/{solicitacaoInsumos}', ['as' => 'admin.solicitacaoInsumos.destroy', 'uses' => 'Admin\SolicitacaoInsumoController@destroy'])
            ->middleware("needsPermission:solicitacaoInsumos.delete");
        $router->get('solicitacaoInsumos/{solicitacaoInsumos}', ['as' => 'admin.solicitacaoInsumos.show', 'uses' => 'Admin\SolicitacaoInsumoController@show']);
        $router->get('solicitacaoInsumos/{solicitacaoInsumos}/edit', ['as' => 'admin.solicitacaoInsumos.edit', 'uses' => 'Admin\SolicitacaoInsumoController@edit'])
            ->middleware("needsPermission:solicitacaoInsumos.edit");
    });

    # Grupo de Insumos
    $router->group(['middleware' => 'needsPermission:grupos_insumos.list'], function () use ($router) {
        $router->get('insumoGrupos', ['as' => 'admin.insumoGrupos.index', 'uses' => 'Admin\InsumoGrupoController@index']);
//        $router->post('insumoGrupos', ['as' => 'admin.insumoGrupos.store', 'uses' => 'Admin\InsumoGrupoController@store']);
//        $router->get('insumoGrupos/create', ['as' => 'admin.insumoGrupos.create', 'uses' => 'Admin\InsumoGrupoController@create']);
//        $router->put('insumoGrupos/{insumoGrupos}', ['as' => 'admin.insumoGrupos.update', 'uses' => 'Admin\InsumoGrupoController@update']);
//        $router->patch('insumoGrupos/{insumoGrupos}', ['as' => 'admin.insumoGrupos.update', 'uses' => 'Admin\InsumoGrupoController@update']);
//        $router->delete('insumoGrupos/{insumoGrupos}', ['as' => 'admin.insumoGrupos.destroy', 'uses' => 'Admin\InsumoGrupoController@destroy']);
        $router->get('insumoGrupos/{insumoGrupos}', ['as' => 'admin.insumoGrupos.show', 'uses' => 'Admin\InsumoGrupoController@show'])
            ->middleware("needsPermission:grupos_insumos.view");
//        $router->get('insumoGrupos/{insumoGrupos}/edit', ['as' => 'admin.insumoGrupos.edit', 'uses' => 'Admin\InsumoGrupoController@edit']);
        $router->post('insumoGrupos/{insumoGrupos}/enable', ['as' => 'admin.insumoGrupos.enable', 'uses' => 'Admin\InsumoGrupoController@enable'])
            ->middleware('needsPermission:grupos_insumos.availability');
        $router->post('insumoGrupos/{insumoGrupos}/disable', ['as' => 'admin.insumoGrupos.disable', 'uses' => 'Admin\InsumoGrupoController@disable'])
            ->middleware('needsPermission:grupos_insumos.availability');
    });

    # Insumos
    $router->group(['middleware' => 'needsPermission:insumos.list'], function () use ($router) {
        $router->get('insumos', ['as' => 'admin.insumos.index', 'uses' => 'Admin\InsumoController@index']);
//        $router->post('insumos', ['as' => 'admin.insumos.store', 'uses' => 'Admin\InsumoController@store']);
//        $router->get('insumos/create', ['as' => 'admin.insumos.create', 'uses' => 'Admin\InsumoController@create']);
//        $router->put('insumos/{insumos}', ['as' => 'admin.insumos.update', 'uses' => 'Admin\InsumoController@update']);
//        $router->patch('insumos/{insumos}', ['as' => 'admin.insumos.update', 'uses' => 'Admin\InsumoController@update']);
//        $router->delete('insumos/{insumos}', ['as' => 'admin.insumos.destroy', 'uses' => 'Admin\InsumoController@destroy']);
        $router->get('insumos/{insumos}', ['as' => 'admin.insumos.show', 'uses' => 'Admin\InsumoController@show'])->middleware("needsPermission:insumos.view");
        $router->get('insumos/{insumos}/json', ['as' => 'admin.insumos.show-json', 'uses' => 'Admin\InsumoController@showJson']);
//        $router->get('insumos/{insumos}/edit', ['as' => 'admin.insumos.edit', 'uses' => 'Admin\InsumoController@edit']);
        $router->post('insumos/{insumos}/enable', ['as' => 'admin.insumos.enable', 'uses' => 'Admin\InsumoController@enable'])
            ->middleware("needsPermission:insumos.availability");
        $router->post('insumos/{insumos}/disable', ['as' => 'admin.insumos.disable', 'uses' => 'Admin\InsumoController@disable'])
            ->middleware("needsPermission:insumos.availability");
    });

    # Retroalimentação de obras
    $router->group(['middleware' => 'needsPermission:retroalimentacao.list'], function () use ($router) {
        $router->resource('retroalimentacaoObras', 'RetroalimentacaoObraController');
    });

    # Obras
    $router->group(['middleware' => 'needsPermission:obras.list'], function () use ($router) {
        $router->get('obras', ['as' => 'admin.obras.index', 'uses' => 'Admin\ObraController@index']);
        $router->post('obras', ['as' => 'admin.obras.store', 'uses' => 'Admin\ObraController@store']);
        $router->get('obras/create', ['as' => 'admin.obras.create', 'uses' => 'Admin\ObraController@create'])
            ->middleware("needsPermission:obras.create");
        $router->put('obras/{obras}', ['as' => 'admin.obras.update', 'uses' => 'Admin\ObraController@update']);
        $router->patch('obras/{obras}', ['as' => 'admin.obras.update', 'uses' => 'Admin\ObraController@update']);
        $router->delete('obras/{obras}', ['as' => 'admin.obras.destroy', 'uses' => 'Admin\ObraController@destroy']);
        $router->get('obras/{obras}', ['as' => 'admin.obras.show', 'uses' => 'Admin\ObraController@show'])
            ->middleware("needsPermission:obras.view");
        $router->get('obras/{obras}/edit', ['as' => 'admin.obras.edit', 'uses' => 'Admin\ObraController@edit'])
            ->middleware("needsPermission:obras.edit");
        $router->get('obras/torre/{obras}', ['as' => 'admin.obras.torre', 'uses' => 'Admin\ObraController@obrasTorre']);
    });

    // Memória de Cálculo
    $router->group(['prefix'=>'memoriaCalculos', 'middleware' => 'needsPermission:memoriaCalculos.list'], function () use ($router) {
        $router->get('', ['as'=> 'memoriaCalculos.index', 'uses' => 'MemoriaCalculoController@index']);
        $router->post('', ['as'=> 'memoriaCalculos.store', 'uses' => 'MemoriaCalculoController@store'])
            ->middleware('needsPermission:memoriaCalculos.create');
        $router->get('/create', ['as'=> 'memoriaCalculos.create', 'uses' => 'MemoriaCalculoController@create'])
            ->middleware('needsPermission:memoriaCalculos.create');
        $router->put('/{memoriaCalculos}', ['as'=> 'memoriaCalculos.update', 'uses' => 'MemoriaCalculoController@update'])
            ->middleware('needsPermission:memoriaCalculos.edit');
        $router->patch('/{memoriaCalculos}', ['as'=> 'memoriaCalculos.update', 'uses' => 'MemoriaCalculoController@update'])
            ->middleware('needsPermission:memoriaCalculos.edit');
        $router->delete('/{memoriaCalculos}', ['as'=> 'memoriaCalculos.destroy', 'uses' => 'MemoriaCalculoController@destroy'])
            ->middleware('needsPermission:memoriaCalculos.delete');
        $router->get('/{memoriaCalculos}', ['as'=> 'memoriaCalculos.show', 'uses' => 'MemoriaCalculoController@show']);
        $router->get('/{memoriaCalculos}/edit', ['as'=> 'memoriaCalculos.edit', 'uses' => 'MemoriaCalculoController@edit'])
            ->middleware('needsPermission:memoriaCalculos.edit');
        $router->get('/{memoriaCalculo}/clone', ['as'=> 'memoriaCalculos.clone', 'uses' => 'MemoriaCalculoController@clonar'])
            ->middleware('needsPermission:memoriaCalculos.create');

        $router->post('/putSessionMemoriaDeCalculo', ['as'=> 'memoriaCalculos.putSessionMemoriaDeCalculo', 'uses' => 'MemoriaCalculoController@putSessionMemoriaDeCalculo'])
            ->middleware('needsPermission:memoriaCalculos.create');

        $router->post('/forgetSessionMemoriaDeCalculo', ['as'=> 'memoriaCalculos.forgetSessionMemoriaDeCalculo', 'uses' => 'MemoriaCalculoController@forgetSessionMemoriaDeCalculo'])
            ->middleware('needsPermission:memoriaCalculos.create');

    });
    // Medição
    $router->group(['prefix'=>'medicoes', 'middleware' => 'needsPermission:medicoes.list'], function () use ($router) {
        //        $router->get('', ['as'=> 'medicoes.index', 'uses' => 'MedicaoController@index']);
        $router->get('', ['as'=> 'medicoes.index', 'uses' => 'MedicaoServicoController@index']);
        $router->post('', ['as'=> 'medicoes.store', 'uses' => 'MedicaoController@store'])
            ->middleware('needsPermission:medicoes.create');
        $router->post('/medicao-servico', ['as'=> 'medicao_servicos.store', 'uses' => 'MedicaoController@medicaoServicoStore'])
            ->middleware('needsPermission:medicoes.create');
        $router->get('/pre-create', ['as'=> 'medicoes.preCreate', 'uses' => 'MedicaoController@preCreate'])
            ->middleware('needsPermission:medicoes.create');
        $router->get('/create', ['as'=> 'medicoes.create', 'uses' => 'MedicaoController@create'])
            ->middleware('needsPermission:medicoes.create');
        $router->put('/{medicoes}', ['as'=> 'medicoes.update', 'uses' => 'MedicaoController@update'])
            ->middleware('needsPermission:medicoes.edit');
        $router->patch('/{medicoes}', ['as'=> 'medicoes.update', 'uses' => 'MedicaoController@update'])
            ->middleware('needsPermission:medicoes.edit');
        $router->delete('/{medicoes}', ['as'=> 'medicoes.destroy', 'uses' => 'MedicaoController@destroy'])
            ->middleware('needsPermission:medicoes.delete');
        $router->delete('/servico/{medicoes}', ['as'=> 'medicaoServicos.destroy', 'uses' => 'MedicaoServicoController@destroy'])
            ->middleware('needsPermission:medicoes.delete');

        $router->put('/servico/{medicoes}', ['as'=> 'medicaoServicos.update', 'uses' => 'MedicaoServicoController@update'])
            ->middleware('needsPermission:medicoes.edit');
        $router->patch('/servico/{medicoes}', ['as'=> 'medicaoServicos.update', 'uses' => 'MedicaoServicoController@update'])
            ->middleware('needsPermission:medicoes.edit');

        $router->get(
            '/fornecedores-por-obra',
            'MedicaoController@fornecedoresPorObra'
        );
        $router->get(
            '/contratos-por-obra',
            'MedicaoController@contratosPorObra'
        );
        $router->get(
            '/tarefas-por-obra',
            'MedicaoController@tarefasPorObra'
        );
        $router->get(
            '/insumos',
            'MedicaoController@insumos'
        );

        $router->get('/{medicoes}', ['as'=> 'medicoes.show', 'uses' => 'MedicaoController@show']);
        $router->get('/{medicoes}/edit', ['as'=> 'medicoes.edit', 'uses' => 'MedicaoController@edit'])
            ->middleware('needsPermission:medicoes.edit');

        $router->get('/servico/{medicoes}', ['as'=> 'medicaoServicos.show', 'uses' => 'MedicaoServicoController@show']);
        $router->get('/servico/{medicoes}/edit', ['as'=> 'medicaoServicos.edit', 'uses' => 'MedicaoServicoController@edit'])
            ->middleware('needsPermission:medicoes.edit');

    });
    // Boletim de Medição
    $router->group(['prefix'=>'boletim-medicao', 'middleware' => 'needsPermission:boletim-medicao.list'], function () use ($router) {
        $router->get('/{boletimMedicao}/remover-medicao/{medicao_servico_id}', ['as'=> 'boletim-medicao.remover', 'uses' => 'MedicaoBoletimController@removerMedicao']);
        $router->get('/{boletimMedicao}/liberar', ['as'=> 'boletim-medicao.liberar-nf', 'uses' => 'MedicaoBoletimController@liberarNF']);
        $router->get('/{boletimMedicao}/download', ['as'=> 'boletim-medicao.download', 'uses' => 'MedicaoBoletimController@download']);

        $router->get('', ['as'=> 'boletim-medicao.index', 'uses' => 'MedicaoBoletimController@index']);
        $router->post('', ['as'=> 'boletim-medicao.store', 'uses' => 'MedicaoBoletimController@store'])
            ->middleware('needsPermission:boletim-medicao.create');
        $router->get('/create', ['as'=> 'boletim-medicao.create', 'uses' => 'MedicaoBoletimController@create'])
            ->middleware('needsPermission:boletim-medicao.create');
        $router->put('/{boletimMedicao}', ['as'=> 'boletim-medicao.update', 'uses' => 'MedicaoBoletimController@update'])
            ->middleware('needsPermission:boletim-medicao.edit');
        $router->patch('/{boletimMedicao}', ['as'=> 'boletim-medicao.update', 'uses' => 'MedicaoBoletimController@update'])
            ->middleware('needsPermission:boletim-medicao.edit');
        $router->delete('/{boletimMedicao}', ['as'=> 'boletim-medicao.destroy', 'uses' => 'MedicaoBoletimController@destroy'])
            ->middleware('needsPermission:boletim-medicao.delete');


        $router->get('/{boletimMedicao}', ['as'=> 'boletim-medicao.show', 'uses' => 'MedicaoBoletimController@show']);
        $router->get('/{boletimMedicao}/edit', ['as'=> 'boletim-medicao.edit', 'uses' => 'MedicaoBoletimController@edit'])
            ->middleware('needsPermission:boletim-medicao.edit');

    });

    // Perfil
    $router->get('/perfil', 'PerfilController@index');
    $router->post('/perfil', 'PerfilController@save');

    # Home
    $router->get('/', 'HomeController@index');
    $router->get('/home', 'HomeController@index');

    # log do laravel
    $router->get('/console/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

    # Compras lembretes
    $router->get('compras', 'OrdemDeCompraController@compras')
        ->middleware("needsPermission:compras_lembretes.list");

    # Ordens de compra
    $router->get('/ordens-de-compra/insumos-aprovados', 'OrdemDeCompraController@insumosAprovados')
        ->middleware("needsPermission:quadroDeConcorrencias.create");
    $router->get('/ordens-de-compra/dispensar-insumo-aprovado', 'OrdemDeCompraController@dispensaAprovado')
        ->middleware("needsPermission:quadroDeConcorrencias.create");
    $router->group(['middleware' => 'needsPermission:ordens_de_compra.list'], function () use ($router) {
        $router->get('/ordens-de-compra/detalhes/{id}', 'OrdemDeCompraController@detalhe')
            ->middleware("needsPermission:ordens_de_compra.detalhes")
            ->name("ordens_de_compra.detalhes");
        $router->get('/ordens-de-compra/carrinho', 'OrdemDeCompraController@carrinho');
        $router->post('/ordens-de-compra/carrinho/comprar-tudo-de-tudo', 'OrdemDeCompraController@comprarTudoDeTudo');
        $router->get('/ordens-de-compra/carrinho/indicar-contrato', 'OrdemDeCompraController@indicarContrato');
        $router->get('/ordens-de-compra/fechar-carrinho', 'OrdemDeCompraController@fechaCarrinho');
        $router->post('/ordens-de-compra/altera-item/{id}', 'OrdemDeCompraController@alteraItem');
        $router->post('/ordens-de-compra/upload-anexos/{id}', 'OrdemDeCompraController@uploadAnexos');
        $router->get('/ordens-de-compra/remover-anexo/{id}', 'OrdemDeCompraController@removerAnexo');
        $router->get('/ordens-de-compra/carrinho/remove-contrato', 'OrdemDeCompraController@removerContrato');
        $router->get('/ordens-de-compra/reabrir-ordem-de-compra/verificar/{oc_id}/{obra_id}', 'OrdemDeCompraController@verificaReabrirOrdemDeCompra');
        $router->get('/ordens-de-compra/reabrir-ordem-de-compra/{id}', 'OrdemDeCompraController@reabrirOrdemDeCompra');
        $router->get('/ordens-de-compra/unificar-ordem-de-compra/{oc_aberta}/{oc_reabrir}', 'OrdemDeCompraController@unificarOrdemDeCompra');
        $router->get('/ordens-de-compra/carrinho/alterar-quantidade/{id}', 'OrdemDeCompraController@alterarQuantidade');
        $router->get('/ordens-de-compra/carrinho/alterar-valor-unitario/{id}', 'OrdemDeCompraController@alteraValorUnitario');
        $router->get('/ordens-de-compra/carrinho/remover-item/{id}', 'OrdemDeCompraController@removerItem');
        $router->get('/ordens-de-compra/carrinho/limpar-carrinho/{ordem_de_compra_id}', 'OrdemDeCompraController@limparCarrinho');
        $router->get('/ordens-de-compra/detalhes-servicos/{obra_id}/{servico_id}', 'OrdemDeCompraController@detalhesServicos')
            ->middleware("needsPermission:ordens_de_compra.detalhes_servicos");

        $router->get('ordens-de-compra/grupos/{id}', 'OrdemDeCompraController@getGrupos');
        $router->get('ordens-de-compra/servicos/{id}', 'OrdemDeCompraController@getServicos');

        $router->resource('ordens-de-compra', 'OrdemDeCompraController');
    });


    # Dashboard site ORDEM DE COMPRA
    $router->group(['middleware' => 'needsPermission:site.dashboard'], function () use ($router) {
        $router->get('compras/jsonOrdemCompraDashboard', 'OrdemDeCompraController@jsonOrdemCompraDashboard');
        $router->get('compras/dashboard', 'OrdemDeCompraController@dashboard');
    });

    # Compras
    $router->group(['prefix' => 'compras'], function () use ($router) {
        $router->group(['middleware' => 'needsPermission:compras.geral'], function () use ($router) {

            $router->get('trocar/{ordemDeCompraItem}', 'OrdemDeCompraController@trocar')->name('compras.trocar');
            $router->post('trocar/{ordemDeCompraItem}', 'OrdemDeCompraController@trocarSave');

            $router->get('{planejamento}/insumos/{insumoGrupo}', 'OrdemDeCompraController@insumos')->name('compraInsumo');
            $router->get('{planejamento}/insumosJson', 'OrdemDeCompraController@insumosJson');
            $router->get('{planejamento}/insumosFilters', 'OrdemDeCompraController@insumosFilters');
            $router->post('{planejamento}/insumosAdd', 'OrdemDeCompraController@insumosAdd');

            $router->get('{planejamento}/obrasInsumos/{insumoGrupo}', 'OrdemDeCompraController@obrasInsumos');
            $router->get('{planejamento}/obrasInsumosFilters', 'OrdemDeCompraController@obrasInsumosFilters');
            $router->get('{planejamento}/obrasInsumosJson/{insumoGrupo}', 'OrdemDeCompraController@obrasInsumosJson');

            $router->get('insumos', 'OrdemDeCompraController@insumos')->name('compraInsumo');
            $router->get('insumos/orcamento/{obra_id}', 'OrdemDeCompraController@insumosOrcamento');
            $router->post('insumos/orcamento/incluir', 'OrdemDeCompraController@incluirInsumosOrcamento');
            $router->get('insumos/orcamento/cadastrar/grupo', 'OrdemDeCompraController@cadastrarGrupo');
            $router->get('insumosJson', 'OrdemDeCompraController@insumosJson');
            $router->get('insumosFilters', 'OrdemDeCompraController@insumosFilters');
            $router->post('insumosAdd', 'OrdemDeCompraController@insumosAdd');

            $router->get('obrasInsumos', 'OrdemDeCompraController@obrasInsumos');
            $router->get('obrasInsumos/dispensar', 'OrdemDeCompraController@dispensar');
            $router->get('obrasInsumosFilters', 'OrdemDeCompraController@obrasInsumosFilters');
            $router->get('obrasInsumosJson', 'OrdemDeCompraController@obrasInsumosJson');

            $router->get('trocaInsumos/{id}', 'OrdemDeCompraController@trocaInsumos');
            $router->get('{planejamento}/trocaInsumosFilters', 'OrdemDeCompraController@trocaInsumosFilters');
            $router->get('trocaInsumosJsonPai/{insumo}', 'OrdemDeCompraController@trocaInsumosJsonPai');
            $router->get('trocaInsumosJsonFilho', 'OrdemDeCompraController@trocaInsumosJsonFilho');
            $router->post('trocaInsumoAction', 'OrdemDeCompraController@trocaInsumoAction');
            $router->post('{obra}/{planejamento}/addCarrinho', 'OrdemDeCompraController@addCarrinho');
            $router->post('{obra}/addCarrinho', 'OrdemDeCompraController@addCarrinho');
            $router->post('{obra}/totalParcial', 'OrdemDeCompraController@totalParcial');
            $router->post('{obra}/comprarTudo', 'OrdemDeCompraController@comprarTudo');
            $router->get('removerInsumoPlanejamento/{planejamentoCompra}', 'OrdemDeCompraController@removerInsumoPlanejamento');

        });
    });

    # Workflow
    $router->group(['prefix' => 'workflow'], function () use ($router) {
        $router->get('aprova-reprova', 'WorkflowController@aprovaReprova');
        $router->get('aprova-reprova-tudo', 'WorkflowController@aprovaReprovaTudo');
    });

    # Retroalimentação de obras
    $router->get('retroalimentacao', 'RetroalimentacaoObraController@create_front')
        ->middleware("needsPermission:retroalimentacao.create");

    # Quadro de Concorrencia
    $router->group(['prefix' => 'quadro-de-concorrencia',
                    'middleware' => 'needsPermission:quadroDeConcorrencias.list'],
        function () use ($router) {
            $router->post(
                '/{quadroDeConcorrencias}/gerar-contrato',
                'QuadroDeConcorrenciaController@gerarContratoSave'
            )->middleware('needsPermission:quadroDeConcorrencias.edit');

            $router->get(
                '/{quadroDeConcorrencias}/gerar-contrato',
                'QuadroDeConcorrenciaController@gerarContrato'
            )->name('quadroDeConcorrencia.informar-valor')
                ->middleware('needsPermission:quadroDeConcorrencias.edit');

            $router->post(
                'remover-itens',
                'QuadroDeConcorrenciaController@removerItens'
            )
            ->name('quadroDeConcorrencia.remover-item')
            ->middleware('needsPermission:quadroDeConcorrencias.edit');

            $router->post(
                '/{quadroDeConcorrencias}/informar-valor',
                'QuadroDeConcorrenciaController@informarValorSave'
            )->middleware('needsPermission:quadroDeConcorrencias.informar_valor');

            $router->get(
                '/{quadroDeConcorrencias}/informar-valor',
                'QuadroDeConcorrenciaController@informarValor'
            )->name('quadroDeConcorrencia.informar-valor')
                ->middleware('needsPermission:quadroDeConcorrencias.informar_valor');

            /* $router->post( */
            /*     '/{quadroDeConcorrencias}/avaliar', */
            /*     'QuadroDeConcorrenciaController@avaliarSave' */
            /* )->middleware('needsPermission:quadroDeConcorrencias.edit'); */

            $router->get(
                '/{quadroDeConcorrencias}/avaliar',
                'QuadroDeConcorrenciaController@avaliar'
            )->name('quadroDeConcorrencia.avaliar')
                ->middleware('needsPermission:quadroDeConcorrencias.edit');

            $router->post(
                '/{quadroDeConcorrencias}/avaliar',
                'QuadroDeConcorrenciaController@avaliarSave'
            )->middleware('needsPermission:quadroDeConcorrencias.edit');

            $router->get(
                '/{quadroDeConcorrencias}/equalizacao-tecnica/{qcFornecedor}',
                'QuadroDeConcorrenciaController@getEqualizacaoTecnica'
            )->name('quadroDeConcorrencia.get-equalizacao-tecnica')
                ->middleware('needsPermission:quadroDeConcorrencias.edit');

            $router->get('', ['as' => 'quadroDeConcorrencias.index', 'uses' => 'QuadroDeConcorrenciaController@index']);

            $router->post('/criar',
                [
                    'as' => 'quadroDeConcorrencias.create',
                    'uses' => 'QuadroDeConcorrenciaController@create'
                ])->middleware("needsPermission:quadroDeConcorrencias.create");
            $router->put('/{quadroDeConcorrencias}',
                [
                    'as' => 'quadroDeConcorrencias.update',
                    'uses' => 'QuadroDeConcorrenciaController@update'
                ]);
            $router->patch('/{quadroDeConcorrencias}',
                [
                    'as' => 'quadroDeConcorrencias.update',
                    'uses' => 'QuadroDeConcorrenciaController@update'
                ]);
            $router->delete('/{quadroDeConcorrencias}',
                [
                    'as' => 'quadroDeConcorrencias.destroy',
                    'uses' => 'QuadroDeConcorrenciaController@destroy'
                ])->middleware("needsPermission:quadroDeConcorrencias.delete");
            $router->get('/{quadroDeConcorrencias}',
                [
                    'as' => 'quadroDeConcorrencias.show',
                    'uses' => 'QuadroDeConcorrenciaController@show'
                ])->middleware("needsPermission:quadroDeConcorrencias.view");
            $router->get('/{quadroDeConcorrencias}/edit',
                [
                    'as' => 'quadroDeConcorrencias.edit',
                    'uses' => 'QuadroDeConcorrenciaController@edit'
                ])->middleware("needsPermission:quadroDeConcorrencias.edit");
            $router->get('/{quadroDeConcorrencias}/remover-fornecedor/{fornecedorId}',
                [
                    'as' => 'quadroDeConcorrencias.removerfornecedor',
                    'uses' => 'QuadroDeConcorrenciaController@removerFornecedor'
                ])->middleware("needsPermission:quadroDeConcorrencias.edit");
            $router->post('/{quadroDeConcorrencias}/adiciona-eqt',
                [
                    'as' => 'quadroDeConcorrencias.adicionaeqt',
                    'uses' => 'QuadroDeConcorrenciaController@adicionaEqt'
                ])->middleware("needsPermission:quadroDeConcorrencias.create");
            $router->get('/{quadroDeConcorrencias}/remover-eqt/{eqtId}',
                [
                    'as' => 'quadroDeConcorrencias.removereqt',
                    'uses' => 'QuadroDeConcorrenciaController@removerEqt'
                ])->middleware("needsPermission:quadroDeConcorrencias.edit");
            $router->get('/{quadroDeConcorrencias}/exibir-eqt/{eqtId}',
                [
                    'as' => 'quadroDeConcorrencias.exibireqt',
                    'uses' => 'QuadroDeConcorrenciaController@exibirEqt'
                ])->middleware("needsPermission:quadroDeConcorrencias.edit");
            $router->post('/{quadroDeConcorrencias}/editar-eqt/{eqtId}',
                [
                    'as' => 'quadroDeConcorrencias.editareqt',
                    'uses' => 'QuadroDeConcorrenciaController@editarEqt'
                ])->middleware("needsPermission:quadroDeConcorrencias.edit");
            $router->post('/{quadroDeConcorrencias}/adiciona-eqt-anexo',
                [
                    'as' => 'quadroDeConcorrencias.adicionaeqtanexo',
                    'uses' => 'QuadroDeConcorrenciaController@adicionaEqtAnexo'
                ])->middleware("needsPermission:quadroDeConcorrencias.create");
            $router->get('/{quadroDeConcorrencias}/remover-eqt-anexo/{eqtId}',
                [
                    'as' => 'quadroDeConcorrencias.removereqtanexo',
                    'uses' => 'QuadroDeConcorrenciaController@removerEqtAnexo'
                ])->middleware("needsPermission:quadroDeConcorrencias.edit");
            $router->get('/{quadroDeConcorrencias}/exibir-eqt-anexo/{eqtId}',
                [
                    'as' => 'quadroDeConcorrencias.exibireqtanexo',
                    'uses' => 'QuadroDeConcorrenciaController@exibirEqtAnexo'
                ])->middleware("needsPermission:quadroDeConcorrencias.edit");
            $router->post('/{quadroDeConcorrencias}/editar-eqt-anexo/{eqtId}',
                [
                    'as' => 'quadroDeConcorrencias.editareqtanexo',
                    'uses' => 'QuadroDeConcorrenciaController@editarEqtAnexo'
                ])->middleware("needsPermission:quadroDeConcorrencias.edit");
            $router->get('/{quadroDeConcorrencias}/desagrupar/{itemId}', 'QuadroDeConcorrenciaController@desagrupar')
                ->middleware("needsPermission:quadroDeConcorrencias.edit");
            $router->post('/{quadroDeConcorrencias}/agrupar', 'QuadroDeConcorrenciaController@agrupar')
                ->middleware("needsPermission:quadroDeConcorrencias.edit");
            $router->get('/{quadroDeConcorrencias}/acao/{acao}', 'QuadroDeConcorrenciaController@acao')
                ->middleware("needsPermission:quadroDeConcorrencias.edit");
            $router->post('/{quadroDeConcorrencias}/adicionar',
                [
                    'as' => 'quadroDeConcorrencias.adicionar',
                    'uses' => 'QuadroDeConcorrenciaController@adicionar'
                ])->middleware("needsPermission:quadroDeConcorrencias.edit");
            $router->get('/{quadroDeConcorrencias}/check-fornecedor',
                [
                    'as' => 'quadroDeConcorrencias.validaFornecedor',
                    'uses' => 'QuadroDeConcorrenciaController@validaFornecedor'
                ]);

            # Dashboard site QUADRO DE CONCORRÊNCIA
            $router->get('/view/dashboard',
                [
                    'as' => 'quadroDeConcorrencias.dashboard',
                    'uses' => 'QuadroDeConcorrenciaController@dashboard'
                ])->middleware("needsPermission:quadroDeConcorrencias.dashboard");
        }
    );

    # Template de Contrato
    $router->get('/contrato-template/{contratoTemplates}/campos', 'Admin\ContratoTemplateController@camposExtras');

    # Lista de Q.C. suprimentos
    $router->group(['prefix' => 'lista-qc'], function () use ($router) {
        $router->get('/', ['as' => 'listaQc.index', 'uses' => 'QcSuprimentosController@index'])
                ->middleware('needsPermission:lista_qc.list');
    });


    # Catálogo de Acordos
    $router->group(['middleware' => 'needsPermission:catalogo_acordos.list'], function () use ($router) {
        $router->get('catalogo-acordos', ['as' => 'catalogo_contratos.index', 'uses' => 'CatalogoContratoController@index']);
        $router->post('catalogo-acordos', ['as' => 'catalogo_contratos.store', 'uses' => 'CatalogoContratoController@store']);
        $router->get('catalogo-acordos/create', ['as' => 'catalogo_contratos.create', 'uses' => 'CatalogoContratoController@create'])
            ->middleware("needsPermission:catalogo_acordos.create");
        $router->put('catalogo-acordos/{contratos}', ['as' => 'catalogo_contratos.update', 'uses' => 'CatalogoContratoController@update']);
        $router->patch('catalogo-acordos/{contratos}', ['as' => 'catalogo_contratos.update', 'uses' => 'CatalogoContratoController@update']);
        $router->delete('catalogo-acordos/{contratos}', ['as' => 'catalogo_contratos.destroy', 'uses' => 'CatalogoContratoController@destroy'])
            ->middleware("needsPermission:catalogo_acordos.delete");
        $router->get('catalogo-acordos/{contratos}', ['as' => 'catalogo_contratos.show', 'uses' => 'CatalogoContratoController@show']);
        $router->get('catalogo-acordos/{contratos}/edit', ['as' => 'catalogo_contratos.edit', 'uses' => 'CatalogoContratoController@edit'])
            ->middleware("needsPermission:catalogo_acordos.edit");
        $router->get('catalogo-acordos/buscar/busca_fornecedores', ['as' => 'catalogo_contratos.busca_fornecedores', 'uses' => 'CatalogoContratoController@buscaFornecedor']);
        $router->get('catalogo-acordos-insumo/delete', 'CatalogoContratoController@deleteInsumo');

        $router->get('catalogo-acordos/{contratos}/removeRegional/{remover}', ['as' => 'catalogo_contratos.removeRegional', 'uses' => 'CatalogoContratoController@removeRegional'])
            ->middleware("needsPermission:catalogo_acordos.edit");
        $router->get('catalogo-acordos/{contratos}/imprimir-minuta', ['as' => 'catalogo_contratos.removeObra', 'uses' => 'CatalogoContratoController@imprimirMinuta']);

        $router->get('catalogo-acordos/acao/ativar-desativar', 'CatalogoContratoController@ativarDesativar')
            ->middleware("needsPermission:catalogo_acordos.edit");
    });

    # Tipo equalização tecnicas
    $router->group(['middleware' => 'needsPermission:equalizacao_tecnicas.list'], function () use ($router) {
        $router->get('tipoEqualizacaoTecnicas', ['as' => 'tipoEqualizacaoTecnicas.index', 'uses' => 'TipoEqualizacaoTecnicaController@index']);
        $router->post('tipoEqualizacaoTecnicas', ['as' => 'tipoEqualizacaoTecnicas.store', 'uses' => 'TipoEqualizacaoTecnicaController@store']);
        $router->get('tipoEqualizacaoTecnicas/create', ['as' => 'tipoEqualizacaoTecnicas.create', 'uses' => 'TipoEqualizacaoTecnicaController@create'])->middleware("needsPermission:equalizacao_tecnicas.create");
        $router->put('tipoEqualizacaoTecnicas/{tipoEqualizacaoTecnicas}', ['as' => 'tipoEqualizacaoTecnicas.update', 'uses' => 'TipoEqualizacaoTecnicaController@update']);
        $router->patch('tipoEqualizacaoTecnicas/{tipoEqualizacaoTecnicas}', ['as' => 'tipoEqualizacaoTecnicas.update', 'uses' => 'TipoEqualizacaoTecnicaController@update']);
        $router->delete('tipoEqualizacaoTecnicas/{tipoEqualizacaoTecnicas}', ['as' => 'tipoEqualizacaoTecnicas.destroy', 'uses' => 'TipoEqualizacaoTecnicaController@destroy'])->middleware("needsPermission:equalizacao_tecnicas.delete");
        $router->get('tipoEqualizacaoTecnicas/{tipoEqualizacaoTecnicas}', ['as' => 'tipoEqualizacaoTecnicas.show', 'uses' => 'TipoEqualizacaoTecnicaController@show']);
        $router->get('tipoEqualizacaoTecnicas/{tipoEqualizacaoTecnicas}/edit', ['as' => 'tipoEqualizacaoTecnicas.edit', 'uses' => 'TipoEqualizacaoTecnicaController@edit'])->middleware("needsPermission:equalizacao_tecnicas.edit");
    });
    $router->get('tipos-equalizacoes-tecnicas/itens/{id}', 'TipoEqualizacaoTecnicaController@buscaItens');
    $router->get('tipos-equalizacoes-tecnicas/anexos/{id}', 'TipoEqualizacaoTecnicaController@buscaAnexos');

    # Outros
    $router->get('filter-json-ordem-compra', 'OrdemDeCompraController@filterJsonOrdemCompra');

    $router->get('planejamentosByObra', 'PlanejamentoController@getPlanejamentosByObra');
    $router->get('planejamentosListaByObra', 'PlanejamentoController@getListaDePlanejamentosByObra');

    $router->get('lembretes', 'PlanejamentoController@lembretes');
    $router->get('lembretes/salvar-data-minima', 'PlanejamentoController@lembretes');


    #Contratos
    $router->get(
        '/solicitacoes-de-entrega/{solicitacao_entrega}',
        'SolicitacaoEntregaController@show'
    )
    ->middleware('needsPermission:contratos.solicitar_entrega')
    ->name('solicitacao-entrega.show');

    $router->get(
        '/solicitacoes-de-entrega/{solicitacao_entrega}/edit',
        'SolicitacaoEntregaController@edit'
    )
    ->middleware('needsPermission:contratos.solicitar_entrega')
    ->name('solicitacao-entrega.edit');

    $router->patch(
        '/solicitacoes-de-entrega/{solicitacao_entrega}',
        'SolicitacaoEntregaController@update'
    )
    ->middleware('needsPermission:contratos.solicitar_entrega')
    ->name('solicitacao-entrega.update');

    $router->post(
        '/solicitacoes-de-entrega/{solicitacao_entrega}/cancelar',
        'SolicitacaoEntregaController@cancel'
    )
    ->middleware('needsPermission:contratos.solicitar_entrega')
    ->name('solicitacao-entrega.cancel');

    $router->get(
        '/solicitacoes-de-entrega/{solicitacao_entrega}/vincular-nota',
        'SolicitacaoEntregaController@vincularNota'
    )
    ->middleware('needsPermission:contratos.solicitar_entrega')
    ->name('solicitacao-entrega.vincular-nota');

    $router->post(
        '/solicitacoes-de-entrega/{solicitacao_entrega}/vincular-nota',
        'SolicitacaoEntregaController@vincularNotaSave'
    )
    ->middleware('needsPermission:contratos.solicitar_entrega')
    ->name('solicitacao-entrega.vincular-nota');

    $router->get(
        '/solicitacoes-de-entrega/imprimir/{solicitacao_entrega}',
        'SolicitacaoEntregaController@imprimirSolicitacaoEntrega'
    )
    ->name('solicitacao-entrega.imprimirSolicitacaoEntrega');

    $router->group(['prefix' => 'contratos','middleware' => 'needsPermission:contratos.list'], function($router) {
        $router->get(
            '',
            ['as' => 'contratos.index', 'uses' => 'ContratoController@index']
        );
        $router->get(
            '/{contratos}/imprimir',
            ['as' => 'contratos.imprimirContrato', 'uses' => 'ContratoController@imprimirContrato']
        );
        $router->get(
            '/{contratos}/imprimir-contrato',
            ['as' => 'contratos.imprimirContratoCompleto', 'uses' => 'ContratoController@imprimirContratoCompleto']
        );
        $router->get(
            '/{contratos}/imprimir-espelho-contrato',
            ['as' => 'contratos.imprimirEspelhoContrato', 'uses' => 'ContratoController@imprimirEspelhoContrato']
        );
        $router->post(
            '/{contratos}/envia-contrato',
            ['as' => 'contratos.enviaContrato', 'uses' => 'ContratoController@validaEnvioContrato']
        );
        $router->get(
            '/atualizar-valor',
            [
                'as' => 'contratos.atualizar-valor',
                'uses' => 'ContratoController@atualizarValor'
            ]
        )->middleware('needsPermission:contratos.edit');
        $router->post(
            '/atualizar-valor',
            [
                'as' => 'contratos.atualizar-valor-save',
                'uses' => 'ContratoController@atualizarValorSave'
            ]
        )->middleware('needsPermission:contratos.edit');
        $router->get(
            '/fornecedores-por-obras',
            'ContratoController@pegaFornecedoresPelasObras'
        )->middleware('needsPermission:contratos.edit');
        $router->get(
            '/insumos-por-fornecedor',
            'ContratoController@insumosPorFornecedor'
        )->middleware('needsPermission:contratos.edit');
        $router->get(
            '/insumo-valor',
            'ContratoController@insumoValor'
        )->middleware('needsPermission:contratos.edit');
        $router->get(
            '/{contratos}',
            ['as' => 'contratos.show', 'uses' => 'ContratoController@show']
        )->middleware('needsPermission:contratos.show');

        $router->get(
            '/{contratos}/solicitar-entrega',
            'ContratoController@solicitarEntrega'
        )
        ->middleware('needsPermission:contratos.solicitar_entrega')
        ->name('contratos.solicitar-entrega');

        $router->post(
            '/{contratos}/solicitar-entrega',
            'ContratoController@solicitarEntregaSave'
        )
        ->middleware('needsPermission:contratos.solicitar_entrega')
        ->name('contratos.solicitar-entrega');

        $router->post(
            '/editar-item/{item}',
            [
                'as' => 'contratos.editar-item',
                'uses' => 'ContratoController@editarItem'
            ]
        )->middleware('needsPermission:contratos.edit');
        $router->post(
            '/reajustar/{contrato_item_id}',
            [
                'as' => 'contratos.reajustar',
                'uses' => 'ContratoController@reajustar'
            ]
        )->middleware('needsPermission:contratos.reajustar');

        $router->post(
            '/reapropriar-item/{item}',
            [
                'as' => 'contratos.reapropriar-item',
                'uses' => 'ContratoController@reapropriarItem'
            ]
        )->middleware('needsPermission:contratos.reapropriar');

        $router->get(
            '/apropriacoes/{item}',
            [
                'uses' => 'ContratoController@apropriacoes'
            ]
        );

        $router->post(
            '/distratar/{contrato_item_id}',
            [
                'as' => 'contratos.distratar',
                'uses' => 'ContratoController@distratar'
            ]
        )->middleware('needsPermission:contratos.distratar');

        $router->get(
            '/{contratos}/editar',
            ['as' => 'contratos.edit', 'uses' => 'ContratoController@edit']
        );
        $router->patch(
            '/{contratos}/update',
            ['as' => 'contratos.update', 'uses' => 'ContratoController@update']
        );

        $router->get(
            '/{contratos}/{insumo_id}/previsao-de-memoria-de-calculo',
            ['as' => 'contratos.memoria_de_calculo', 'uses' => 'ContratoController@memoriaDeCalculo']
        )->middleware('needsPermission:contratos.previsao_de_memoria_de_calculo');

        $router->post(
            '/previsao-de-memoria-de-calculo/salvar',
            ['as' => 'contratos.memoria_de_calculo_salvar', 'uses' => 'ContratoController@memoriaDeCalculoSalvar']
        )->middleware('needsPermission:contratos.previsao_de_memoria_de_calculo');

        $router->post(
            '/previsao-de-memoria-de-calculo/excluir-previsao',
            ['as' => 'contratos.memoria_de_calculo.excluir_previsao', 'uses' => 'ContratoController@memoriaDeCalculoExcluirPrevisao']
        )->middleware('needsPermission:contratos.previsao_de_memoria_de_calculo');
    });

    #LPU
    $router->group(['prefix' => 'lpu','middleware' => 'needsPermission:lpu.list'], function($router) {
        $router->get('',['as' => 'lpu.index', 'uses' => 'LpuController@index']);
        $router->get('/{lpu}',['as' => 'lpu.show', 'uses' => 'LpuController@show'])
			->middleware('needsPermission:lpu.show');
        $router->get('/{lpu}/editar',['as' => 'lpu.edit', 'uses' => 'LpuController@edit']);
        $router->patch('/{lpu}/update',['as' => 'lpu.update', 'uses' => 'LpuController@update']);
		$router->delete('lpu/{lpu}', ['as' => 'lpu.destroy', 'uses' => 'LpuController@destroy']);
    });

    # DocBild
    $router->group(['prefix'=>'qc','middleware' => 'needsPermission:qc.list'], function () use ($router) {
        $router->get('', ['as' => 'qc.index', 'uses' => 'QcController@index']);
        $router->get('/create', ['as' => 'qc.create', 'uses' => 'QcController@create']);
        $router->post('', ['as' => 'qc.store', 'uses' => 'QcController@store']);
        $router->get('/{qc}',['as' => 'qc.show', 'uses' => 'QcController@show'])
            ->middleware('needsPermission:qc.show');
        $router->get('/{qc}/editar',['as' => 'qc.edit', 'uses' => 'QcSuprimentosController@edit']);
        $router->patch('/{qc}/update',['as' => 'qc.update', 'uses' => 'QcSuprimentosController@update']);
        $router->post('', ['as' => 'qc.store', 'uses' => 'QcController@store']);
        $router->get('/buscar/busca_carteiras', ['as' => 'qc.busca_carteiras', 'uses' => 'QcController@buscaCarteira']);
        // $router->delete('/{qc}', ['as' => 'qc.destroy', 'uses' => 'QcController@destroy']);
        $router->get('/anexos/{qc}',['as' => 'qc.anexos', 'uses' => 'QcController@anexos'])
            ->middleware('needsPermission:qc.anexos.list');

        $router->get('/aprovar/{qc}',['as' => 'qc.aprovar.edit', 'uses' => 'QcController@aprovar'])
            ->middleware('needsPermission:qc-aprovar.show');
            $router->patch('/aprovar/{qc}/update',['as' => 'qc.aprovar.update', 'uses' => 'QcController@aprovarUpdate']);
    });

	# Configuracao Estatica
    $router->group(['middleware' => 'needsPermission:configuracaoEstaticas.list'], function () use ($router) {
        $router->get('configuracaoEstaticas', ['as' => 'configuracaoEstaticas.index', 'uses' => 'ConfiguracaoEstaticaController@index']);
        $router->post('configuracaoEstaticas', ['as' => 'configuracaoEstaticas.store', 'uses' => 'ConfiguracaoEstaticaController@store']);
        $router->get('configuracaoEstaticas/create', ['as' => 'configuracaoEstaticas.create', 'uses' => 'ConfiguracaoEstaticaController@create']);
        $router->put('configuracaoEstaticas/{configuracaoEstaticas}', ['as' => 'configuracaoEstaticas.update', 'uses' => 'ConfiguracaoEstaticaController@update'])
            ->middleware('needsPermission:configuracaoEstaticas.edit');
        $router->patch('configuracaoEstaticas/{configuracaoEstaticas}', ['as' => 'configuracaoEstaticas.update', 'uses' => 'ConfiguracaoEstaticaController@update'])
            ->middleware('needsPermission:configuracaoEstaticas.edit');
        $router->delete('configuracaoEstaticas/{configuracaoEstaticas}', ['as' => 'configuracaoEstaticas.destroy', 'uses' => 'ConfiguracaoEstaticaController@destroy']);
        $router->get('configuracaoEstaticas/{configuracaoEstaticas}', ['as' => 'configuracaoEstaticas.show', 'uses' => 'ConfiguracaoEstaticaController@show'])
            ->middleware('needsPermission:configuracaoEstaticas.show');
        $router->get('configuracaoEstaticas/{configuracaoEstaticas}/edit', ['as' => 'configuracaoEstaticas.edit', 'uses' => 'ConfiguracaoEstaticaController@edit'])
            ->middleware('needsPermission:configuracaoEstaticas.edit');
    });

    Route::resource('templateEmails', 'TemplateEmailController');

    $router->get('notasfiscais', ['as' => 'notafiscals.index', 'uses' => 'NotafiscalController@index']);
    $router->post('notasfiscais', ['as' => 'notafiscals.store', 'uses' => 'NotafiscalController@store']);
    $router->get('notasfiscais/create', ['as' => 'notafiscals.create', 'uses' => 'NotafiscalController@create']);
    $router->put('notasfiscais/{notafiscal}', ['as' => 'notafiscals.update', 'uses' => 'NotafiscalController@update']);
    $router->patch('notasfiscais/{notafiscal}', ['as' => 'notafiscals.update', 'uses' => 'NotafiscalController@update']);
    $router->delete('notasfiscais/{notafiscal}', ['as' => 'notafiscals.destroy', 'uses' => 'NotafiscalController@destroy']);
    $router->get('notasfiscais/{notafiscal}', ['as' => 'notafiscals.show', 'uses' => 'NotafiscalController@show']);
    $router->get('notasfiscais/{notafiscal}/edit', ['as' => 'notafiscals.edit', 'uses' => 'NotafiscalController@edit']);
    $router->get('notasfiscais/conciliacao/filtro', ['as' => 'notafiscals.filtro', 'uses' => 'NotafiscalController@filtraFornecedorContratos']);
    $router->get('notasfiscais/pagamentos/filtro/{contrato_id}/{nfe_id}', ['as' => 'notafiscals.pagamentos.filtro', 'uses' => 'NotafiscalController@filtrarPagamentos']);

    $router->get('importaNfe', ['as' => 'nfe.import', 'uses' => 'NotafiscalController@importaNfe']);
    $router->post('importaNfe', ['as' => 'nfe.store', 'uses' => 'NotafiscalController@postImportaNfe']);


    $router->get('capturaNfe', 'NotafiscalController@pescadorNfe');
    $router->get('capturaCte', 'NotafiscalController@buscaCTe');
    $router->get('danfe/{id}', 'NotafiscalController@visualizaDanfe');
    $router->get('dacte/{id}', 'NotafiscalController@visualizaDacte');
    $router->get('integra-mega/{id}', 'NotafiscalController@integraMega');
    $router->get('dacte/v3/{id}', 'NotafiscalController@visualizaDacteV3');
    $router->get('manifestar/nfe', 'NotafiscalController@manifesta');
    $router->get('reprocessaNfe/{id}', 'NotafiscalController@reprocessaNfe');

    # Padrões de empreendimento Novo
    $router->group(['middleware' => 'needsPermission:padraoEmpreendimentos.list'], function () use ($router) {
        $router->get('padroes-de-empreendimento', ['as' => 'padraoEmpreendimentos.index', 'uses' => 'PadraoEmpreendimentoController@index']);
        $router->post('padroes-de-empreendimento', ['as' => 'padraoEmpreendimentos.store', 'uses' => 'PadraoEmpreendimentoController@store'])
            ->middleware('needsPermission:padraoEmpreendimentos.create');
        $router->get('padroes-de-empreendimento/create', ['as' => 'padraoEmpreendimentos.create', 'uses' => 'PadraoEmpreendimentoController@create'])
            ->middleware('needsPermission:padraoEmpreendimentos.create');
        $router->put('padroes-de-empreendimento/{padraoEmpreendimentos}', ['as' => 'padraoEmpreendimentos.update', 'uses' => 'PadraoEmpreendimentoController@update'])
            ->middleware('needsPermission:padraoEmpreendimentos.edit');
        $router->patch('padroes-de-empreendimento/{padraoEmpreendimentos}', ['as' => 'padraoEmpreendimentos.update', 'uses' => 'PadraoEmpreendimentoController@update'])
            ->middleware('needsPermission:padraoEmpreendimentos.edit');
        $router->delete('padroes-de-empreendimento/{padraoEmpreendimentos}', ['as' => 'padraoEmpreendimentos.destroy', 'uses' => 'PadraoEmpreendimentoController@destroy'])
            ->middleware('needsPermission:padraoEmpreendimentos.delete');
        $router->get('padroes-de-empreendimento/{padraoEmpreendimentos}', ['as' => 'padraoEmpreendimentos.show', 'uses' => 'PadraoEmpreendimentoController@show']);
        $router->get('padroes-de-empreendimento/{padraoEmpreendimentos}/edit', ['as' => 'padraoEmpreendimentos.edit', 'uses' => 'PadraoEmpreendimentoController@edit'])
            ->middleware('needsPermission:padraoEmpreendimentos.edit');
    });

    # Regionais
    $router->group(['middleware' => 'needsPermission:regionals.list'], function () use ($router) {
        $router->get('regionais', ['as' => 'regionals.index', 'uses' => 'RegionalController@index']);
        $router->post('regionais', ['as' => 'regionals.store', 'uses' => 'RegionalController@store'])
            ->middleware('needsPermission:regionals.create');
        $router->get('regionais/create', ['as' => 'regionals.create', 'uses' => 'RegionalController@create'])
            ->middleware('needsPermission:regionals.create');
        $router->put('regionais/{regionals}', ['as' => 'regionals.update', 'uses' => 'RegionalController@update'])
            ->middleware('needsPermission:regionals.edit');
        $router->patch('regionais/{regionals}', ['as' => 'regionals.update', 'uses' => 'RegionalController@update'])
            ->middleware('needsPermission:regionals.edit');
        $router->delete('regionais/{regionals}', ['as' => 'regionals.destroy', 'uses' => 'RegionalController@destroy'])
            ->middleware('needsPermission:regionals.delete');
        $router->get('regionais/{regionals}', ['as' => 'regionals.show', 'uses' => 'RegionalController@show']);
        $router->get('regionais/{regionals}/edit', ['as' => 'regionals.edit', 'uses' => 'RegionalController@edit'])
            ->middleware('needsPermission:regionals.edit');
    });

    # condicoes-de-pagamento
    $router->group([
        'prefix'=>'condicoes-de-pagamento',
        // 'middleware' => 'needsPermission:insumos.list'
    ], function () use ($router) {
        $router->get('', ['as' => 'pagamentoCondicaos.index', 'uses' => 'PagamentoCondicaoController@index']);
        $router->get('/{id}', ['as' => 'pagamentoCondicaos.show', 'uses' => 'PagamentoCondicaoController@show']);
        //->middleware("needsPermission:insumos.view");
    });

    # tipos-de-documentos-fiscais
    $router->group([
        'prefix'=>'tipos-de-documentos-fiscais',
        // 'middleware' => 'needsPermission:insumos.list'
    ], function () use ($router) {
        $router->get('', ['as' => 'documentoTipos.index', 'uses' => 'DocumentoTipoController@index']);
        $router->get('/{id}', ['as' => 'documentoTipos.show', 'uses' => 'DocumentoTipoController@show']);
        //->middleware("needsPermission:insumos.view");
    });

    # tipos-de-documentos-financeiros
    $router->group([
        'prefix'=>'tipos-de-documentos-financeiros',
        // 'middleware' => 'needsPermission:insumos.list'
    ], function () use ($router) {
        $router->get('', ['as' => 'documentoFinanceiroTipos.index', 'uses' => 'DocumentoFinanceiroTipoController@index']);
        $router->get('/{id}', ['as' => 'documentoFinanceiroTipos.show', 'uses' => 'DocumentoFinanceiroTipoController@show']);
        //->middleware("needsPermission:insumos.view");
    });

    // Pagamentos
    $router->group(['prefix'=>'pagamentos', 'middleware' => 'needsPermission:pagamentos.list'], function () use ($router) {
        $router->get('', ['as'=> 'pagamentos.index', 'uses' => 'PagamentoController@index']);
        $router->post('', ['as'=> 'pagamentos.store', 'uses' => 'PagamentoController@store'])
            ->middleware('needsPermission:pagamentos.create');
        $router->get('/create', ['as'=> 'pagamentos.create', 'uses' => 'PagamentoController@create'])
            ->middleware('needsPermission:pagamentos.create');
        $router->put('/{pagamentos}', ['as'=> 'pagamentos.update', 'uses' => 'PagamentoController@update'])
            ->middleware('needsPermission:pagamentos.edit');
        $router->patch('/{pagamentos}', ['as'=> 'pagamentos.update', 'uses' => 'PagamentoController@update'])
            ->middleware('needsPermission:pagamentos.edit');
        $router->delete('/{pagamentos}', ['as'=> 'pagamentos.destroy', 'uses' => 'PagamentoController@destroy'])
            ->middleware('needsPermission:pagamentos.delete');
        $router->get('/{pagamentos}', ['as'=> 'pagamentos.show', 'uses' => 'PagamentoController@show']);
        $router->get('/{pagamentos}/edit', ['as'=> 'pagamentos.edit', 'uses' => 'PagamentoController@edit'])
            ->middleware('needsPermission:pagamentos.edit');
        $router->get('/{pagamentos}/integrar', ['as'=> 'pagamentos.integrar', 'uses' => 'PagamentoController@integrar'])
            ->middleware('needsPermission:pagamentos.edit');
    });

	$router->get('/testeLpu', function () {
        $lpu = \App\Repositories\LpuGerarRepository::calcular();
		dd($lpu);
    });

	$router->get('/testeInsumos', function () {
		$insumos = \App\Repositories\ImportacaoRepository::insumos();
	});

    $router->get('/teste', function () {
        //        $grupos_mega = \App\Models\MegaInsumoGrupo::select([
        //            'GRU_IDE_ST_CODIGO',
        //            'GRU_IN_CODIGO',
        //            'GRU_ST_NOME',])
        //            ->where('gru_ide_st_codigo' , '07')
        //            ->first();
        //        dd($grupos_mega);
        //        $servicos = \App\Repositories\ImportacaoRepository::fornecedor_servicos(446);

		$insumos = \App\Repositories\ImportacaoRepository::insumos();
        dd($insumos);

		//        $insumos = \App\Repositories\ImportacaoRepository::insumos();
		//        dd($insumos);
        $contratoTemplate = \App\Models\ContratoTemplate::find(1);
        if($contratoTemplate){
            if($contratoTemplate->campos_extras){
                $campos_extras_template = json_decode($contratoTemplate->campos_extras);
                foreach ($campos_extras_template as $campo){
                    var_dump($campo);
                }
                dd($campos_extras_template);

            }
        }

    });


});

#Image Controller
$router->get('imagem', 'ImageController@index');


$router->get('requisicao/get-pavimentos-obra/{obra}/torre/{torre}', ['as' => 'requisicao.pavimentosObra', 'uses' => 'RequisicaoController@getPavimentosByObraAndTorre']);
$router->get('requisicao/get-trechos-obra/{obra}/torre/{torre}/pavimento/{pavimento}', ['as' => 'requisicao.trechoObra', 'uses' => 'RequisicaoController@getTrechoByObraTorrePavimento']);
$router->get('requisicao/get-andares-obra/{obra}/torre/{torre}/pavimento/{pavimento}', ['as' => 'requisicao.andarObra', 'uses' => 'RequisicaoController@getAndarByObraTorrePavimento']);
$router->get('requisicao/get-insumos', ['as' => 'requisicao.getInsumos', 'uses' => 'RequisicaoController@getInsumos']);
$router->get('requisicao/get-insumos-obra/', ['as' => 'requisicao.insumosObra', 'uses' => 'RequisicaoController@getInsumos']);
$router->get('requisicao/get-insumos-obra-comodo/', ['as' => 'requisicao.insumosObraComodo', 'uses' => 'RequisicaoController@getInsumosByComodo']);

$router->get('/requisicao/ler-qr-cod', function() {
    return View('requisicao.ler_qr_code');
});

$router->resource('requisicao', 'RequisicaoController');

# Processo de Saída
$router->get('requisicao/processo-saida/{requisicao}', ['as' => 'requisicao.processoSaida', 'uses' => 'RequisicaoController@processoSaida']);

$router->get('requisicao/processo-saida/{requisicao}/ler-insumo-saida', ['as' => 'requisicao.lerInsumoSaida', 'uses' => 'RequisicaoController@lerInsumoSaida']);
$router->get('requisicao/processo-saida/ler-insumo-saida/salvar-leitura', ['as' => 'requisicao.salvarLeituraSaida', 'uses' => 'RequisicaoController@salvarLeituraSaida']);
$router->get('requisicao/processo-saida/{requisicao}/lista-de-inconsistencia', ['as' => 'requisicao.listaInconsistencia', 'uses' => 'RequisicaoController@listaInconsistencia']);
$router->get('requisicao/processo-saida/lista-de-inconsistencia/excluir-leitura', ['as' => 'requisicao.excluirLeitura', 'uses' => 'RequisicaoController@excluirLeitura']);
