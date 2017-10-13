<div class='btn-group'>
    <a href="{{ route('qc.show', $id) }}"
        title="Abrir"
        data-toggle="tooltip"
        class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-eye-open"></i>
    </a>
    @if($qc_status_id!=6 && $qc_status_id!=7 && $qc_status_id!=8)
        <button type="button" class="btn btn-xs btn-default" onclick="cancelarQC({{$id}});" title="Cancelar Quadro de Concorrência">
            <i class="glyphicon glyphicon-remove"></i>
        </button>
    @endif
</div>
