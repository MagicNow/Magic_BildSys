{!! Form::open(['route' => ['quadroDeConcorrencias.destroy', $id], 'id'=>'formDelete'.$id, 'method' => 'delete']) !!}

<div class='btn-group'>
    @shield('quadroDeConcorrencias.view')
      <a href="{{ route('quadroDeConcorrencias.show', $id) }}" title="{{ ucfirst( trans('common.show') )}}" class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-eye-open"></i>
      </a>
    @endshield

    @shield('quadroDeConcorrencias.edit')
        @if($qc_status_id!=6 && $qc_status_id!=7 && $qc_status_id!=8)
        <a href="{{ route('quadroDeConcorrencias.edit', $id) }}" title="{{ ucfirst( trans('common.edit') )}}" class='btn btn-warning btn-xs'>
            <i class="glyphicon glyphicon-edit"></i>
        </a>
        @endif
        @if($qc_status_id==5)
            <button type="button" class="btn btn-xs btn-success" onclick="abrirConcorrencia({{$id}});" title="Abrir concorrência">
                <i class="fa fa-play-circle-o" aria-hidden="true"></i>
            </button>
        @endif
        @if($qc_status_id!=6 && $qc_status_id!=7 && $qc_status_id!=8)
        <button type="button" class="btn btn-xs btn-default" onclick="cancelarQC({{$id}});" title="Cancelar Quadro de Concorrência">
            <i class="glyphicon glyphicon-remove"></i>
        </button>
        @endif
        @if($tem_ofertas && $qc_status_id === 7)
          <a href="{{ route('quadroDeConcorrencia.avaliar', $id) }}" class="btn btn-xs btn-primary" title="Avaliar Quadro de Concorrência">
            <i class="glyphicon glyphicon-ok"></i>
          </a>
        @endif
        @if($qc_status_id === 8)
            <a href="{{ url('/quadro-de-concorrencia/'. $id.'/gerar-contrato') }}" class="btn btn-xs btn-success" title="Gerar/Imprimir contrato(s)">
                <i class="fa fa-file-text-o"></i>
            </a>
        @endif
    @endshield

    @shield('quadroDeConcorrencias.informar_valor')
        @if($qc_status_id == 7 && $fornecedores != $propostas)
        <a href="{{ route('quadroDeConcorrencia.informar-valor', $id) }}" class="btn btn-xs btn-info" title="Informar valores">
            <i class="glyphicon glyphicon-usd"></i>
        </a>
        @endif
    @endshield

    @shield('quadroDeConcorrencias.delete')
        @if($qc_status_id!=7 && $qc_status_id!=8)
            {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', [
                'type' => 'button',
                'class' => 'btn btn-danger btn-xs',
                'onclick' => "confirmDelete('formDelete".$id."');",
                'title' => ucfirst(trans('common.delete'))
            ]) !!}
        @endif
    @endshield
</div>

{!! Form::close() !!}
