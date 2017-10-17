<!-- Obra ID Field -->
@if(Request::get('obra_id'))
	{!! Form::hidden('obra_id', Request::get('obra_id')) !!}
@else
	<div class="form-group col-sm-6">
		{!! Form::label('obra_id', 'Obra:') !!}
		{!! Form::select('obra_id',[''=>'Escolha...'] + $obras, NULL, ['class' => 'form-control', 'required'=>'required']) !!}
	</div>
@endif

<!-- Tipologia Field -->
<div class="form-group col-sm-6">
	{!! Form::label('tipologia', 'Tipologia:') !!}
	{!! Form::select('tipologia_id',[''=>'Escolha...']+$tipologias, NULL, ['class' => 'form-control', 'required'=>'required']) !!}
</div>

<!-- Carteira ID Field -->
<div class="form-group col-sm-6">
	{!! Form::label('carteira_id', 'Carteira:') !!}
	{!! Form::select('carteira_id', [''=>'Escolha...']+$carteiras, @isset($qc) ? $qc->carteira_id : NULL, ['class' => 'form-control select2', 'required'=>'required']) !!}
</div>

<!-- Descrição Field -->
<div class="form-group col-sm-12">
	{!! Form::label('descricao', 'Descrição do serviço:') !!}
	{!! Form::textarea('descricao', NULL, ['class' => 'form-control', 'required'=>'required']) !!}
</div>

<!-- Valor Pré Orçamento Field -->
<div class="form-group col-sm-6">
	{!! Form::label('valor_pre_orcamento', 'Valor Pré Orçamento:') !!}
	{!! Form::text('valor_pre_orcamento', NULL, ['class' => 'form-control money', 'required'=>'required']) !!}
</div>

<!-- Valor Orçamento Inicial Field -->
<div class="form-group col-sm-6">
	{!! Form::label('valor_orcamento_inicial', 'Valor Orçamento Inicial :') !!}
	{!! Form::text('valor_orcamento_inicial', NULL, ['class' => 'form-control money', 'required'=>'required']) !!}
</div>

<!-- Valor da Gerencial Field -->
<div class="form-group col-sm-6">
	{!! Form::label('valor_gerencial', 'Valor da Gerencial:') !!}
	{!! Form::text('valor_gerencial', NULL, ['class' => 'form-control money', 'required'=>'required']) !!}
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
		<div class="col-sm-3">
			<div class="{{ $errors->has('anexo_arquivo') ? 'has-error' : '' }}">
				{!! Form::file('anexo_arquivo[]', array('id' => 'file', 'class' => 'form-control', 'multiple' => true)) !!}
			</div>
		</div>
		<div class="col-sm-3">
			{!! Form::select('anexo_tipo[]', ['' => 'Escolha...', 'Proposta' => 'Proposta', 'Quadro de concorrência' => 'Quadro de concorrência', 'Email' => 'Email', 'Detalhamento do Projeto' => 'Detalhamento do Projeto'], NULL, ['class' => 'form-control']) !!}
		</div>
		<div class="col-sm-5">
			{!! Form::text('anexo_descricao[]', NULL, ['class' => 'form-control']) !!}
		</div>
		<div class="col-md-1 text-center">
			<button type="button" class="fa fa-plus btn qc-anexos-novo" disabled="disabled"></button>
		</div>
	</div>
</fieldset>

<!-- Submit Field -->
<div class="form-group col-sm-12">
	{!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
	<a href="{!! route('qc.index') !!}" class="btn btn-danger"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>

@section('scripts')
	<script src="{{ asset('js/libs/jquery.formatCurrency-1.4.0.js') }}"></script>
	<script src="{{ asset('js/qc-actions.js') }}"></script>
@stop
