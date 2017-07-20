<!-- Qtd Field -->
<div class="form-group col-md-4">
    {!! Form::label('qtd', 'Qtd:') !!}
    <p class="form-control text-right">{!! float_to_money($medicao->qtd,'')  !!}</p>
</div>


<!-- User Id Field -->
<div class="form-group col-md-4">
    {!! Form::label('user_id', 'Usuário:') !!}
    <p class="form-control">{!! $medicao->user->name !!}</p>
</div>

<!-- Aprovado Field -->
<div class="form-group col-md-4">
    {!! Form::label('aprovado', 'Aprovação:') !!}
    <p class="form-control">{!! is_null($medicao->aprovado)?'Ainda não verificado': ($medicao->aprovado?'APROVADO':'REPROVADO') !!}</p>
</div>

<!-- Obs Field -->
<div class="form-group col-md-12">
    {!! Form::label('obs', 'Obs:') !!}
    <p class="form-control">{!! $medicao->obs !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group col-md-6">
    {!! Form::label('created_at', 'Criado em:') !!}
    <p class="form-control">{!! $medicao->created_at->format('d/m/Y H:i') !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-md-6">
    {!! Form::label('updated_at', 'Última Alteração:') !!}
    <p class="form-control">{!! $medicao->updated_at->format('d/m/Y H:i') !!}</p>
</div>

<!-- Imagens Field -->
<div class="form-group col-md-12">
    {!! Form::label('Imagens', 'Imagens:') !!}
    <p>
        @if($medicao->medicaoImagens->count())
            @foreach($medicao->medicaoImagens as $imagem)
                <a class="colorbox-photo" rel="galeria" href="{!! url('/imagem?file='.$imagem->imagem.'&mode=resize&height=940&width=940') !!}">
                    <img width="100"
                            src="{!! url('/imagem?file='.$imagem->imagem.'&mode=resize&width=100') !!}">
                </a>
            @endforeach
        @endif
    </p>
</div>

