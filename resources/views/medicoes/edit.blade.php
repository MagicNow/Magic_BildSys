@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Medicão - {{ $medicao->id }}
        </h1>
    </section>
    <div class="content">
        <div class="box box-default">
            <div class="box-body">
                <h4>Obra: <span class="label bg-orange">{{ $contratoItemApropriacao->contratoItem->contrato->obra->nome }}</span></h4>
                <h4>
                    Contrato: <span class="label bg-navy">{{ $contratoItemApropriacao->contratoItem->contrato->id }}</span>
                    <span class="label bg-navy">{{ $contratoItemApropriacao->contratoItem->contrato->fornecedor->nome }}</span>
                </h4>
                <h4>Insumo: <span class="label label-warning">{{ $contratoItemApropriacao->codigo_insumo }}</span> <span class="label label-warning">{{ $contratoItemApropriacao->insumo->nome }}</span></h4>
                @if($medicaoServico)
                    <h4>Período do Serviço: <span class="label label-default"> {{ with(new\Carbon\Carbon($medicaoServico->periodo_inicio))->format('d/m/Y') }}</span> à <span class="label label-default">{{ with(new\Carbon\Carbon($medicaoServico->periodo_termino))->format('d/m/Y') }}</span></h4>
                @endif
                @if($mcMedicaoPrevisao)
                    <h3>{{ $mcMedicaoPrevisao->memoriaCalculoBloco->estruturaObj->nome }} -
                        {{ $mcMedicaoPrevisao->memoriaCalculoBloco->pavimentoObj->nome }} -
                        {{ $mcMedicaoPrevisao->memoriaCalculoBloco->trechoObj->nome }}
                        <span class="label label-default">
                            {{ float_to_money($mcMedicaoPrevisao->qtd,'') .' '. $mcMedicaoPrevisao->unidade_sigla }}
                        </span>
                        @if($medicoes)
                            @if($medicoes->count())
                                <span class="label label-warning">
                                {{ number_format( ( ($medicoes->first()->qtd/$mcMedicaoPrevisao->qtd) * 100),2,',','.')  }}% já medido
                            </span>
                            @endif
                        @endif
                    </h3>
                @endif
            </div>
        </div>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($medicao, ['route' => ['medicoes.update', $medicao->id], 'method' => 'patch','files'=>true]) !!}

                        @include('medicoes.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection