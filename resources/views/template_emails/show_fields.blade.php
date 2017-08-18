<!-- Nome Field -->
<div class="form-group col-md-12">
    {!! Form::label('nome', 'Nome:') !!}
    <p class="form-control">{!! $templateEmail->nome !!}</p>
</div>
<!-- Created At Field -->
<div class="form-group col-md-4">
    {!! Form::label('created_at', 'Criado em:') !!}
    <p class="form-control">{!! $templateEmail->created_at->format('d/m/Y H:i') !!}</p>
</div>

<!-- User Id Field -->
<div class="form-group col-md-4">
    {!! Form::label('user_id', 'Por:') !!}
    <p class="form-control">{!! $templateEmail->user_id ? $templateEmail->user()->withTrashed()->first()->name : '' !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-md-4">
    {!! Form::label('updated_at', 'Alterado em:') !!}
    <p class="form-control">{!! $templateEmail->updated_at ? $templateEmail->updated_at->format('d/m/Y H:i'):'' !!}</p>
</div>


<!-- Template Field -->
<div class="form-group col-md-12">
    {!! Form::label('template', 'Template:') !!}
    <div class="well well-sm">{!! $templateEmail->template !!}</div>
</div>






