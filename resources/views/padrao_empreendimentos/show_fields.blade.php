<!-- Nome Field -->
<div class="form-group col-md-6">
    {!! Form::label('nome', 'Nome:') !!}
    <p class="form-control">{!! $padraoEmpreendimento->nome !!}</p>
</div>

<!-- Cor Field -->
<div class="form-group col-md-6">
    {!! Form::label('cor', 'Cor:') !!}
    <p class="form-control" style="background-color: {!! $padraoEmpreendimento->cor !!}"></p>
</div>

<!-- Created At Field -->
<div class="form-group col-md-6">
    {!! Form::label('created_at', 'Criado em:') !!}
    <p class="form-control">{!! $padraoEmpreendimento->created_at->format('d/m/Y') !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-md-6">
    {!! Form::label('updated_at', 'Alterado em:') !!}
    <p class="form-control">{!! $padraoEmpreendimento->updated_at->format('d/m/Y') !!}</p>
</div>
