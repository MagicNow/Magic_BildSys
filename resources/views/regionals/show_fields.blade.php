<!-- Nome Field -->
<div class="form-group col-md-12">
    {!! Form::label('nome', 'Nome:') !!}
    <p class="form-control">{!! $regional->nome !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group col-md-6">
    {!! Form::label('created_at', 'Criado em:') !!}
    <p class="form-control">{!! $regional->created_at->format('d/m/Y') !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-md-6">
    {!! Form::label('updated_at', 'Alterado em:') !!}
    <p class="form-control">{!! $regional->updated_at->format('d/m/Y') !!}</p>
</div>
