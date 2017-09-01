<!-- Obra Id Field -->
{!! Form::hidden('origem', url()->previous() ) !!}
@if(Request::get('obra_id'))
    {!! Form::hidden('obra_id', Request::get('obra_id')) !!}
@else
    <div class="form-group col-sm-6">
        {!! Form::label('obra_id', 'Obra:') !!}
        {!! Form::select('obra_id',[''=>'Escolha...']+$obras, null, ['class' => 'form-control', 'required'=>'required']) !!}
    </div>
@endif

<!-- Categoria Field -->
<div class="form-group col-sm-6">
    {!! Form::label('categoria', 'Categoria:') !!}
    {!! Form::select('categoria_id',[''=>'Escolha...']+$categorias, null, ['class' => 'form-control', 'required'=>'required']) !!}
</div>

<!-- Origem Field -->
<div class="form-group col-sm-6">
    {!! Form::label('situacao_atual', 'Situação Atual:') !!}
    {!! Form::textarea('situacao_atual', null, ['class' => 'form-control', 'rows' => '3']) !!}
</div>

<!-- Origem Field -->
<div class="form-group col-sm-6">
    {!! Form::label('situacao_proposta', 'Situação Proposta:') !!}
    {!! Form::textarea('situacao_proposta', null, ['class' => 'form-control', 'rows' => '3']) !!}
</div>

@if (strpos(Request::path(), 'edit') !== false)
    <!-- Origem Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('acao', 'Ação :') !!}
        {!! Form::text('acao', null, ['class' => 'form-control input-md']) !!}
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('aceite', 'Aceite:') !!}
        <p>{!! Form::checkbox('aceite', '1', $retroalimentacaoObra->aceite == 1 ? true : false) !!}</p>
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('data_prevista', 'Data prevista:') !!}
        {!! Form::date('data_prevista', isset($retroalimentacaoObra) ? $retroalimentacaoObra->data_prevista ? $retroalimentacaoObra->data_prevista->format('d/m/Y') : null : null, ['class' => 'form-control', 'readonly' => true]) !!}
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('data_conclusao', 'Data conclusão:') !!}
        {!! Form::date('data_conclusao', isset($retroalimentacaoObra) ? $retroalimentacaoObra->data_conclusao ? $retroalimentacaoObra->data_conclusao->format('d/m/Y') : null : null, ['class' => 'form-control', 'readonly' => true]) !!}
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('status', 'Status:') !!}
        {!! Form::select('status_id',[''=>'Escolha...']+$status, null, ['class' => 'form-control', 'required'=>'required']) !!}
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('resultado_obtido', 'Resultado obtido:') !!}
        {!! Form::text('resultado_obtido', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('Usuário Responsável', 'Usuário Responsável:') !!}
        {!! Form::select('user_id_responsavel',[''=>'Escolha...']+$usuarios, null, ['class' => 'form-control', 'required'=>'required']) !!}
    </div>

    <div class="form-group col-sm-12">
        {!! Form::label('andamento', 'Informar Andamento:') !!}
        {!! Form::textarea('andamento', null, ['class' => 'form-control', 'rows'=>4]) !!}
    </div>



@endif

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success btn-lg btn-flat pull-right', 'type'=>'submit']) !!}
    <a href="{!! url('/retroalimentacaoObras') !!}" class="btn btn-lg btn-flat btn-danger"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>
