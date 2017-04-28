<div class='btn-group'>
    @if(isset($data_upload))
    <a href="/admin/planejamentos/atividade?id={{$id}}" title="visualizar itens" class='btn btn-default btn-ms'>
        <i class="fa fa-eye"></i>
    </a>
    @endif
    <a title="Importar cronograma" class='btn btn-default btn-ms'>
        <i class="fa fa-cloud-upload"></i>
    </a>
</div>
