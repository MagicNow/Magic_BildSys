@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Lista de Insumos
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    <div class="col-md-2">
                        Filtros
                    </div>
                    <div class="col-md-10">

                    </div>
                </div>

                <div>
                    <div id="insumos">
                        <!-- demo root element -->
                        <div id="demo">
                            <form id="search">
                                Search <input name="query" v-model="searchQuery">
                            </form>
                            <demo-grid
                                    :data="gridData"
                                    :columns="gridColumns"
                                    :filter-key="searchQuery">
                            </demo-grid>
                        </div>
                        {{--<v-server-table url="{{ url('compras/insumos/lista') }}" :columns="columns" :options="options"></v-server-table>--}}
                    </div>

                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                        <tr>
                            <th width="20%">Insumo</th>
                            <th>Descrição</th>
                            <th>Serviço</th>
                            <th width="10%"></th>
                        </tr>
                        </thead>
                        <tbody id="insumosList">
                        <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>@fat</td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td>Larry</td>
                            <td>the Bird</td>
                            <td>@twitter</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <!-- component template -->
    <script type="text/x-template" id="grid-template">
        <table class="table table-bordered table-striped table-hover">
            <thead>
            <tr>
                <th v-for="key in columns"
                @click="sortBy(key)"
                :class="{ active: sortKey == key }">
                @{{ key | capitalize }}
                <span class="arrow" :class="sortOrders[key] > 0 ? 'asc' : 'dsc'">
          </span>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="entry in filteredData">
                <td v-for="key in columns">
                    @{{entry[key]}}
                </td>
            </tr>
            </tbody>
        </table>
    </script>

    <script type="text/javascript">
        $(function () {
            // register the grid component
            Vue.component('demo-grid', {
                template: '#grid-template',
                props: {
                    data: Array,
                    columns: Array,
                    filterKey: String
                },
                data: function () {
                    var sortOrders = {}
                    this.columns.forEach(function (key) {
                        sortOrders[key] = 1
                    })
                    return {
                        sortKey: '',
                        sortOrders: sortOrders
                    }
                },
                computed: {
                    filteredData: function () {
                        var sortKey = this.sortKey
                        var filterKey = this.filterKey && this.filterKey.toLowerCase()
                        var order = this.sortOrders[sortKey] || 1
                        var data = this.data
                        if (filterKey) {
                            data = data.filter(function (row) {
                                return Object.keys(row).some(function (key) {
                                    return String(row[key]).toLowerCase().indexOf(filterKey) > -1
                                })
                            })
                        }
                        if (sortKey) {
                            data = data.slice().sort(function (a, b) {
                                a = a[sortKey]
                                b = b[sortKey]
                                return (a === b ? 0 : a > b ? 1 : -1) * order
                            })
                        }
                        return data
                    }
                },
                filters: {
                    capitalize: function (str) {
                        return str.charAt(0).toUpperCase() + str.slice(1)
                    }
                },
                methods: {
                    sortBy: function (key) {
                        this.sortKey = key
                        this.sortOrders[key] = this.sortOrders[key] * -1
                    }
                }
            })

// bootstrap the demo
            var demo = new Vue({
                el: '#demo',
                data: {
                    searchQuery: '',
                    gridColumns: ['name', 'power'],
                    gridData: [
                        { name: 'Chuck Norris', power: Infinity },
                        { name: 'Bruce Lee', power: 9000 },
                        { name: 'Jackie Chan', power: 7000 },
                        { name: 'Jet Li', power: 8000 }
                    ]
                }
            });
        });

    </script>
@stop