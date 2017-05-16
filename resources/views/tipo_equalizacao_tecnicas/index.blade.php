@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Equalização Técnicas
           <a class="btn btn-primary pull-right btn-flat btn-lg" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('tipoEqualizacaoTecnicas.create') !!}">
               <i class="fa fa-plus" aria-hidden="true"></i> {{ ucfirst( trans('common.new') )}}
           </a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>



        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('tipo_equalizacao_tecnicas.table')
            </div>
        </div>
    </div>
@endsection

