@extends('layouts.front')
@section('content')
<style type="text/css">
.ml15{
    margin-top:0!important;
    text-transform:uppercase;
}
.content-header .pull-right{
    font-family: 'Abel', sans-serif;
    font-weight: bold;
    text-transform: uppercase;
    font-size: 14px;
}
.content-header .label-default{
    display: inline-block;
    padding: 6px 6px 4px 6px!important;
}
.modal-header{
    padding:15px 15px 0!important;
    border:0!important;
    margin-bottom: -10px!important;
    display: inline-block;
    margin-top: -32px;
    text-transform:uppercase;
}
.border-separation{
    margin:4px 0!important;
}
.input-group-addon{
    padding:5px 12px!important;
}
.border-separation{
    border-bottom: 1px solid #d2d6de; 
}
.border-separation:first-child{
    border-bottom:0!important;
}
.textAtualiza{
    display: table;
    padding: 13px 0 0 30px;
}
.overflowH{
    overflow:hidden;
}
.insumo:first-child{
    margin-top:12px!important;
}
.bloco .border-separation{
    border-bottom: 1px solid #d2d6de!important;
    margin-bottom:20px;
}
</style>
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Catálogo de acordo
            <span class="pull-right">
                Situação:
                <span class="label label-default"
                      style="background-color: {{ $catalogoContrato->status->cor }}"> {{ $catalogoContrato->status->nome }} </span>
                @if($catalogoContrato->catalogo_contrato_status_id == 3 && $catalogoContrato->regionais()->whereIn('catalogo_contrato_status_id',[1,2])->count()  )
                    <span class="label label-warning" data-toggle="tooltip" data-placement="bottom"
                          title="Alguma obra amarrada foi adicionada após a assinatura"
                          style="margin-left: 10px">
                        <i class="fa fa-exclamation"></i>
                    </span>
                @endif
                @if($catalogoContrato->catalogo_contrato_status_id == 2 ||  $catalogoContrato->catalogo_contrato_status_id == 3 && $catalogoContrato->regionais()->whereIn('catalogo_contrato_status_id',[1,2])->count()  )
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
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px;padding-right: 20px;">
                    <div style="display:inline-block">
                        @include('catalogo_contratos.show_fields')
                    </div>
                    <a href="{!! route('catalogo_contratos.index') !!}" class="btn btn-warning" style="margin-top: 10px;">
                        <i class="fa fa-arrow-left"></i> {{ ucfirst( trans('common.back') )}}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
