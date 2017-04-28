<!-- Obra Id Field -->
{!! Form::hidden('anterior', url()->previous() ) !!}
@if(Request::get('obra_id'))
    {!! Form::hidden('obra_id', Request::get('obra_id')) !!}
@else
    <div class="form-group col-sm-12">
        {!! Form::label('obra_id', 'Obra:') !!}
        {!! Form::select('obra_id',[''=>'Escolha...']+$obras, null, ['class' => 'form-control input-lg', 'required'=>'required']) !!}
    </div>
@endif

<!-- Origem Field -->
<div class="form-group col-sm-6">
    {!! Form::label('origem', 'Origem:') !!}
    {!! Form::text('origem', null, ['class' => 'form-control']) !!}
</div>

<!-- Categoria Field -->
<div class="form-group col-sm-6">
    {!! Form::label('categoria', 'Categoria:') !!}
    {!! Form::select(
        'categoria',
        array(
            'Escolha'=>'Escolha...',
            'Quantidade'=>'Quantidade',
            'Escopo'=>'Escopo',
            'Consumo'=>'Consumo',
            'Máscara'=>'Máscara',
            'Projeto'=>'Projeto',
            'Orçamento'=>'Orçamento',
            'Procedimento'=>'Procedimento'
        ),
        isset($retroalimentacaoObra)? $retroalimentacaoObra->categoria : 'Escolha',
        ['class' => 'form-control input-md']
    ) !!}
</div>

<!-- Origem Field -->
<div class="form-group col-sm-6">
    {!! Form::label('situacao_atual', 'Situação Atual:') !!}
    {!! Form::text('situacao_atual', null, ['class' => 'form-control input-md']) !!}
</div>

<!-- Origem Field -->
<div class="form-group col-sm-6">
    {!! Form::label('situacao_proposta', 'Situação Proposta:') !!}
    {!! Form::text('situacao_proposta', null, ['class' => 'form-control']) !!}
</div>


<!-- Data Inclusao Field -->
<div class="form-group col-sm-6">
    {!! Form::label('data_inclusao', 'Data Inclusao:') !!}
    {!! Form::date('data_inclusao', null, ['class' => 'form-control']) !!}
</div>

@if (strpos(Request::path(), 'admin') !== false)
    <!-- Origem Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('acao', 'Ação :') !!}
        {!! Form::text('acao', null, ['class' => 'form-control input-md']) !!}
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('data_prevista', 'Data prevista:') !!}
        {!! Form::date('data_prevista', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('data_conclusao', 'Data conclusao:') !!}
        {!! Form::date('data_conclusao', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('status', 'Status:') !!}
        {!! Form::select(
            'status',
            array(
                'Escolha'=>'Escolha...',
                'Pendente'=>'Pendente',
                'Escopo'=>'Escopo',
                'Consumo'=>'Consumo',
                'Máscara'=>'Máscara',
                'Projeto'=>'Projeto',
                'Orçamento'=>'Orçamento',
                'Procedimento'=>'Procedimento'
            ),
            (isset($retroalimentacaoObra) && $retroalimentacaoObra->status != null)? $retroalimentacaoObra->status : 'Escolha',
            ['class' => 'form-control input-md']
        ) !!}
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('resultado_obtido', 'Resultado obtido:') !!}
        {!! Form::text('resultado_obtido', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('aceite', 'Aceite:') !!}
        {!! Form::checkbox('aceite', '1', true) !!}
    </div>
    <!-- Submit Field -->
    <div class="form-group col-sm-12">
        {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
        <a href="{!! url('/admin/retroalimentacaoObras') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
    </div>
@endif
