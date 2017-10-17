@extends((!isset($isModal) || !$isModal)? 'layouts.front' : 'layouts.modal')

@section('scripts')
  @parent
  <script src="{{ asset('js/general-filters.js') }}"></script>
@stop

@section('content')
  <section class="content-header">
    <h1>
      <button type="button" class="btn btn-link" onclick="history.go(-1);"><i class="fa fa-arrow-left" aria-hidden="true"></i></button>
      Lista de preço unitário
    </h1>
  </section>
  <div class="content" style="clear: both">
    @if(!isset($isModal) || !$isModal)
	<div class="box box-muted">
      <div class="box-body">        
        <div class="row">
          <div class="col-sm-3">
            <h4>Data</h4>
            @include('partials.filter-date')
          </div>
          <div class="col-sm-3">
            <h4>Grupo de Insumos</h4>
            {!!
              Form::select(
                'insumo_grupo_id',
                $insumoGrupo,
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
  @parent
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>    
    {!! $dataTable->scripts() !!}
@endsection

