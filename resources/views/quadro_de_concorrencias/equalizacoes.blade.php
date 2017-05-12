<ul class="list-group">
  @foreach($checks as $check)
    <li class="list-group-item">
      {{ $check->checkable->nome }} -
      @if($check->checkable->obrigatorio)
        <span class="text-info">
          <i class="fa fa-check"></i> Obrigatório
        </span>
      @else
        @if($check->checked)
          <span class="text-success">
            <i class="fa fa-check"></i> Concorda
          </span>
        @else
          <span class="text-danger">
            <i class="fa fa-times"></i> Não Concorda
          </span>
        @endif
      @endif
      <button class="btn btn-xs btn-flat btn-default js-sweetalert pull-right"
        data-text="{{ $check->checkable->descricao }}"
        data-title="{{ $check->checkable->nome }}">
        Descrição
      </button>
      @if($check->obs)
        <button class="btn btn-xs btn-flat btn-default js-sweetalert pull-right"
          data-text="{{ $check->obs }}"
          data-title="Observação">
          Observações
        </button>
      @endif
    </li>
  @endforeach
  {{-- @foreach($anexos as $anexo) --}}
  {{--   <li class="list-group-item"> --}}
  {{--     {{ $anexo->nome }} --}}
  {{--     <a href="{{ $anexo->url }}" --}}
  {{--       download="{{ $anexo->nome }}" type="button" --}}
  {{--       class="btn btn-xs btn-flat btn-default pull-right"> --}}
  {{--       <i class="fa fa-paperclip" title="Baixar"></i> --}}
  {{--     </a> --}}
  {{--   </li> --}}
  {{-- @endforeach --}}
</ul>

