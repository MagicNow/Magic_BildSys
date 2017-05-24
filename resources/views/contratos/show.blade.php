@extends('layouts.front')

@section('content')
  <section class="content-header">
    <h1>
      Contrato #{{ $contrato->id }}
      @if($workflowAprovacao['podeAprovar'])
        @if($workflowAprovacao['iraAprovar'])
          <span class="text-warning"> ||  Aprovação de Escopo </span>
          <div class="btn-group" role="group" id="blocoItemAprovaReprova{{ $contrato->id }}"
            aria-label="...">
            <button type="button" onclick="workflowAprovaReprova({{ $contrato->id }},
              'Contrato',1,'blocoItemAprovaReprova{{ $contrato->id }}',
              'Contrato {{ $contrato->id }}',0, '', '');"
              class="btn btn-success btn-lg btn-flat"
              title="Aprovar">
              Aprovar Contrato
              <i class="fa fa-check" aria-hidden="true"></i>
            </button>
            <button type="button" onclick="workflowAprovaReprova({{ $contrato->id }},
              'Contrato',0, 'blocoItemAprovaReprova{{ $contrato->id }}',
              'Contrato {{ $contrato->id }}',0, '', '');"
              class="btn btn-danger btn-lg btn-flat"
              title="Reprovar Este item">
              Reprovar
              <i class="fa fa-times" aria-hidden="true"></i>
            </button>
          </div>
        @else
          @if($workflowAprovacao['jaAprovou'])
            @if($workflowAprovacao['aprovacao'])
              <span class="btn-lg btn-flat text-success" title="Aprovado por você">
                <i class="fa fa-check" aria-hidden="true"></i>
              </span>
            @else
              <span class="text-danger btn-lg btn-flat" title="Reprovado por você">
                <i class="fa fa-times" aria-hidden="true"></i>
              </span>
            @endif
          @else
            {{--Não Aprovou ainda, pode aprovar, mas por algum motivo não irá aprovar no momento--}}
            <button type="button" title="{{ $workflowAprovacao['msg'] }}"
              onclick="swal('{{ $workflowAprovacao['msg'] }}','','info');"
              class="btn btn-default btn-lg btn-flat">
              <i class="fa fa-info" aria-hidden="true"></i>
            </button>
          @endif
        @endif
      @endif
    <small class="label label-default pull-right margin10">
      <i class="fa fa-circle" aria-hidden="true" style="color:{{ $contrato->status->cor }}"></i>
      {{ $contrato->status->nome }}
    </small>
  </h1>
</section>

<div class="hidden">
  {!! Form::select('motivo', $motivos, null, ['id' => 'motivo']) !!}
</div>
@endsection

@section('scripts')
  <script>
        options_motivos = document.getElementById('motivo').innerHTML;
  </script>
  @parent
@stop



