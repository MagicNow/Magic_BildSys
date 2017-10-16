@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>

            Estoque mínimo
        </h1>

        <div class="box box-muted">
            <div class="box-body">
                <div class="row">
                    <div class="form-group col-sm-4">
                        {!! Form::label('obra_id', 'Obra:') !!}
                        {!! Form::select('obra_id', $obras, null, ['class' => 'form-control select2', 'onchange' => 'filter();']) !!}
                    </div>

                    <div class="form-group col-sm-4">
                        {!! Form::label('insumo_grupo_id', 'Grupo de insumo:') !!}
                        {!! Form::select('insumo_grupo_id', $grupo_insumos, null, ['class' => 'form-control select2', 'onchange' => 'filter();']) !!}
                    </div>

                    <div class="form-group col-sm-4">
                        {!! Form::label('insumo_id', 'Insumo:') !!}
                        {!! Form::select('insumo_id', $insumos, null, ['class' => 'form-control select2', 'onchange' => 'filter();']) !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <table id="fixTable" class="table table-bordered table-striped table-condensed table-nowrap">
                    <thead>
                    <tr align="left">
                        <th>Obra</th>
                        <th>Contrato</th>
                        <th>Cod</th>
                        <th>Insumo</th>
                        <th>Un de medida</th>
                        <th>Qtd mínima</th>
                    </tr>
                    </thead>

                    <tbody>
                        @foreach($itens as $item)
                            <tr align="left">
                                <td>{{$item['obra']}}</td>
                                <td>{{$item['contrato_id']}}</td>
                                <td>{{$item['codigo']}}</td>
                                <td>{{$item['insumo']}}</td>
                                <td>{{$item['unidade_medida']}}</td>
                                <td>
                                    <input value="{{float_to_money($item['qtd_minima'], '')}}" class="money">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function filter() {
            startLoading();

            var queryString = '';
            var obra_id = $('#obra_id').val();
            var insumo_id = $('#insumo_id').val();
            var insumo_grupo_id = $('#insumo_grupo_id').val();

            if(obra_id){
                queryString ='?obra_id=' + obra_id;
            }

            if(insumo_id){
                if(queryString.length>0){
                    queryString +='&';
                }else{
                    queryString +='?';
                }
                queryString +='insumo_id=' + insumo_id;
            }

            if(insumo_grupo_id){
                if(queryString.length>0){
                    queryString +='&';
                }else{
                    queryString +='?';
                }
                queryString +='insumo_grupo_id=' + insumo_grupo_id;
            }

            history.pushState("", document.title, location.pathname+queryString);
            location.reload();
        }
    </script>
@endsection