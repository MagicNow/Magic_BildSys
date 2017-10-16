{!! Form::open(['route' => ['admin.mascara_padrao.destroy', $id], 'id'=>'formDelete'.$id, 'method' => 'delete']) !!}
<div class='btn-group'>
    <a href="{{ route('admin.mascaraPadraoEstruturas.mascara-padrao-insumos', $id) }}" title="Relacionar Insumos" class='btn btn-success btn-xs'>
        <i class="fa fa-plus"></i>
    </a>

    <a href="{{ route('admin.mascaraPadraoEstruturas.create', $id) }}" title="Visualizar estrutura da máscara padrão" class='btn btn-info btn-xs'>
        <i class="glyphicon glyphicon-eye-open"></i>
    </a>

    <a href="{{ route('admin.mascara_padrao.edit', $id) }}" title="{{ ucfirst( trans('common.edit') )}}" class='btn btn-warning btn-xs'>
        <i class="glyphicon glyphicon-edit"></i>
    </a>
    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', [
            'type' => 'button',
            'class' => 'btn btn-danger btn-xs',
            'onclick' => "confirmDelete('formDelete".$id."');",
            'title' => ucfirst(trans('common.delete'))
        ]) !!}

</div>
{!! Form::close() !!}
