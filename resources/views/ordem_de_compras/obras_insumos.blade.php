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
                <tabela
                        api-url="/insumos_json"
                        v-bind:params="{planejamento_id: 1}"
                        v-bind:colunas="[{campo_db: 'id', label: 'identificador'},{campo_db: 'nome', label: 'nomezinho'}]">
                </tabela>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
@stop