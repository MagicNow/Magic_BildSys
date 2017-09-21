@shield('users.list')
<li class="{{ Request::is('users*') ? 'active' : '' }}">
    <a href="{!! route('manage.index') !!}"><i class="fa fa-users"></i><span>Controle de Acesso</span></a>
</li>
@endshield

<li class="treeview {{ Request::is('admin/orcamento*') ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-balance-scale"></i> <span>Orçamento</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        @shield('orcamentos.list')
        <li class="{{ Request::is('admin/orcamentos*') ? 'active' : '' }}">
            <a href="{!! route('admin.orcamentos.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Orçamentos</span>
            </a>
        </li>
        @endshield
        @shield('orcamentos.import')
        <li class="{{ Request::is('admin/orcamento') ? 'active' : '' }}">
            <a href="{!! route('admin.orcamentos.indexImport') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Importação</span>
            </a>
        </li>
        @endshield
    </ul>
</li>

<li class="treeview {{ Request::is('admin/orcamento*') || Request::is('admin/mascara_padrao_insumos*')  ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-balance-scale"></i> <span>Pré-Orçamento</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu"> 
		@shield('mascara_padrao_insumos.list')
        <li class="{{ Request::is('admin/mascara_padrao_insumos') ? 'active' : '' }}">
            <a href="{!! route('admin.mascara_padrao_insumos.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Máscara Padrão/Insumos</span>
            </a>
        </li>
        @endshield	
		@shield('tarefa_mascaras.list')
        <li class="{{ Request::is('admin/tarefa_mascaras') ? 'active' : '' }}">
            <a href="{!! route('admin.tarefa_mascaras.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Tarefa Padrão/Máscaras P.</span>
            </a>
        </li>
        @endshield
        @shield('orcamentos.list')
        <li class="{{ Request::is('admin/orcamento') ? 'active' : '' }}">
            <a href="{!! route('admin.orcamentos.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Pré Orçamento</span>
            </a>
        </li>
        @endshield
		@shield('orcamentos.list')
        <li class="{{ Request::is('admin/orcamento') ? 'active' : '' }}">
            <a href="{!! route('admin.orcamentos.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Índices</span>
            </a>
        </li>
        @endshield
		@shield('orcamentos.list')
        <li class="{{ Request::is('admin/orcamento') ? 'active' : '' }}">
            <a href="{!! route('admin.orcamentos.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Exportar Insumos/Estrutura</span>
            </a>
        </li>
        @endshield
    </ul>
</li>

<li class="treeview {{ Request::is('admin/levantamentos*') ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-pencil fa-fw"></i> <span>Levantamentos</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">		
        @shield('levantamentos.list')
        <li class="{{ Request::is('admin/levantamentos*') ? 'active' : '' }}">
            <a href="{!! route('admin.levantamentos.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Levantamentos</span>
            </a>
        </li>
        @endshield
		@shield('tipoLevantamentos.list')
        <li class="{{ Request::is('admin/tipo_levantamentos*') ? 'active' : '' }}">
            <a href="{!! route('admin.tipo_levantamentos.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Tipos Levantamentos</span>
            </a>
        </li>
        @endshield
		
		@shield('levantamentos.list')
        <li class="{{ Request::is('admin/estruturas*') ? 'active' : '' }}">
            <a href="{!! route('admin.estruturas.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Mascara Estrutura</span>
            </a>
        </li>
        @endshield
        @shield('levantamentos.import')
        <li class="{{ Request::is('admin/levantamentos*') ? 'active' : '' }}">
            <a href="{!! route('admin.levantamentos.indexImport') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Importação</span>
            </a>
        </li>
        @endshield
    </ul>
</li>

<li class="treeview {{ Request::is('admin/cronogramaFisicos*') ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-book fa-fw"></i> <span>Planejamento Físicos</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
		@shield('cronogramaFisicos.dashboard')
        <li class="{{ Request::is('admin/cronogramaFisicos*') ? 'active' : '' }}">
            <a href="{!! route('admin.cronograma_fisicos.relSemanal') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Acompanhamento Semanal</span>
            </a>
        </li>
        @endshield
		@shield('cronogramaFisicos.dashboard')
        <li class="{{ Request::is('admin/cronogramaFisicos*') ? 'active' : '' }}">
            <a href="{!! route('admin.cronograma_fisicos.relMensal') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Acompanhamento Mensal</span>
            </a>
        </li>
        @endshield
        @shield('cronogramaFisicos.list')
        <li class="{{ Request::is('admin/cronogramaFisicos*') ? 'active' : '' }}">
            <a href="{!! route('admin.cronograma_fisicos.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Cronograma Físicos</span>
            </a>
        </li>
        @endshield		
        @shield('cronogramaFisicos.import')
        <li class="{{ Request::is('admin/cronogramaFisicos*') ? 'active' : '' }}">
            <a href="{!! route('admin.cronogramaFisicos.indexImport') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Importação</span>
            </a>
        </li>
        @endshield
    </ul>
</li>

<li class="treeview {{ Request::is('admin/planejamento*') ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-calendar"></i> <span>Cronograma de Obras</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Request::is('admin/planejamentos/planejamentoOrcamentos*') ? 'active' : '' }}">
            <a href="{!! route('admin.planejamentoOrcamentos.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Tarefa/Orçamentos</span>
            </a>
        </li>
        @shield('cronograma_por_obras.list')
        <li class="{{ Request::is('admin/planejamentoCronogramas*') ? 'active' : '' }}">
            <a href="{!! route('admin.planejamentoCronogramas.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Cronograma por obra</span>
            </a>
        </li>
        @endshield
        @shield('cronograma_de_obras.list')
        <li class="{{ Request::is('admin/planejamentos') ? 'active' : '' }}"><a
                    href="{!! route('admin.planejamentos.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Cronograma de obra</span>
            </a>
        </li>
        @endshield
        @shield('planejamento.import')
        <li class="{{ Request::is('admin/planejamento') ? 'active' : '' }}"><a
                    href="{!! route('admin.planejamentos.indexImport') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Importação</span>
            </a>
        </li>
        @endshield
    </ul>
</li>

{{-- **** SUPRIMENTOS  **** --}}
<li class="treeview {{ Request::is('admin/lembretes*') || Request::is('admin/compradorInsumos*') || Request::is('admin/contratoTemplates*') || Request::is('desistenciaMotivos*')  ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-diamond"></i> <span>Suprimentos</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        @shield('lembretes.list')
        <li class="{{ Request::is('admin/lembretes*') ? 'active' : '' }}"><a
                    href="{!! route('admin.lembretes.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Lembretes</span></a>
        </li>
        @endshield
        @shield('compradorInsumos.list')
        <li class="{{ Request::is('admin/compradorInsumos*') ? 'active' : '' }}">
            <a href="{!! route('admin.compradorInsumos.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Comprador/Insumos</span>
            </a>
        </li>
        @endshield
		@shield('carteiraInsumos.list')
        <li class="{{ Request::is('admin/carteiraInsumos*') ? 'active' : '' }}">
            <a href="{!! route('admin.carteiraInsumos.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Carteira/Insumos</span>
            </a>
        </li>
        @endshield
        @shield('contratoTemplates.list')
        <li class="{{ Request::is('admin/contratoTemplates*') ? 'active' : '' }}">
            <a href="{!! route('admin.contratoTemplates.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Templates de Minutas</span>
            </a>
        </li>
        @endshield
        @shield('desistenciaMotivos.list')
        <li class="{{ Request::is('desistenciaMotivos*') ? 'active' : '' }}">
            <a href="{!! route('admin.desistenciaMotivos.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Motivos declinar proposta</span>
            </a>
        </li>
        @endshield		
    </ul>
</li>

<li class="treeview {{ Request::is('admin/workflow*') ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-check-square"></i> <span>Workflow</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        @shield('alcadas.list')
        <li class="{{ Request::is('admin/workflow/workflow-alcadas*') ? 'active' : '' }}"><a
                    href="{!! route('admin.workflowAlcadas.index') !!}"><i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Alçadas</span>
            </a>
        </li>
        @endshield
        @shield('motivos_reprovacao.list')
        <li class="{{ Request::is('admin/workflow/reprovacao-motivos*') ? 'active' : '' }}">
            <a href="{!! route('admin.workflowReprovacaoMotivos.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>
                   Motivos de Reprovação
                </span>
            </a>
        </li>
        @endshield
    </ul>
</li>

<li class="treeview {{ Request::is('admin/templatePlanilhas*') ||
                       Request::is('configuracaoEstaticas*')
                       ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-table"></i> <span>Templates</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        @shield('template_planilhas.list')
        <li class="{{ Request::is('admin/templatePlanilhas*') ? 'active' : '' }}">
            <a href="{!! route('admin.templatePlanilhas.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Template de planilhas</span>
            </a>
        </li>
        @endshield


        @shield('configuracaoEstaticas.list')
        <li class="{{ Request::is('configuracaoEstaticas*') ? 'active' : '' }}">
            <a href="{!! route('configuracaoEstaticas.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Mensagens Inform.</span></a>
        </li>
        @endshield

        <li class="{{ Request::is('templateEmails*') ? 'active' : '' }}">
            <a href="{!! route('templateEmails.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Template Emails</span>
            </a>
        </li>

    </ul>
</li>

<li class="treeview {{ Request::is('admin/topologia*') ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-building"></i> <span>Topologia</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        @shield('topologia.list')
        <li class="{{ Request::is('admin/topologia*') ? 'active' : '' }}">
            <a href="{!! route('admin.topologia.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Topologias</span>
            </a>
        </li>
        @endshield
    </ul>
</li>