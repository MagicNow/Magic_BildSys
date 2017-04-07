@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Compras
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body" id='app'>

                <!-- Roles list -->
                <div class="col-sm-12" id="roles-list-container">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <span class="glyphicon glyphicon-align-justify"></span>
                                Obras Insumos
                            </h3>
                        </div>
                        {{--<div class="panel-body" v-show="dados.length > 0">--}}
                            <tabela api-url="/insumos_json" v-bind:colunas="[{campo_db: 'id', label: 'identificador'},{campo_db: 'nome', label: 'nomezinho'}]">
                        {{--</div>--}}
                        {{--<div class="panel-footer" v-show="dados.length > 0">--}}
                            {{--@include('vendor.pagination.vue-pagination')--}}
                        {{--</div>--}}

                        {{--<div class="panel-body" v-show="dados.length === 0">--}}
                        <span class="text-danger text-center">
                            <strong>Não há papéis registrados</strong>
                        </span>
                        </div>
                    </div>
                </div>
                <!--/Roles list -->
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="js/table.js"></script>
    <script>
//        new Vue({
//            el: '#app',
//            data: {
//                insumos: [],
//                success: '',
//                error: '',
//                pagination: {
//                    total: 0,
//                    per_page: 10,
//                    from: 1,
//                    to: 0,
//                    current_page: 1
//                },
//                offset: 4
//            },
//            computed: {
//                isActived: function () {
//                    return this.pagination.current_page;
//                },
//                pagesNumber: function () {
//                    if (!this.pagination.to) {
//                        return [];
//                    }
//                    var from = this.pagination.current_page - this.offset;
//                    if (from < 1) {
//                        from = 1;
//                    }
//                    var to = from + (this.offset * 2);
//                    if (to >= this.pagination.last_page) {
//                        to = this.pagination.last_page;
//                    }
//                    var pagesArray = [];
//                    while (from <= to) {
//                        pagesArray.push(from);
//                        from++;
//                    }
//                    return pagesArray;
//                }
//            },
//            methods: {
//                exists: function(permissionObj) {
//                    var keyNames = this.role.permissions.map(function(item) { return item["name"]; });
//                    return $.inArray( permissionObj.name, keyNames );
//                },
//                loadInsumos: function (page) {
//                    this.success = '';
//                    this.error = '';
//
//                    startLoading();
//                    this.$http.get('/insumos_json', {
//                        params: { page: page, planejamento_id: 1 }
//                    }).then(function(resp) {
//                        if(typeof resp.data == 'object') {
//                            this.insumos = resp.data.data;
//                            console.log(this.insumos[0]['nome']);
//                            this.pagination = resp.data;
//                        } else if (typeof resp.data =='string') {
//                            var response=jQuery.parseJSON(resp.data);
//                            this.insumos      = response.data;
//                            this.pagination = response;
//                        }
//                        stopLoading();
//                    });
//                },
//                changePage: function (page) {
//                    this.pagination.current_page = page;
//                    this.loadInsumos(page);
//                }
//            },
//            created: function() {
//                this.loadInsumos(1);
//            }
//        });
    </script>
@stop