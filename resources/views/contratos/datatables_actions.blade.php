<div class='btn-group'>
    @if($isModal)
        <button class="btn btn-success btn-flat btn-xs js-indicar"
            data-contrato="{{ $id }}">
            Indicar
        </button>
    @else
        <a href="{{ route('contratos.show', $id) }}"
            title="{{ ucfirst(trans('common.show')) }}"
            class='btn btn-default btn-xs btn-flat'>
            <i class="fa fa-eye"></i>
        </a>
    @endif
</div>
