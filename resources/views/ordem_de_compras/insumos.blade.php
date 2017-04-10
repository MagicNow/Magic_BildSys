@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Lista de Insumos
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div id="root" class="box box-primary">

            <div class="box-body">
                <div class="row">
                    <div class="col-md-2">
                        Filtros
                    </div>
                    <div class="col-md-10">

                    </div>
                </div>

                <div>
                    {{--<div id="insumos">
                        <!-- demo root element -->

                        <form id="search">
                            Search <input name="query" v-model="searchQuery">
                        </form>
                        <generic-grid
                                :data="gridData"
                                :columns="gridColumns"
                                :filter-key="searchQuery">
                        </generic-grid>
                    </div>
                    <div class="text-center">
                        <generic-paginator :pagination="pagination" :callback="loadData" :options="paginationOptions"></generic-paginator>
                    </div>--}}

                    <div id="app" class="col-md-8 col-md-offset-2">
                        <v-client-table
                                :data="tableData"
                                :columns="columns"
                                :options="options">
                        </v-client-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        /*const app = new Vue({
            el: '#root',
            data: {
                searchQuery: '',
                gridColumns: ['codigo', 'descricao', 'servico', '#'],
                gridData: [

                ],
                pagination: {
                    total: 0,
                    per_page: 12,    // required
                    current_page: 1, // required
                    last_page: 0,    // required
                    from: 1,
                    to: 12           // required
                },
                paginationOptions: {
                    offset: 4,
                    previousText: 'Anterior',
                    nextText: 'Proxima',
                    alwaysShowPrevNext: true
                }
            },
            methods:{
                loadData(){
                    let options = {
                        params: {
                            paginate: this.pagination.per_page,
                            page: this.pagination.current_page,
                            /!* additional parameters *!/
                        }
                    };
                    $.getJSON('{{ url('compras/insumos/lista') }}', options.params, function (response) {
                        app.gridData = response.data;
                        app.pagination.total = response.total;
                        app.pagination.last_page = response.last_page;
                        app.pagination.current_page = response.current_page;
                        app.pagination.from = response.from;
                        app.pagination.to = response.to;
                    });
                }
            }
            ,
            created: function () {
                this.loadData();
            }
        });*/
    </script>

@stop