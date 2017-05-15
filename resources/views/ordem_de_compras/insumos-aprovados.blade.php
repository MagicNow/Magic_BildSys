@extends('layouts.front')
@section('styles')
    <style type="text/css">

        #totalInsumos h5{
            font-weight: bold;
            color: #4a4a4a;
            font-size: 13px;
            margin: 0 10px;
            opacity: 0.5;
            text-transform: uppercase;
        }
        #totalInsumos h4{
            font-weight: bold;
            margin: 0 10px;
            color: #4a4a4a;
            font-size: 22px;
        }
        #totalInsumos{
            margin-bottom: 20px;
        }
        .tooltip-inner {
             max-width: 500px;
             text-align: left !important;
         }
        .bloco_filtro{
            height: 100px;
            overflow-y: scroll;
            border: solid 2px #474747;
            background-color: #fff;
            font-size: 12px;
        }
        .list-group-item{
            border-radius: 0px !important;
        }
    </style>
@stop
@section('content')
    <section class="content-header">
        <div class="modal-header">
            <div class="row">
                <div class="col-md-12">
                    <span class="pull-left title">
                        <h3>
                            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                             <i class="fa fa-arrow-left" aria-hidden="true"></i>
                            </button>
                            <span>Lista de OC/Insumos aprovados</span>
                        </h3>
                    </span>
                </div>



                <div class="row">
                    <div class="col-md-2">
                        <h4>Obras</h4>
                        @if(count($obras))
                        <ul class="list-group bloco_filtro js-datatable-filter-form">
                            @foreach($obras as $obra_id => $obra_nome)
                                <li class="list-group-item">
                                    {!! Form::checkbox('obras[]', $obra_id, null,['id'=>'filter_obra_'.$obra_id]) !!}
                                    <label for="filter_obra_{{ $obra_id }}">{{ $obra_nome }}</label>
                                </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>

                    <div class="col-md-1">
                        <h4>O.C.s</h4>
                        @if(count($OCs))
                            <ul class="list-group bloco_filtro js-datatable-filter-form">
                                @foreach($OCs as $oc_id)
                                    <li class="list-group-item">
                                        {!! Form::checkbox('ocs[]', $oc_id, null,['id'=>'filter_oc_'.$oc_id]) !!}
                                        <label for="filter_oc_{{ $oc_id }}">{{ $oc_id }}</label>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <div class="col-md-2">
                        <h4>Grupos de Insumos</h4>
                        @if(count($insumoGrupos))
                            <ul class="list-group bloco_filtro js-datatable-filter-form">
                                @foreach($insumoGrupos as $insumo_grupo_id => $insumo_grupo_nome)
                                    <li class="list-group-item">
                                        {!! Form::checkbox('insumo_grupos[]', $insumo_grupo_id, null,['id'=>'filter_insumo_grupo_'.$insumo_grupo_id]) !!}
                                        <label for="filter_insumo_grupo_{{ $insumo_grupo_id }}">{{ $insumo_grupo_nome }}</label>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <div class="col-md-3">
                        <h4>Insumos</h4>
                        @if(count($insumos))
                            <ul class="list-group bloco_filtro js-datatable-filter-form">
                                @foreach($insumos as $insumo_id => $insumo_nome)
                                    <li class="list-group-item">
                                        {!! Form::checkbox('insumos[]', $insumo_id, null,['id'=>'filter_insumo_'.$insumo_id]) !!}
                                        <label for="filter_insumo_{{ $insumo_id }}">{{ $insumo_nome }}</label>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <div class="col-md-3">
                        <h4>Cidades</h4>
                        @if(count($cidades))
                            <ul class="list-group bloco_filtro js-datatable-filter-form">
                                @foreach($cidades as $cidade_id => $cidade_nome)
                                    <li class="list-group-item">
                                        {!! Form::checkbox('cidades[]', $cidade_id, null,['id'=>'filter_cidade_'.$cidade_id]) !!}
                                        <label for="filter_cidade_{{ $cidade_id }}">{{ $cidade_nome }}</label>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>


                    <div class="col-md-1">
                        <h4>Farol</h4>
                        @if(count($farol))
                            <ul class="list-group bloco_filtro js-datatable-filter-form">
                                @foreach($farol as $range => $signal)
                                    <li class="list-group-item">
                                        {!! Form::checkbox('farol[]', $range, null,['id'=>'filter_oc_'.str_replace(',','_',$range)]) !!}
                                        <label for="filter_oc_{{ str_replace(',','_',$range) }}">{!! $signal !!}</label>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        {!! Form::open(['url'=>(Request::has('qc')? '/quadro-de-concorrencia/'.Request::get('qc').'/adicionar' : '/quadro-de-concorrencia/criar'),'method'=>'post']) !!}
        @include('ordem_de_compras.insumos-aprovados-table')
        <div class="row" style="margin-top: 15px">
            <div class="col-md-12 text-right">
                @if(Request::has('qc'))
                        <a type="button" href="{{ url('/quadro-de-concorrencia/'.Request::get('qc').'/edit') }}"
                           class="btn btn-default btn-flat btn-lg"><i class="fa fa-times"></i>
                            Cancelar
                        </a>
                @endif
                <button type="submit" class="btn btn-flat btn-lg btn-success">{{ Request::has('qc')? 'Adicionar no Q.C. '.Request::get('qc') : 'Criar Q.C.' }}</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@endsection
@section('scripts')
<script type="text/javascript">
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();

        $('.js-datatable-filter-form :input').on('change', function (e) {
            window.LaravelDataTables["dataTableBuilder"].draw();
        });

        $('.js-datatable-filter-form input').on('ifChanged', function(event) {
            window.LaravelDataTables["dataTableBuilder"].draw();
        });

        $('#dataTableBuilder').on('preXhr.dt', function ( e, settings, data ) {
            $('.js-datatable-filter-form :input').each(function () {
                if($(this).attr('type')=='checkbox'){
                    if(data[$(this).prop('name')]==undefined){
                        data[$(this).prop('name')] = [];
                    }
                    if($(this).is(':checked')){
                        data[$(this).prop('name')].push($(this).val());
                    }

                }else{
                    data[$(this).prop('name')] = $(this).val();
                }
            });
        });
    });
</script>
@stop