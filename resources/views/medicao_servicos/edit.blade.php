@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Editar medicão de serviço {!! $medicaoServico->id . ' - Obra '.
            $medicaoServico->contratoItemApropriacao->contratoItem->contrato->obra->nome . ' ' .
            !is_null($medicaoServico->aprovado)?($medicaoServico->aprovado?'<span class="label label-success">Aprovado</span>':'<span class="label label-danger">Reprovado</span>'):''  !!}

        @if(!$medicaoServico->finalizado)
                @if(!$medicaoServico->medicoes()->where('aprovado','0')->count())
                    <a class="btn btn-success btn-flat btn-lg pull-right" href="{{ url('/medicoes/create?contrato_item_apropriacao_id='.$medicaoServico->contrato_item_apropriacao_id.'&medicao_servico_id='.$medicaoServico->id) }}">
                        Continuar medição <i class="fa fa-arrow-right" aria-hidden="true"></i>
                    </a>
                @else
                    <button data-toggle="tooltip" data-placement="left"
                            type="button" class="btn btn-success btn-flat btn-lg pull-right disabled"

                       title="Impossível continuar, pois existem itens reprovados!">
                        Continuar medição <i class="fa fa-arrow-right" aria-hidden="true"></i>
                    </button>
                @endif

            @endif
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($medicaoServico, ['route' => ['medicaoServicos.update', $medicaoServico->id], 'method' => 'patch']) !!}

                        @include('medicao_servicos.fields')

                   {!! Form::close() !!}

                   <div class="col-md-12">
                       @include('medicoes.table')
                   </div>
               </div>
           </div>
       </div>
   </div>
@endsection