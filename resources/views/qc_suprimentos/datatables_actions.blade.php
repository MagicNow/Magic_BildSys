<div class='btn-group'>
    @shield('qc.edit')
    <a href="{{ route('qc.edit', $id) }}" title="{{ ucfirst( trans('common.edit') )}}" class='btn btn-warning btn-xs'>
        <i class="glyphicon glyphicon-edit"></i>
    </a>
    @endshield
</div>