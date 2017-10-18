@if($qc_id)
<a href="{{ route('qc.show', $qc_id) }}"
    title="Abrir Q.C. # {{ $qc_id }}"
    data-toggle="tooltip"
    data-container="body"
    class='btn btn-default'>
    <i class="glyphicon glyphicon-eye-open"></i>
</a>
@endif
