
<!-- Nome Field -->
<div class="form-group col-md-6">
    {!! Form::label('nome', 'Nome:') !!}
    <p class="form-control">{!! $lembreteTipo->nome !!}</p>
</div>

<!-- Dias Prazo Minimo Field -->
<div class="form-group col-md-3">
    {!! Form::label('dias_prazo_minimo', 'Dias Prazo Minimo:') !!}
    <p class="form-control">{!! $lembreteTipo->dias_prazo_minimo !!}</p>
</div>

<!-- Dias Prazo Maximo Field -->
<div class="form-group col-md-3">
    {!! Form::label('dias_prazo_maximo', 'Dias Prazo Maximo:') !!}
    <p class="form-control">{!! $lembreteTipo->dias_prazo_maximo !!}</p>
</div>

