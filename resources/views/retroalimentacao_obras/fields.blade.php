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

<!-- Nome Field -->
<div class="form-group col-sm-12">
    {!! Form::label('nome', 'Nome:') !!}
    {!! Form::text('nome', null, ['class' => 'form-control input-lg', 'required'=>'required']) !!}
</div>

<!-- Nome Field -->
<div class="form-group col-sm-12">
    {!! Form::label('descricao', 'Descrição:') !!}
    {!! Form::textarea('descricao', null, ['class' => 'form-control input-lg']) !!}
</div>
