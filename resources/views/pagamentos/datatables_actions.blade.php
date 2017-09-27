{!! Form::open(['route' => ['pagamentos.destroy', $id], 'id'=>'formDelete'.$id, 'method' => 'delete']) !!}
<div class='btn-group'>
    <a href="{{ route('pagamentos.show', $id) }}" title="{{ ucfirst( trans('common.show') )}}" class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-eye-open"></i>
    </a>
    @if(!$enviado_integracao)
        @shield('pagamentos.edit')
        <a href="{{ route('pagamentos.integrar', $id) }}" title="Integrar com Mega" class='btn btn-success btn-xs'>
            <i class="glyphicon glyphicon-upload"></i>
        </a>
        @endshield
        <a href="{{ route('pagamentos.edit', $id) }}" title="{{ ucfirst( trans('common.edit') )}}" class='btn btn-warning btn-xs'>
            <i class="glyphicon glyphicon-edit"></i>
        </a>
        @shield('pagamentos.delete')
        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', [
                'type' => 'button',
                'class' => 'btn btn-danger btn-xs',
                'onclick' => "confirmDelete('formDelete".$id."');",
                'title' => ucfirst(trans('common.delete'))
        ]) !!}
        @endshield
    @endif
</div>
{!! Form::close() !!}
