<div class="clearfix">
    <!-- Nome Field -->
    <div class="form-group col-md-6">
        {!! Form::label('nome', 'Nome:') !!}
        <p class="form-control">{!! $tipologia->nome !!}</p>
    </div>

    <!-- Created At Field -->
    <div class="form-group col-md-6">
        {!! Form::label('created_at', 'Cadastrada em:') !!}
        <p class="form-control">{!! $tipologia->created_at->format('d/m/Y H:i') !!}</p>
    </div>

    <!-- Updated At Field -->
    <div class="form-group col-md-6">
        {!! Form::label('updated_at', 'Alterada em:') !!}
        <p class="form-control">{!! $tipologia->updated_at->format('d/m/Y H:i') !!}</p>
    </div>
</div>