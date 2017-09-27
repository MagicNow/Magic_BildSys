{!! Form::open(['route' => ['qc.destroy', $id], 'id'=>'formDelete'.$id, 'method' => 'delete']) !!}

@if($status == 'Ativo' || $status == 'Inativo')
    <div class="btn-group" style="margin-top: 10px;">
        <!-- Rounded switch -->
        <label class="switch" title="{{$status == 'Ativo' ? 'Inativar' : 'Ativar'}}" data-toggle="tooltip" data-placement="top">
            <input type="checkbox" {{$status == 'Ativo' ? 'checked' : null}} onclick="ativarDesativarCatalogo({{$id}});">
            <span class="slider round"></span>
        </label>
    </div>
@endif

<div class='btn-group'>
    <a href="{{ route('qc.show', $id) }}" title="{{ ucfirst( trans('common.show') )}}" class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-eye-open"></i>
    </a>
    {{-- @shield('qc.edit')
    <a href="{{ route('qc.edit', $id) }}" title="{{ ucfirst( trans('common.edit') )}}" class='btn btn-warning btn-xs'>
        <i class="glyphicon glyphicon-edit"></i>
    </a>
    @endshield --}}
    @shield('qc.delete')
    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', [
            'type' => 'button',
            'class' => 'btn btn-danger btn-xs',
            'onclick' => "confirmDelete('formDelete".$id."');",
            'title' => ucfirst(trans('common.delete'))
        ]) !!}
    @endshield
    @shield('qc-aprovar.show')
    <a href="{{ route('qc.aprovar.edit', $id) }}" title="{{ ucfirst( trans('common.approve') . '/' . trans('common.repprove'))}}" class='btn btn-info btn-xs' style="margin: 0 5px;">
        <i class="glyphicon glyphicon-check"></i>
    </a>
    @endshield
</div>
{!! Form::close() !!}
