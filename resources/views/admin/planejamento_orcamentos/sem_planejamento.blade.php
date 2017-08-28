@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Insumos que não tem planejamento de compra associado
		</h1>


        <div class="js-datatable-filter-form pull-right form-group col-sm-3">

            <select name="obra" id="obra" class="select2">
                <option value="0">-- Selecione a Obra --</option>

                @foreach($obras as $k => $v)
                    <option value="{{ $k }}">{{ $v }}</option>
                @endforeach

            </select>

        </div>

        <br>


    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">

                @include('admin.planejamento_orcamentos.sem_planejamento_table')

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">

        $(function () {

            $('#obra').on('change', function (event) {
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

