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
        }
    </style>
@stop
@section('content')
    <section class="content-header">
        <div class="modal-header">
            <div class="col-md-12">
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
                <div class="col-md-12">
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
                </div>
                <div class="col-md-12">
                    <button type="button" class="btn btn-flat btn-warning btn-sm" id="filtrar"><i class="fa fa-filter"></i> Filtrar</button>
                </div>
            </div>
        </div>
    </section>
    <div class="content">
        @include('ordem_de_compras.insumos-aprovados-table')
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();

        $('.js-datatable-filter-form :input').on('change', function (e) {
            window.LaravelDataTables["dataTableBuilder"].draw();
        });

        $('#dataTableBuilder').on('preXhr.dt', function ( e, settings, data ) {
            $('.js-datatable-filter-form :input').each(function () {
                data[$(this).prop('name')] = $(this).val();
            });
        });

        $('#filtrar').on('submit', function(e) {
            window.LaravelDataTables.draw();
            e.preventDefault();
        });
    });
</script>
@stop