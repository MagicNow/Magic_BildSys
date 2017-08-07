<!-- Nome Field -->
<div class="form-group col-md-6">
    {!! Form::label('nome', 'Nome:') !!}
    <p class="form-control">{!! $carteira->nome !!}</p>
</div>

<div class="form-group col-sm-6">
    {!! Form::label('carteiraUsers', 'Usuários nesta carteira:') !!}
    @if($carteira->users)
        @php $usuarios = ''; @endphp
        @foreach($carteira->users as $users)
            @php $usuarios .= $users->name . ' - '; @endphp
        @endforeach
        {!! Form::textarea('nome', substr($usuarios, 0, -3), ['class' => 'form-control', 'rows' => '3', 'disabled']) !!}
    @else
        <p class="form-control"></p>
    @endif
</div>

<div class="form-group col-sm-6">
    {!! Form::label('carteiraTipoEqualizacaoTecnicas', 'Tipos Equalização Técnica nesta carteira:') !!}
    @if($carteira->tipoEquilizacaoTecnicas)
        @php $tipos = ''; @endphp
        @foreach($carteira->tipoEquilizacaoTecnicas as $tipoEquilizacaoTecnicas)
            @php $tipos .= $tipoEquilizacaoTecnicas->nome . ' - '; @endphp
        @endforeach
        {!! Form::textarea('nome', substr($tipos, 0, -3), ['class' => 'form-control', 'rows' => '3', 'disabled']) !!}
    @else
        <p class="form-control"></p>
    @endif
</div>

<!-- Created At Field -->
<div class="form-group col-md-6">
    {!! Form::label('created_at', 'Cadastrada em:') !!}
    <p class="form-control">{!! $carteira->created_at->format('d/m/Y H:i') !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-md-6">
    {!! Form::label('updated_at', 'Alterada em:') !!}
    <p class="form-control">{!! $carteira->updated_at->format('d/m/Y H:i') !!}</p>
</div>
