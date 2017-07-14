@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Motivo para declinar proposta
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('admin.desistencia_motivos.show_fields')
                    <a href="{!! route('admin.desistenciaMotivos.index') !!}" class="btn btn-default">
                       <i class="fa fa-arrow-left"></i>  {{ ucfirst( trans('common.back') )}}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
