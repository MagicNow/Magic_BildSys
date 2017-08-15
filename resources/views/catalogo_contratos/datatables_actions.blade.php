{!! Form::open(['route' => ['catalogo_contratos.destroy', $id], 'id'=>'formDelete'.$id, 'method' => 'delete']) !!}

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
    <a href="{{ route('catalogo_contratos.show', $id) }}" title="{{ ucfirst( trans('common.show') )}}" class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-eye-open"></i>
    </a>
    @shield('catalogo_acordos.edit')
    <a href="{{ route('catalogo_contratos.edit', $id) }}" title="{{ ucfirst( trans('common.edit') )}}" class='btn btn-warning btn-xs'>
        <i class="glyphicon glyphicon-edit"></i>
    </a>
    @endshield
    @shield('catalogo_acordos.delete')
    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', [
            'type' => 'button',
            'class' => 'btn btn-danger btn-xs',
            'onclick' => "confirmDelete('formDelete".$id."');",
            'title' => ucfirst(trans('common.delete'))
        ]) !!}
    @endshield
</div>
{!! Form::close() !!}
