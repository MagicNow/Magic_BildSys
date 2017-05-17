@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Cronograma por obras</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>



        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.planejamento_cronogramas.table')
            </div>
        </div>
    </div>
@endsection

