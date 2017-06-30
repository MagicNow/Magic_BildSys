<!-- Nome Field -->
<div class="form-group col-sm-4">
    {!! Form::label('nome', 'Nome:') !!}
    {!! Form::text('nome', null, ['class' => 'form-control','required'=>'required']) !!}
</div>

<!-- Modo Field -->
<div class="form-group col-sm-4">
    {!! Form::label('modo', 'Modo:') !!}
    {!! Form::select('modo',['T'=>'Torre','C'=>'Cartela','U'=>'Unidade'], null, ['class' => 'form-control select2', 'required'=>'required']) !!}
</div>

<!-- Padrão Field -->
<div class="form-group col-sm-2">
    {!! Form::label('padrao', 'Padrão:') !!}
    <div class="form-control">
        {!! Form::checkbox('padrao',1, null,['id'=>'padrao']) !!}
    </div>

</div>
<div class="form-group col-sm-2">
    {!! Form::label('bloco', 'Blocos:') !!}
    <div >
        <button type="button" class="btn btn-flat btn-primary btn-block">
            <i class="fa fa-plus"></i> Adicionar
        </button>
    </div>
</div>
<div >
    <div class="col-sm-12">
        <ul class="list-group">
            <li class="list-group-item">
                <div class="row" style="margin-bottom: 10px">
                    <div class="col-sm-10">

                        <i class="fa fa-th-large"></i>
                        Estrutura:
                        {!! Form::select('estrutura_bloco[1]',
                        [''=> 'Escolha'] +
                        \App\Models\NomeclaturaMapa::where('tipo',1)->orderBy('nome')->pluck('nome','id')->toArray(),
                        null,
                        ['class'=>'form-control select2'] ) !!}

                    </div>
                    <div class="col-sm-2" style="min-height: 54px; padding-top: 20px">
                        <button type="button" class="btn btn-flat btn-info btn-block">
                            <i class="fa fa-plus"></i> Adicionar Pavimento
                        </button>
                    </div>
                </div>
                <div style="clear: both">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="row" style="margin-bottom: 10px">
                                <div class="col-sm-10">

                                    <i class="fa fa-th-large"></i>
                                    Pavimento:
                                    {!! Form::select('estrutura_bloco[1]',
                                    [''=> 'Escolha'] +
                                    \App\Models\NomeclaturaMapa::where('tipo',2)->orderBy('nome')->pluck('nome','id')->toArray(),
                                    null,
                                    ['class'=>'form-control select2'] ) !!}

                                </div>
                                <div class="col-sm-2" style="min-height: 54px; padding-top: 20px">
                                    <button type="button" class="btn btn-flat btn-warning btn-block">
                                        <i class="fa fa-plus"></i> Adicionar Trecho
                                    </button>
                                </div>
                            </div>

                            <div style="clear: both">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class="input-group">
                                            <span class="input-group-addon" id="trecho1">Trecho</span>
                                            {!! Form::select('estrutura_bloco[1]',
                                            [''=> 'Escolha'] +
                                            \App\Models\NomeclaturaMapa::where('tipo',3)->orderBy('nome')->pluck('nome','id')->toArray(),
                                            null,
                                            ['class'=>'form-control select2', 'aria-describedby'=>'trecho1'] ) !!}
                                        </div>

                                    </li>
                                    <li class="list-group-item">
                                        <div class="input-group">
                                            <span class="input-group-addon" id="trecho2">Trecho</span>
                                            {!! Form::select('estrutura_bloco[1]',
                                            [''=> 'Escolha'] +
                                            \App\Models\NomeclaturaMapa::where('tipo',3)->orderBy('nome')->pluck('nome','id')->toArray(),
                                            null,
                                            ['class'=>'form-control select2', 'aria-describedby'=>'trecho2'] ) !!}
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </li>

        </ul>
    </div>
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('memoriaCalculos.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>
