@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">
            Planejamento Orçamento
        </h1>

        <h1 class="pull-right">
            <a class="btn btn-warning pull-right hide" id="js-btn-semPlanejamento" style="margin-top: -10px;margin-bottom: 5px; margin-right: 10px;" href="">
                Ver insumos sem planejamento de compra associado
            </a>
        </h1>
    </section>

    <div class="content">
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'admin.planejamentoOrcamentos.store']) !!}

                        @include('admin.planejamento_orcamentos.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
