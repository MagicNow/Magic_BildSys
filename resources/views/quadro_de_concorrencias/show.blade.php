@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Quadro De Concorrencia {{ $quadroDeConcorrencia->id }}
            <small class="label label-default pull-right">
                <i class="fa fa-clock-o"
                   aria-hidden="true"></i> {{ $quadroDeConcorrencia->created_at->format('d/m/Y H:i') }}
                <i class="fa fa-user" aria-hidden="true"></i> {{ $quadroDeConcorrencia->user->name }}
            </small>
            <small class="label label-info pull-right">
                <i class="fa fa-circle" aria-hidden="true" style="color:{{ $quadroDeConcorrencia->status->cor }}"></i>
                {{ $quadroDeConcorrencia->status->nome }}
            </small>
        </h1>
    </section>
    <div class="content">
        <div class="box box-warning">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('quadro_de_concorrencias.show_fields')
                    <a href="{!! route('quadroDeConcorrencias.index') !!}" class="btn btn-default">
                       <i class="fa fa-arrow-left"></i>  {{ ucfirst( trans('common.back') )}}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
