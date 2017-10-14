@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Cronograma x Carteiras de Q.C. Avulso</h1>
        <h1 class="pull-right">
            <a class="btn btn-primary pull-right" style="margin-top: -10px; margin-bottom: 5px;" href="{!! route('admin.planejamento.create-tarefa_carteiras') !!}">
                Amarrar Tarefa do Cronograma à uma Carteira
            </a>
        </h1>

    </section>
    <div class="content">
        <div class="clearfix"></div>



        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.planejamentos.table')
            </div>
        </div>
    </div>
@endsection

