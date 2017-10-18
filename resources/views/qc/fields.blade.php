<!-- Obra ID Field -->
<div class="form-group col-sm-6">
    {!! Form::label('obra_id', 'Obra:') !!}
    {!! Form::select('obra_id', $obras, request('obra_id'), ['class' => 'form-control select2']) !!}
</div>

<!-- Tipologia Field -->
<div class="form-group col-sm-6">
	{!! Form::label('tipologia', 'Tipo de Q.C.:') !!}
	{!! Form::select('tipologia_id', $tipologias, null, ['class' => 'form-control select2', 'required'=>'required']) !!}
</div>

<!-- Carteira ID Field -->
<div class="form-group col-sm-6">
	{!! Form::label('carteira_id', 'Carteira:') !!}
	{!! Form::select('carteira_id', $carteiras, request('carteira_id'), ['class' => 'form-control', 'required'=>'required']) !!}
</div>

<!-- Carteira ID Field -->
<div class="form-group col-sm-6">
	{!! Form::label('carteira_comprada', 'Carteira comprada?') !!}
    <p>
        <label>
            {!! Form::radio('carteira_comprada', 1, true) !!}
            Sim
        </label>
        &nbsp;
        &nbsp;
        <label>
            {!! Form::radio('carteira_comprada', 0) !!}
            Não
        </label>
    </p>
</div>

<div class="col-sm-12">
    <div class="row">
        <!-- Valor Pré Orçamento Field -->
        <div class="form-group col-sm-4">
        	{!! Form::label('valor_pre_orcamento', 'Valor Pré Orçamento:') !!}
        	{!! Form::text('valor_pre_orcamento', NULL, ['class' => 'form-control money', 'required'=>'required']) !!}
        </div>

        <!-- Valor Orçamento Inicial Field -->
        <div class="form-group col-sm-4">
        	{!! Form::label('valor_orcamento_inicial', 'Valor Orçamento Inicial :') !!}
        	{!! Form::text('valor_orcamento_inicial', NULL, ['class' => 'form-control money', 'required'=>'required']) !!}
        </div>

        <!-- Valor da Gerencial Field -->
        <div class="form-group col-sm-4">
        	{!! Form::label('valor_gerencial', 'Valor da Gerencial:') !!}
        	{!! Form::text('valor_gerencial', NULL, ['class' => 'form-control money', 'required'=>'required']) !!}
        </div>
    </div>
</div>

<!-- Descrição Field -->
<div class="form-group col-sm-12">
	{!! Form::label('descricao', 'Descrição do serviço:') !!}
	{!! Form::textarea('descricao', NULL, ['class' => 'form-control', 'required'=>'required']) !!}
</div>

@if(!isset($qc))
    <div class="col-sm-12">
        <div class="box box-muted">
            <div class="box-header with-border">
                <i class="fa fa-paperclip"></i> ANEXOS
            </div>
            <div class="box-body qc-anexos">
                <div class="form-group row qc-anexos-campos">
                    <div class="col-sm-5">
                       {!! Form::label('', 'Descrição:') !!}
                        {!! Form::text('anexo_descricao[]', NULL, ['class' => 'form-control']) !!}
                    </div>
                    <div class="col-sm-3">
                       {!! Form::label('', 'Tipo do Anexo:') !!}
                        {!! Form::select('anexo_tipo[]', ['' => 'Escolha...', 'Proposta' => 'Proposta', 'Quadro de concorrência' => 'Quadro de concorrência', 'Email' => 'Email', 'Detalhamento do Projeto' => 'Detalhamento do Projeto'], NULL, ['class' => 'form-control']) !!}
                    </div>
                    <div class="col-sm-3">
                           {!! Form::label('', 'Arquivo:') !!}
                           {!! Form::file('anexo_arquivo[]', array('id' => 'file', 'class' => 'form-control')) !!}
                    </div>
                    <div class="col-md-1 text-center">
                        <label>&nbsp;</label>
                        <div class="center-block">
                            <button
                                 type="button"
                                 class="btn js-qc-anexos-remover btn-warning hidden">
                                 <i class="fa fa-minus"></i>
                             </button>
                            <button
                                 type="button"
                                 class="btn js-qc-anexos-novo btn-default"
                                 disabled="disabled">
                                 <i class="fa fa-plus"></i>
                             </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Submit Field -->
<div class="form-group col-sm-12">
	{!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
	<a href="{!! route('qc.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>

@section('scripts')
	<script src="{{ asset('js/libs/jquery.formatCurrency-1.4.0.js') }}"></script>
	<script src="{{ asset('js/qc-actions.js') }}"></script>
@stop
