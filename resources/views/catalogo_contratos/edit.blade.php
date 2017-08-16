@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Catálogo de Acordo
            <span class="pull-right">
                Situação:
                <span class="label label-default"
                      style="background-color: {{ $catalogoContrato->status->cor }}"> {{ $catalogoContrato->status->nome }} </span>
                @if($catalogoContrato->catalogo_contrato_status_id == 3 && $catalogoContrato->obras()->whereIn('catalogo_contrato_status_id',[1,2])->count()  )
                    <span class="label label-warning" data-toggle="tooltip" data-placement="bottom"
                          title="Alguma obra amarrada foi adicionada após a assinatura"
                          style="margin-left: 10px">
                        <i class="fa fa-exclamation"></i>
                    </span>
                @endif
                @if($catalogoContrato->catalogo_contrato_status_id == 2 ||  $catalogoContrato->catalogo_contrato_status_id == 3 && $catalogoContrato->obras()->whereIn('catalogo_contrato_status_id',[1,2])->count()  )
                    <a href="{{ url('/catalogo-acordos/'.$catalogoContrato->id.'/imprimir-minuta') }}"
                       target="_blank"
                       style="margin-left: 10px" class="btn btn-success btn-sm btn-flat">
                        <i class="fa fa-print"></i> Imprimir Minuta
                    </a>
                @endif

            </span>
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::model($catalogoContrato, ['route' => ['catalogo_contratos.update', $catalogoContrato->id], 'method' => 'patch', 'files' => true]) !!}

                    @include('catalogo_contratos.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection