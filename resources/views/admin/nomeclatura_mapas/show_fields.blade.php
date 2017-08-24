<!-- Id Field -->
<div class="form-group col-md-2">
    {!! Form::label('id', 'Id:') !!}
    <p class="form-control">{!! $nomeclaturaMapa->id !!}</p>
</div>

<!-- Nome Field -->
<div class="form-group col-md-6">
    {!! Form::label('nome', 'Nome:') !!}
    <p class="form-control">{!! $nomeclaturaMapa->nome !!}</p>
</div>

<!-- Tipo Field -->
<div class="form-group col-md-4">
    {!! Form::label('tipo', 'Tipo:') !!}
    <?php
            $tipos = ['1'=>'Bloco','2'=>'Linha','3'=>'Coluna'];
            ?>
    <p class="form-control">{!! $tipos[$nomeclaturaMapa->tipo] !!}</p>
</div>

<!-- Apenas Cartela Field -->
<div class="form-group col-md-6">
    {!! Form::label('apenas_cartela', 'Apenas Cartela:') !!}
    <p class="form-control">{!! '<span class="label label-'.(intval($nomeclaturaMapa->apenas_cartela)?'success':'danger').'">'. (intval($nomeclaturaMapa->apenas_cartela)?'SIM':'NÃO') .'</span>' !!}</p>
</div>

<!-- Apenas Unidade Field -->
<div class="form-group col-md-6">
    {!! Form::label('apenas_unidade', 'Apenas Unidade:') !!}
    <p class="form-control">{!! '<span class="label label-'.(intval($nomeclaturaMapa->apenas_unidade)?'success':'danger').'">'. (intval($nomeclaturaMapa->apenas_unidade)?'SIM':'NÃO') .'</span>' !!}</p>
</div>

