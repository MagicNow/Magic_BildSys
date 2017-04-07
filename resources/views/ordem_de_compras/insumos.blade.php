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
                            <generic-grid
                                    :data="gridData"
                                    :columns="gridColumns"
                                    :filter-key="searchQuery">
                            </generic-grid>
                        </div>
                        {{--<v-server-table url="{{ url('compras/insumos/lista') }}" :columns="columns" :options="options"></v-server-table>--}}
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        const app = new Vue({
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
    </script>

@stop