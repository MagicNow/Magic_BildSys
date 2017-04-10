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
                    <div id="insumos">
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
                    </div>-


                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
    <script type="text/javascript">

    </script>

@stop