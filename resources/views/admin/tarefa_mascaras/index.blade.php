@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Tarefa padrão/Máscara padrão</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.tarefa_mascaras.create')
            </div>
        </div>
    </div>
@endsection

