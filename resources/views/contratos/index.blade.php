@extends((!isset($isModal) || !$isModal)? 'layouts.front' : 'layouts.modal')

@section('scripts')
  @parent
  <script src="{{ asset('js/general-filters.js') }}"></script>
@stop

@section('content')
  <section class="content-header">
    <h1>
      Contratos
      <a href="{{ url('/contratos/atualizar-valor') }}" class="btn btn-lg btn-flat btn-info pull-right"> <i class="fa fa-refresh"></i>  Atualizar valores</a>
    </h1>
  </section>
  <div class="content" style="clear: both">
    @if(!isset($isModal) || !$isModal)
    <div class="box box-muted">
      <div class="box-body">
        @include('partials.grupos-de-orcamento')
        <div class="row">
          <div class="col-sm-3">
            <h4>Data</h4>
            @include('partials.filter-date')
          </div>
          <div class="col-sm-3">
            <h4>Fornecedor</h4>
            {!!
              Form::select(
                'fornecedor_id',
                $fornecedores,
                null,
                ['class' => 'form-control select2 js-filter']
              )
            !!}
          </div>
          <div class="col-sm-3">
            <h4>Obra</h4>
            {!!
              Form::select(
                'obra_id',
                $obras,
                null,
                ['class' => 'form-control select2 js-filter']
              )
            !!}
          </div>
          <div class="col-sm-3">
            <h4>Status</h4>
            {!!
              Form::select(
                'contrato_status_id',
                $status,
                null,
                ['class' => 'form-control select2 js-filter']
              )
            !!}
          </div>
        </div>
      </div>
    </div>
    @endif
    <div class="box box-muted">
      <div class="box-body">
        {!! $dataTable->table(['width' => '100%','class'=>'table table-striped table-hover'],true) !!}
      </div>
    </div>
  </div>
@endsection

@section('scripts')
    <script src="/vendor/datatables/buttons.server-side.js"></script>
    {!! $dataTable->scripts() !!}
@endsection

