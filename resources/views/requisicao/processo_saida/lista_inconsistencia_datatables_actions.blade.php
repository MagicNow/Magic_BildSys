@if($numero_leituras)
    <div class='btn-group'>
        <a onclick="excluirLeitura({{$id}});" title="Excluir leitura" class='btn btn-default'>
            Excluir leitura
        </a>
    </div>
@endif