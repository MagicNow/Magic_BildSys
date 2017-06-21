@if(!$item->pendente && $item->aprovado)
    @shield('contratos.reapropriar')
        <button class="btn btn-default btn-xs btn-flat js-reapropriar"
            data-item-qtd="{{ $item->qtd }}"
            data-item-id="{{ $item->id }}">
            Reapropriar
        </button>
    @endshield
    @shield('contratos.distratar')
        <button class="btn btn-warning btn-xs btn-flat js-distrato"
            data-item-id="{{ $item->id }}"
            data-item-qtd="{{ $item->qtd }}">
            Distrato
        </button>
    @endshield
    @shield('contratos.reajustar')
        <button class="btn btn-primary btn-xs btn-flat js-reajuste"
            data-item-id="{{ $item->id }}"
            data-item-valor="{{ $item->valor_unitario }}"
            data-item-qtd="{{ $item->qtd }}">
            Reajuste
        </button>
    @endshield
@elseif($item->pendente && $item->aprovado)
    <button class="btn btn-default btn-xs btn-flat"
        data-toggle="tooltip"
        title="Item com modificação pendente">
        <i class="fa fa-fw fa-hourglass-half"></i>
    </button>
@elseif($item->pendente && !$item->aprovado)
    <button class="btn btn-default btn-xs btn-flat"
        data-toggle="tooltip"
        title="Item em aprovação para ser adicionado ao Contrato">
        <i class="fa fa-fw fa-hourglass-half"></i>
    </button>
@else
    <button class="btn btn-danger btn-xs btn-flat js-editar"
        data-toggle="tooltip"
        data-html="true"
        data-item-id="{{ $item->id }}"
        data-item-valor="{{ $item->valor_unitario }}"
        data-item-qtd="{{ $item->qtd }}"
        title="
            <div class='text-center'>
                Este item é um aditivo <strong class='text-danger'>REPROVADO</strong>, portanto
                não entrou no contrato ainda. Clique para editar e enviar para aprovação
                novamente.
            </div>
        ">
        <i class="fa fa-fw fa-hourglass-half"></i>
    </button>
@endif
