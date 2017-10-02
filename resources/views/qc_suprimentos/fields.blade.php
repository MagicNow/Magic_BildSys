<!-- ID Field -->
<div class="form-group col-sm-6">
	{!! Form::label('id', 'ID:') !!}
	{!! Form::text('id', NULL, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
</div>

<!-- Obra ID Field -->
<div class="form-group col-sm-6">
	{!! Form::label('obra_id', 'Obra:') !!}
	{!! Form::select('obra_id',[''=>'Escolha...']+$obras, NULL, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
</div>

<!-- Tipologia Field -->
<div class="form-group col-sm-6">
	{!! Form::label('tipologia', 'Tipologia:') !!}
	{!! Form::select('tipologia_id',[''=>'Escolha...']+$tipologias, NULL, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
</div>

<!-- Carteira ID Field -->
<div class="form-group col-sm-6">
	{!! Form::label('carteira_id', 'Carteira:') !!}
	{!! Form::select('carteira_id', [''=>'Escolha...']+$carteiras, NULL, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
</div>

<!-- Descrição Field -->
<div class="form-group col-sm-12">
	{!! Form::label('descricao', 'Descrição do serviço:') !!}
	{!! Form::textarea('descricao', NULL, ['class' => 'form-control', 'disabled' => 'disabled', 'rows' => 5]) !!}
</div>

<!-- Valor Pré Orçamento Field -->
<div class="form-group col-sm-6">
	{!! Form::label('valor_pre_orcamento', 'Valor Pré Orçamento:') !!}
	{!! Form::text('valor_pre_orcamento', money_format('%i', $qc->valor_pre_orcamento), ['class' => 'form-control', 'disabled' => 'disabled']) !!}
</div>

<!-- Valor Orçamento Inicial Field -->
<div class="form-group col-sm-6">
	{!! Form::label('valor_orcamento_inicial', 'Valor Orçamento Inicial :') !!}
	{!! Form::text('valor_orcamento_inicial', money_format('%i', $qc->valor_orcamento_inicial), ['class' => 'form-control', 'disabled' => 'disabled']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
	{!! Form::label('status', 'Status:') !!}
	{!! Form::select('status',[''=>'Escolha...', 'Em andamento' => 'Em andamento', 'Fechado' => 'Fechado'], NULL, ['class' => 'form-control']) !!}
</div>

<!-- Valor Fechamento Field -->
<div class="form-group col-sm-6">
	{!! Form::label('valor_fechamento', 'Valor Fechamento:') !!}
	{!! Form::text('valor_fechamento', NULL, ['class' => 'form-control currency']) !!}
</div>

<!-- Fornecedor ID Field -->
<div class="form-group col-sm-6">
	{!! Form::label('fornecedor_id', 'Fornecedor:') !!}
	{!! Form::select('fornecedor_id', [''=>'Escolha...']+$fornecedores, NULL, ['class' => 'form-control select2']) !!}
</div>

<!-- Número do contrato Field -->
<div class="form-group col-sm-6">
	{!! Form::label('numero_contrato', 'Número do contrato (MEGA):') !!}
	{!! Form::text('numero_contrato', NULL, ['class' => 'form-control']) !!}
</div>

<!-- Comprador Field -->
<div class="form-group col-sm-6">
	{!! Form::label('comprador_id', 'Comprador:') !!}
	{!! Form::select('comprador_id',[''=>'Escolha...']+$comprador, NULL, ['class' => 'form-control select2']) !!}
</div>

<fieldset class="col-sm-12 qc-anexos">
	<legend>Anexos</legend>
	<div class="row">
		<div class="col-sm-3">
			<h5 style="color:#000000">Arquivo:</h5>
		</div>
		<div class="col-sm-3">
			<h5 style="color:#000000">Tipo:</h5>
		</div>
		<div class="col-sm-5">
			<h5 style="color:#000000">Descrição:</h5>
		</div>
		<div class="col-md-1">
			<h5>&nbsp;</h5>
		</div>
	</div>
	<div class="form-group row qc-anexos-campos">
		<div class="col-sm-4">
			<div class="{{ $errors->has('anexo_arquivo') ? 'has-error' : '' }}">
				{!! Form::file('anexo_arquivo[]', array('id' => 'file', 'class' => 'form-control', 'multiple' => true)) !!}
			</div>
		</div>
		<div class="col-sm-3">
			{!! Form::select('anexo_tipo[]', ['' => 'Escolha...', 'QC Fechado' => 'QC Fechado'], NULL, ['class' => 'form-control']) !!}
		</div>
		<div class="col-sm-5">
			{!! Form::text('anexo_descricao[]', NULL, ['class' => 'form-control']) !!}
		</div>
	</div>
</fieldset>

<!-- Submit Field -->
<div class="form-group col-sm-12">
	{!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
	<a href="{!! route('qc.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>

@section('scripts')
	<script src="{{ asset('js/libs/jquery.formatCurrency-1.4.0.js') }}"></script>
	<script src="{{ asset('js/qc-actions.js') }}"></script>
@stop