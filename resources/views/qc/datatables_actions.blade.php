<div class='btn-group'>
    <a href="{{ route('qc.show', $qc->id) }}"
        title="Abrir"
        data-toggle="tooltip"
        class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-eye-open"></i>
    </a>
    @if($qc->canCancel())
        <button type="button" class="btn btn-xs btn-danger" onclick="cancelarQC({{$qc->id}});" title="Cancelar Quadro de Concorrência">
            <i class="glyphicon glyphicon-remove"></i>
        </button>
    @endif
</div>
