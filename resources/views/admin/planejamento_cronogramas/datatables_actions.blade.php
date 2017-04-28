<div class='btn-group'>
    @if(isset($data_upload))
    <a href="/admin/planejamentos/atividade?id={{$id}}" title="visualizar cronograma" class='btn btn-default btn-ms'>
        <i class="fa fa-eye"></i>
    </a>
    @endif
    <a href="/admin/planejamento?id={{$id}}" title="Importar cronograma" class='btn btn-default btn-ms'>
        <i class="fa fa-cloud-upload"></i>
    </a>
</div>
