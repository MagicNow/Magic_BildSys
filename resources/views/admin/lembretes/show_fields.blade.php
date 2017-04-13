<!-- Lembrete Tipo Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('lembrete_tipo_id', 'Lembrete:') !!}
    <p class="form-control">{!! $lembrete->lembreteTipo->nome !!}</p>
</div>

<!-- User Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('user_id', 'Cadastrado por:') !!}
    <p class="form-control">{!! $lembrete->user->name !!}</p>
</div>

<!-- Nome Field -->
<div class="form-group col-md-6">
    {!! Form::label('nome', 'Nome:') !!}
    <p class="form-control">{!! $lembrete->nome !!}</p>
</div>

<!-- Dias Prazo Minimo Field -->
<div class="form-group col-md-6">
    {!! Form::label('dias_prazo_minimo', 'Dias Prazo Minimo:') !!}
    <p class="form-control">{!! $lembrete->dias_prazo_minimo . ' dias' !!}</p>
</div>


<!-- Created At Field -->
<div class="form-group col-md-6">
    {!! Form::label('created_at', 'Data criação:') !!}
    <p class="form-control">{!! $lembrete->created_at->format('d/m/Y H:m:s') !!}</p>
</div>

<!-- Dias Prazo Maximo Field -->
<div class="form-group col-md-6">
    {!! Form::label('dias_prazo_maximo', 'Dias Prazo Maximo:') !!}
    <p class="form-control">{!! $lembrete->dias_prazo_maximo . ' dias' !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-md-6">
    {!! Form::label('updated_at', 'Última alteração:') !!}
    <p class="form-control">{!! $lembrete->updated_at->format('d/m/Y H:m:s') !!}</p>
</div>

