@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Gestão de estoque
        </h1>

        @include('gestao_estoque.partials_index.filters')
    </section>

    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <table id="fixTable" class="table table-bordered table-striped table-condensed table-nowrap">
                    <thead>
                        @include('gestao_estoque.partials_index.thead')
                    </thead>

                    <tbody>
                        @include('gestao_estoque.partials_index.tbody')
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            $("#fixTable").tableHeadFixer({'left' : 8, 'head' : true});
        });

        function mudaVisao(visao) {
            if(visao == 'E') {
                $class = 'btn btn-warning';
            } else if(visao == 'P') {
                $class = 'btn btn-success';
            } else {
                $class = 'btn btn-primary';
            }

            if($('#visao_'+visao).attr('class') == 'btn btn-default') {
                $('#visao_'+visao).attr('class', $class);
                $('.class_visao_'+visao).show();

                if(visao == 'E') {
                    $('#td_2').attr('colspan', '1');
                } else if(visao == 'P') {
                    $('#td_3').attr('colspan', '6');
                }

            } else {
                $('#visao_'+visao).attr('class', 'btn btn-default');
                $('.class_visao_'+visao).hide();

                if(visao == 'E') {
                    $('#td_2').attr('colspan', '3');
                } else if(visao == 'P') {
                    $('#td_3').attr('colspan', '4');
                }
            }
        }

        function filter() {
            startLoading();
            
            var queryString = '';
            var obra_id = $('#obra_id').val();
            var insumo_id = $('#insumo_id').val();

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

            history.pushState("", document.title, location.pathname+queryString);
            location.reload();
        }
    </script>
@endsection