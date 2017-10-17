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
                    <div class="col-md-3">
                        <h4>Regionais</h4>

                        <div class="js-datatable-filter-form">
                            @if(count($regionais))
                            {!! Form::select('regionais[]',$regionais, null, [
                                'class'=>'form-control select2',
                                'multiple'=>'multiple',
                                ]) !!}
                            @else
                                Sem registros vinculados
                            @endif
                        </div>

                    </div>

                    <div class="col-md-3">
                        <h4>Padrões de empreendimento</h4>
                        <div class="js-datatable-filter-form">
                            @if(count($padroes_empreendimento))
                                {!! Form::select('padroes_empreendimento[]',$padroes_empreendimento, null, [
                                    'class'=>'form-control select2',
                                    'multiple'=>'multiple',
                                    ]) !!}
                            @else
                                Sem registros vinculados
                            @endif
                        </div>
                    </div>

                    <div class="col-md-3">
                        <h4>Obras</h4>
                        <div class="js-datatable-filter-form">
                            @if(count($padroes_empreendimento))
                                {!! Form::select('obras[]',$obras, null, [
                                    'class'=>'form-control select2',
                                    'multiple'=>'multiple',
                                    ]) !!}
                            @else
                                Sem registros vinculados
                            @endif
                        </div>
                    </div>

                    <div class="col-md-3">
                        <h4>Compradores</h4>
                        <div class="js-datatable-filter-form">
                            @if(count($compradores))
                                {!! Form::select('compradores[]',$compradores, null, [
                                    'class'=>'form-control select2',
                                    'multiple'=>'multiple',
                                    ]) !!}
                            @else
                                Sem registros vinculados
                            @endif
                        </div>
                    </div>

                    <div class="col-md-3">
                        <h4>Cidades</h4>
                        <div class="js-datatable-filter-form">
                            @if(count($cidades))
                                {!! Form::select('cidades[]',$cidades, null, [
                                    'class'=>'form-control select2',
                                    'multiple'=>'multiple',
                                    ]) !!}
                            @else
                                Sem registros vinculados
                            @endif
                        </div>
                    </div>

                    <div class="col-md-3">
                        <h4>Grupos de Insumos</h4>
                        <div class="js-datatable-filter-form">
                            @if(count($insumoGrupos))
                                {!! Form::select('insumo_grupos[]',$insumoGrupos, null, [
                                    'class'=>'form-control select2',
                                    'multiple'=>'multiple',
                                    ]) !!}
                            @else
                                Sem registros vinculados
                            @endif
                        </div>
                    </div>

                    <div class="col-md-3">
                        <h4>Insumos</h4>
                        <div class="js-datatable-filter-form">
                            @if(count($insumos))
                                {!! Form::select('insumos[]',$insumos, null, [
                                    'class'=>'form-control select2',
                                    'multiple'=>'multiple',
                                    ]) !!}
                            @else
                                Sem registros vinculados
                            @endif
                        </div>
                    </div>
					
					<div class="col-md-3">
                        <h4>Carteiras</h4>
                        <div class="js-datatable-filter-form">
                            @if(count($carteiras))
                                {!! Form::select('carteiras[]',$carteiras, null, [
                                    'class'=>'form-control select2',
                                    'multiple'=>'multiple',
                                    ]) !!}
                            @else
                                Sem registros vinculados
                            @endif
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-md-1">
                        <h4>O.C.s</h4>
                        <div class="js-datatable-filter-form">
                            @if(count($OCs))
                                {!! Form::select('ocs[]',$OCs, null, [
                                    'class'=>'form-control select2',
                                    'multiple'=>'multiple',
                                    ]) !!}
                            @else
                                Sem registros vinculados
                            @endif
                        </div>
                    </div>

                    <div class="col-md-2">
                        <h4>SLA</h4>
                        <div class="js-datatable-filter-form">
                            @if(count($farol))
                                {!! Form::select('farol[]',$farol, null, [
                                    'class'=>'form-control select2',
                                    'multiple'=>'multiple',
                                    ]) !!}
                            @else
                                Sem registros vinculados
                            @endif
                        </div>
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

        $('.js-datatable-filter-form .select2').on('select2:select', function (evt) {
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

    function dispensarInsumoAprovado(ordem_de_compra_item_id, nome) {
        swal({
                    title: "Dispensar item de O.C.?",
                    text: "Insumo: " + nome + "<br><hr><label>Escreva uma justificativa</label>: ",
                    html: true,
                    type: "input",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    animation: "slide-from-top",
                    inputPlaceholder: "Justificativa",
                    showLoaderOnConfirm: true,
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Dispensar',
                    confirmButtonColor: '#DD6B55'
                },
                function(justificativa_texto) {
                    if (justificativa_texto === false) return false;

                    if (justificativa_texto === "") {
                        swal.showInputError("Escreva uma justificativa!");
                        return false
                    }
                    var data = {
                        id: ordem_de_compra_item_id,
                        justificativa: justificativa_texto
                    };


                    return $.ajax('{{ url("ordens-de-compra/dispensar-insumo-aprovado") }}', {
                        data: data
                    }).done(function(retorno) {
                        if (retorno.success) {
                            swal({
                                        title: 'Dispensado',
                                        text: 'Item de O.C. dispensado!',
                                        type: "success",
                                        showCancelButton: false,
                                        confirmButtonColor: "#7ED32C",
                                        confirmButtonText: "Ok",
                                        closeOnConfirm: true
                                    },
                                    function() {
                                        window.LaravelDataTables["dataTableBuilder"].draw();
                                        swal.close();
                                    });

                        } else {
                            swal({
                                        title: 'Oops',
                                        text: retorno.erro,
                                        type: "error",
                                        showCancelButton: false,
                                        confirmButtonColor: "#7ED32C",
                                        confirmButtonText: "Ok",
                                        closeOnConfirm: true
                                    },
                                    function() {
                                        swal.close();
                                    });
                        }
                    })
                            .fail(function(retorno) {

                                erros = '';
                                $.each(retorno.responseJSON, function(index, value) {
                                    if (erros.length) {
                                        erros += '<br>';
                                    }
                                    erros += value;
                                });
                                swal("Oops", erros, "error");
                            });
                });
    }
</script>
@stop