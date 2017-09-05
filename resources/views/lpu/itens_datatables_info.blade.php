@inject('carbon', 'Carbon\Carbon')

<div class='btn-group'>
    @shield('contratos.reapropriar')
        @if($reapropriacoes->isNotEmpty())
            <a href="javascript:void(0)"
                title="Reapropriações"
                data-toggle="popover"
                data-container="body"
                data-external-content="#reapropriacao-{{ $item->id }}"
                class='btn btn-info btn-xs btn-flat'>
                <i class="fa fa-asterisk fa-fw"></i>
            </a>
        @endif
    @endshield
    <a href="javascript:void(0)"
        data-toggle="modal"
        data-target="#history-{{ $item->id }}"
        class='btn btn-default btn-xs btn-flat'>
        <span data-toggle="tooltip"
            title="Histórico de Insumo"
            data-container="body">
            <i class="fa fa-history fa-fw"></i>
        </span>
    </a>
</div>

<div class="modal fade" id="history-{[ $item->id ]}" tabindex="-1">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Histórico de Alteração</h4>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-succes">Salvar</button>
      </div>
    </div>
  </div>
</div>

