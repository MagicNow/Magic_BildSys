@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Medicão de Serviço {{ $medicaoServico->id . ' - Obra '. $medicaoServico->contratoItemApropriacao->contratoItem->contrato->obra->nome }}

            @if(!$medicaoServico->finalizado)
            <a class="btn btn-success btn-flat btn-lg pull-right" href="{{ url('/medicoes/create?contrato_item_apropriacao_id='.$medicaoServico->contrato_item_apropriacao_id.'&medicao_servico_id='.$medicaoServico->id) }}">
                Continuar medição <i class="fa fa-arrow-right" aria-hidden="true"></i>
            </a>
            @endif
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('medicao_servicos.show_fields')

                    @include('medicoes.table')

                </div>
                <div class="row">
                    <a href="{!! route('medicoes.index') !!}" class="btn btn-default">
                        <i class="fa fa-arrow-left"></i>  {{ ucfirst( trans('common.back') )}}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
