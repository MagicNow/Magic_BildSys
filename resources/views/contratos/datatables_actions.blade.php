<div class='btn-group'>
    @if($isModal)
        <button class="btn btn-success btn-flat btn-xs js-indicar"
            data-contrato="{{ $id }}">
            Indicar
        </button>
    @else
        @if($tem_pendencias)
            <a href="{{ route('contratos.show', $id) }}"
                data-toggle="tooltip"
                title="Contém modificações pendentes"
                class="btn btn-warning btn-xs btn-flat">
                <i class="fa fa-exclamation-triangle"></i>
            </a>
        @else
            <a href="{{ route('contratos.show', $id) }}"
                title="{{ ucfirst(trans('common.show')) }}"
                class="btn btn-default btn-xs btn-flat">
                <i class="fa fa-eye"></i>
            </a>
        @endif
        @if($status == 'Reprovado')
            <a href="/contratos/{{$id}}/editar" title="{{ ucfirst( trans('common.edit') )}}" class='btn btn-warning btn-xs'>
                <i class="glyphicon glyphicon-edit"></i>
            </a>
        @endif
    @endif
</div>
