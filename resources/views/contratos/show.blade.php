@extends('layouts.front')

@section('content')
  <section class="content-header">
    <h1>
      Detalhes do Contrato #{{ $contrato->id }}
      @if($workflowAprovacao['podeAprovar'])
        @if($workflowAprovacao['iraAprovar'])
          <span class="text-warning"> ||  Aprovação de Escopo </span>
          <div class="btn-group" role="group" id="blocoItemAprovaReprova{{ $contrato->id }}"
            aria-label="...">
            <button type="button" onclick="workflowAprovaReprova({{ $contrato->id }},
              'Contrato',1,'blocoItemAprovaReprova{{ $contrato->id }}',
              'Contrato {{ $contrato->id }}',0, '', '');"
              class="btn btn-success btn-lg btn-flat"
              title="Aprovar">
              Aprovar Contrato
              <i class="fa fa-check" aria-hidden="true"></i>
            </button>
            <button type="button" onclick="workflowAprovaReprova({{ $contrato->id }},
              'Contrato',0, 'blocoItemAprovaReprova{{ $contrato->id }}',
              'Contrato {{ $contrato->id }}',0, '', '');"
              class="btn btn-danger btn-lg btn-flat"
              title="Reprovar Este item">
              Reprovar
              <i class="fa fa-times" aria-hidden="true"></i>
            </button>
          </div>
        @else
          @if($workflowAprovacao['jaAprovou'])
            @if($workflowAprovacao['aprovacao'])
              <span class="btn-lg btn-flat text-success" title="Aprovado por você">
                <i class="fa fa-check" aria-hidden="true"></i>
              </span>
            @else
              <span class="text-danger btn-lg btn-flat" title="Reprovado por você">
                <i class="fa fa-times" aria-hidden="true"></i>
              </span>
            @endif
          @else
            {{--Não Aprovou ainda, pode aprovar, mas por algum motivo não irá aprovar no momento--}}
            <button type="button" title="{{ $workflowAprovacao['msg'] }}"
              onclick="swal('{{ $workflowAprovacao['msg'] }}','','info');"
              class="btn btn-default btn-lg btn-flat">
              <i class="fa fa-info" aria-hidden="true"></i>
            </button>
          @endif
        @endif
      @endif
    <small class="label label-default pull-right margin10">
      <i class="fa fa-circle"
        aria-hidden="true"
        style="color:{{ $contrato->status->cor }}"></i>
      {{ $contrato->status->nome }}
    </small>
  </h1>
</section>

<div class="content">
  <div class="row">
    <div class="col-sm-4">
      <div class="box box-muted">
        <div class="box-header with-border">
          Detalhes do Fornecedor
        </div>
        <div class="box-body">
          <table class="table table-striped table-bordered">
              <tr>
                <th>Nome</th>
                <td>{{ $contrato->fornecedor->nome }}</td>
              </tr>
              <tr>
                <th>CNPJ</th>
                <td>{{ $contrato->fornecedor->cnpj }}</td>
              </tr>
              <tr>
                <th>Telefone</th>
                <td>{{ $contrato->fornecedor->telefone ?: 'Sem telefone' }}</td>
              </tr>
              <tr>
                <th>Email</th>
                <td>{{ $contrato->fornecedor->email ?: 'Sem email' }}</td>
              </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="box box-muted">
    <div class="box-body">
        @include('contratos.table')
    </div>
  </div>
</div>

<div class="hidden">
  {!! Form::select('motivo', $motivos, null, ['id' => 'motivo']) !!}
</div>


<div class="modal centered-modal fade" id="modal-reapropriar" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Reapropriar</h4>
      </div>
      <div class="modal-body">
        {!! Form::hidden('contrato_item_id') !!}
        <div class="form-group">
          <label for="qtd">Quantidade</label>
          <div class="input-group">
            {!! Form::text('qtd', null, ['class' => 'form-control money']) !!}
            <div class="input-group-btn">
              <button class="btn btn-warning btn-flat" id="add-all">
                Tudo
              </button>
            </div>
          </div>
        </div>
        @include('partials.grupos-de-orcamento', ['full' => true])
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">
          Cancelar
        </button>
        <button type="button" class="btn btn-success btn-flat js-save">
          Salvar
        </button>
      </div>
    </div>
  </div>
</div>

<div class="modal centered-modal fade" id="modal-reajuste" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Reajuste</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="qtd">Quantidade Adicionada</label>
          {!! Form::text('qtd', null, ['class' => 'form-control money']) !!}
        </div>
        <div class="form-group">
          <label for="valor">Valor</label>
          <div class="input-group">
            <span class="input-group-addon">R$</span>
            {!! Form::text('valor', null, ['class' => 'form-control money']) !!}
          </div>
        </div>
        <div class="form-group">
          <label for="total">Quantidade Total</label>
          {!! Form::text('total', null, ['class' => 'form-control money', 'readonly']) !!}
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">
          Cancelar
        </button>
        <button type="button" class="btn btn-success btn-flat js-save">
          Salvar
        </button>
      </div>
    </div>
  </div>
</div>

<div class="modal centered-modal fade" id="modal-distrato" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Distrato</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          {!! Form::hidden('contrato_item_id') !!}
          <label for="qtd">Quantidade</label>
          {!! Form::text('qtd', null, ['class' => 'form-control money']) !!}
        </div>
        <button class="btn btn-warning btn-block btn-flat btn-sm" id="zerar-saldo">
          Zerar Saldo
        </button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn-flat js-save">Salvar</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
  <script>
        options_motivos = document.getElementById('motivo').innerHTML;
  </script>
  <script data-token="{{ csrf_token() }}" src="{{ asset('/js/contrato-actions.js') }}"></script>
@append