<div class='btn-group'>
    <a href="{{ route('admin.configuracaoEstaticas.show', $id) }}" title="{{ ucfirst( trans('common.show') )}}" class='btn btn-default btn-ms'>
        <i class="glyphicon glyphicon-eye-open"></i>
    </a>
    <a href="{{ route('admin.configuracaoEstaticas.edit', $id) }}" title="{{ ucfirst( trans('common.edit') )}}" class='btn btn-warning btn-ms'>
        <i class="glyphicon glyphicon-edit"></i>
    </a>
</div>
