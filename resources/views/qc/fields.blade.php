<!-- Obra ID Field -->
@if(request()->has('obra_id'))
	{!! Form::hidden('obra_id', request('obra_id')) !!}
@else
	<div class="form-group col-sm-6">
		{!! Form::label('obra_id', 'Obra:') !!}
        {!! Form::select('obra_id', $obras, NULL, ['class' => 'form-control select2', 'required']) !!}
	</div>
@endif

<!-- Tipologia Field -->
<div class="form-group col-sm-6">
	{!! Form::label('tipologia', 'Tipologia:') !!}
	{!! Form::select('tipologia_id', $tipologias, NULL, ['class' => 'form-control select2', 'required'=>'required']) !!}
</div>

<!-- Carteira ID Field -->
<div class="form-group col-sm-6">
	{!! Form::label('carteira_id', 'Carteira:') !!}
	{!! Form::select('carteira_id', $carteiras, NULL, ['class' => 'form-control', 'required'=>'required']) !!}
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

<!-- Descrição Field -->
<div class="form-group col-sm-12">
	{!! Form::label('descricao', 'Descrição do serviço:') !!}
	{!! Form::textarea('descricao', NULL, ['class' => 'form-control', 'required'=>'required']) !!}
</div>

<div class="col-sm-12">
	<div class="box box-muted">
        <div class="box-header with-border">
            <i class="fa fa-paperclip"></i> ANEXOS
        </div>
	    <div class="box-body qc-anexos">
	        <div class="form-group row qc-anexos-campos">
	        	<div class="col-sm-3">
	                   {!! Form::label('', 'Arquivo:') !!}
	                   {!! Form::file('anexo_arquivo[]', array('id' => 'file', 'class' => 'form-control', 'multiple' => true)) !!}
	        	</div>
	        	<div class="col-sm-3">
	                   {!! Form::label('', 'Tipo:') !!}
	        		{!! Form::select('anexo_tipo[]', ['' => 'Escolha...', 'Proposta' => 'Proposta', 'Quadro de concorrência' => 'Quadro de concorrência', 'Email' => 'Email', 'Detalhamento do Projeto' => 'Detalhamento do Projeto'], NULL, ['class' => 'form-control']) !!}
	        	</div>
	        	<div class="col-sm-5">
                   {!! Form::label('', 'Descrição:') !!}
	        		{!! Form::text('anexo_descricao[]', NULL, ['class' => 'form-control']) !!}
	        	</div>
	        	<div class="col-md-1 text-center">
                    <label>&nbsp;</label>
	        		<button
                        type="button"
                        class="btn qc-anexos-novo btn-default center-block"
                        disabled="disabled">
                        <i class="fa fa-plus"></i>
                    </button>
	        	</div>
	        </div>
	    </div>
	</div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
	{!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
	<a href="{!! route('qc.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>

@section('scripts')
	<script src="{{ asset('js/libs/jquery.formatCurrency-1.4.0.js') }}"></script>
	<script src="{{ asset('js/qc-actions.js') }}"></script>
@stop
