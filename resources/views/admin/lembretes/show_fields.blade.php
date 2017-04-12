<!-- Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('id', 'Id:') !!}
    <p class="form-control">{!! $lembrete->id !!}</p>
</div>

<!-- Lembrete Tipo Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('lembrete_tipo_id', 'Lembrete Tipo Id:') !!}
    <p class="form-control">{!! $lembrete->lembrete_tipo_id !!}</p>
</div>

<!-- Planejamento Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('planejamento_id', 'Planejamento Id:') !!}
    <p class="form-control">{!! $lembrete->planejamento_id !!}</p>
</div>

<!-- User Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('user_id', 'User Id:') !!}
    <p class="form-control">{!! $lembrete->user_id !!}</p>
</div>

<!-- Nome Field -->
<div class="form-group col-md-6">
    {!! Form::label('nome', 'Nome:') !!}
    <p class="form-control">{!! $lembrete->nome !!}</p>
</div>

<!-- Dias Prazo Minimo Field -->
<div class="form-group col-md-6">
    {!! Form::label('dias_prazo_minimo', 'Dias Prazo Minimo:') !!}
    <p class="form-control">{!! $lembrete->dias_prazo_minimo !!}</p>
</div>

<!-- Dias Prazo Maximo Field -->
<div class="form-group col-md-6">
    {!! Form::label('dias_prazo_maximo', 'Dias Prazo Maximo:') !!}
    <p class="form-control">{!! $lembrete->dias_prazo_maximo !!}</p>
</div>

<!-- Deleted At Field -->
<div class="form-group col-md-6">
    {!! Form::label('deleted_at', 'Deleted At:') !!}
    <p class="form-control">{!! $lembrete->deleted_at !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group col-md-6">
    {!! Form::label('created_at', 'Created At:') !!}
    <p class="form-control">{!! $lembrete->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-md-6">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p class="form-control">{!! $lembrete->updated_at !!}</p>
</div>

