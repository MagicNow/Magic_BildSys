@extends((!isset($isModal) || !$isModal)? 'layouts.front' : 'layouts.modal')

@section('scripts')
  @parent
  <script src="{{ asset('js/general-filters.js') }}"></script>
@stop

@section('content')
  <section class="content-header">
    <h1>
      Lista de Preço Unitário      
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
				
				<!-- Grupo de Insumo Field -->			
				<div class="col-sm-6">
					{!! Form::label('insumo_grupo_id', 'Grupo de Insumos:') !!}
					{!! Form::select('insumo_grupo_id', $insumoGrupo, null, ['class' => 'form-control']) !!}
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
    <script src="/vendor/datatables/buttons.server-side.js"></script>    
    {!! $dataTable->scripts() !!}
@endsection

