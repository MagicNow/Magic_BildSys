<!-- Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('id', 'Id:') !!}
    <p class="form-control">{!! $templateEmail->id !!}</p>
</div>

<!-- Nome Field -->
<div class="form-group col-md-6">
    {!! Form::label('nome', 'Nome:') !!}
    <p class="form-control">{!! $templateEmail->nome !!}</p>
</div>

<!-- Template Field -->
<div class="form-group col-md-6">
    {!! Form::label('template', 'Template:') !!}
    <p class="form-control">{!! $templateEmail->template !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group col-md-6">
    {!! Form::label('created_at', 'Created At:') !!}
    <p class="form-control">{!! $templateEmail->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-md-6">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p class="form-control">{!! $templateEmail->updated_at !!}</p>
</div>

<!-- User Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('user_id', 'User Id:') !!}
    <p class="form-control">{!! $templateEmail->user_id !!}</p>
</div>


