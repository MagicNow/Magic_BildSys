<div class='btn-group'>
    <a href="{{ route('qc.show', $id) }}" title="{{ ucfirst( trans('common.show') )}}" class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-eye-open"></i>
    </a>
    {{-- @shield('qc.edit')
    <a href="{{ route('qc.edit', $id) }}" title="{{ ucfirst( trans('common.edit') )}}" class='btn btn-warning btn-xs'>
        <i class="glyphicon glyphicon-edit"></i>
    </a>
    @endshield --}}
    @shield('qc-aprovar.show')
    <a href="{{ route('qc.aprovar.edit', $id) }}" title="{{ ucfirst( trans('common.approve') . '/' . trans('common.repprove'))}}" class='btn btn-info btn-xs' style="margin: 0 5px;">
        <i class="glyphicon glyphicon-check"></i>
    </a>
    @endshield
</div>
{!! Form::close() !!}
