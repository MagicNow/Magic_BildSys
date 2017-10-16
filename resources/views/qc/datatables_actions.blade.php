<a href="{{ route('qc.show', $qc->id) }}"
    title="Abrir Q.C. # {{ $qc->id }}"
    data-toggle="tooltip"
    data-container="body"
    class='btn btn-default'>
    <i class="glyphicon glyphicon-eye-open"></i>
</a>
@if($qc->canCancel())
    <button
        type="button"
        class="btn btn-danger"
        onclick="cancelarQC({{$qc->id}});"
        data-toggle="tooltip"
        data-container="body"
        title="Cancelar Q.C.">
        <i class="glyphicon glyphicon-remove"></i>
    </button>
@endif
