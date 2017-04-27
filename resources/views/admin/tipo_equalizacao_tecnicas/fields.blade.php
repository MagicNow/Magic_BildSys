<!-- Nome Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nome', 'Nome:') !!}
    {!! Form::text('nome', null, ['class' => 'form-control']) !!}
</div>

<div class="col-sm-12">

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#tab_itens" aria-controls="tab_itens" role="tab" data-toggle="tab">Itens</a></li>
        <li role="presentation"><a href="#tab_anexos" aria-controls="tab_anexos" role="tab" data-toggle="tab">Anexos</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="tab_itens">
            <fieldset class="col-sm-12" style="min-width: 300px">
                <legend>Itens
                    <small class="pull-right" style="font-size: 11px; margin-top: 5px;">
                        <button type="button" class="btn btn-warning btn-xs" onclick="addItens();">
                            <i class="fa fa-plus"></i> Adicionar
                        </button>
                    </small>
                </legend>
                <div id="itens">
                    <?php $qtdItens = 0; ?>
                    @if(isset($tipoEqualizacaoTecnica))
                        @foreach($tipoEqualizacaoTecnica->itens as $item)
                            <?php $qtdItens++; ?>
                            <div id="item_{{$qtdItens}}">
                                <!-- idioma Id Field -->
                                <div class="form-group col-sm-11">
                                    {!! Form::label('itens['.$qtdItens.'][nome]', 'Nome:') !!}
                                    {!! Form::text('itens['.$qtdItens.'][nome]', $item->nome, ['class' => 'form-control']) !!}
                                </div>

                                <div class="form-group col-sm-11">
                                    {!! Form::label('itens['.$qtdItens.'][descricao]', 'Descrição:') !!}
                                    {!! Form::textarea('itens['.$qtdItens.'][descricao]', $item->descricao, ['class' => 'form-control', 'rows'=>3]) !!}
                                </div>

                                <div class="form-group col-sm-11">
                                    {!! Form::label('itens['.$qtdItens.'][obrigatorio]', 'Obrigatório: ') !!}
                                        @if($item->obrigatorio == 1)
                                            {!! Form::checkbox('itens['.$qtdItens.'][obrigatorio]', 1, null, [ 'id'=>'obrigatorio', 'checked'=>'checked']) !!}
                                        @else
                                            {!! Form::checkbox('itens['.$qtdItens.'][obrigatorio]', 1, null, [ 'id'=>'obrigatorio']) !!}
                                        @endif
                                </div>
                                {!! Form::hidden('itens['.$qtdItens.'][id]',$item->id) !!}
                                {!! Form::hidden('itens['.$qtdItens.'][tipo_equalizacao_tecnica_id]',$item->tipo_equalizacao_tecnica_id) !!}

                                <div class="form-group col-sm-1">
                                    <button type="button" onclick="remExtra({{$qtdItens}},'item_')" class="btn btn-danger" style="margin-top: 24px" title="Remover">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </fieldset>
        </div>
        <div role="tabpanel" class="tab-pane" id="tab_anexos">
            <fieldset class="col-sm-12" style="min-width: 300px">
                <legend>Anexos
                    <small class="pull-right" style="font-size: 11px; margin-top: 5px;">
                        <button type="button" class="btn btn-warning btn-xs" onclick="addAnexos();">
                            <i class="fa fa-plus"></i> Adicionar
                        </button>
                    </small>
                </legend>
                <div id="anexos">
                    @if(isset($tipoEqualizacaoTecnica))
                        <?php $qtdanexos = 0; ?>
                        @foreach($tipoEqualizacaoTecnica->anexos as $anexo)
                            <?php $qtdanexos++; ?>
                            <div id="arquivo_{{$qtdanexos}}">
                                <!-- idioma Id Field -->
                                <div class="form-group col-sm-7">
                                    {!! Form::label('anexos['.$qtdanexos.'][nome]', 'Nome:') !!}
                                    {!! Form::text('anexos['.$qtdanexos.'][nome]', $anexo->nome, ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group col-sm-7">
                                    {!! Form::label('anexos['.$qtdanexos.'][arquivo]', 'Imagem:') !!}
                                    {!! Form::hidden('anexos['.$qtdanexos.'][arquivo_atual]',$anexo->arquivo) !!}
                                    {!! Form::hidden('anexos['.$qtdanexos.'][id]',$anexo->id) !!}
                                    {!! Form::hidden('anexos['.$qtdanexos.'][tipo_equalizacao_tecnica_id]',$anexo->tipo_equalizacao_tecnica_id) !!}
                                    <a href="{!! Storage::url($anexo->arquivo) !!}" target="_blank" title="Exibir imagem">
                                        <img src="{!! Storage::url($anexo->arquivo) !!}" width="100">
                                    </a>
                                    {!! Form::file('anexos['.$qtdanexos.'][arquivo]', null, ['class' => 'form-control']) !!}
                                </div>

                                <div class="form-group col-sm-1">
                                    <button type="button" onclick="remExtra({{$qtdanexos}},'arquivo_')" class="btn btn-danger" style="margin-top: 24px" title="Remover">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>

                            </div>
                        @endforeach
                    @endif
                </div>
            </fieldset>
        </div>
    </div>

</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('admin.tipoEqualizacaoTecnicas.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>

@section('scripts')
    <script type="text/javascript">
        var qtditens = {{ isset($tipoEqualizacaoTecnica)?$qtdItens:'0' }};
        var qtdanexos = {{ isset($tipoEqualizacaoTecnica)?$qtdanexos:'0' }};

        function addItens() {
            qtditens++;
            $('#itens').append('<div id="item_'+qtditens+'">' +
                    '<div class="form-group col-sm-11">'+
                    '<label for="itens['+qtditens+'][nome]">Nome:</label>'+
                    '<input class="form-control" name="itens[' + qtditens + '][nome]" type="text" id="itens['+qtditens+'][nome]" required="required" />'+
                    '</div>'+
                    '<div class="form-group col-sm-11">'+
                    '<label for="itens['+qtditens+'][descricao]">Descrição:</label>'+
                    '<textarea class="form-control" name="itens[' + qtditens + '][descricao]" type="text" id="itens['+qtditens+'][descricao]"/></textarea>'+
                    '</div>'+
                    '<div class="form-group col-sm-11">'+
                    '<label for="itens['+qtditens+'][obrigatorio]">Obrigatório: </label> '+
                    '<input class="icheckbox_square-green hover" type="checkbox" name="itens[' + qtditens + '][obrigatorio]"><br>'+
                    '</div>'+
                    '<div class="form-group col-sm-1"><button type="button" onclick="remExtra('+qtditens+',\'item_\')" class="btn btn-danger" style="margin-top: 24px" title="Remover"><i class="fa fa-times"></i></button> </div>'+
                    '</div>');
        }

        function addAnexos() {
            qtdanexos++;
            $('#anexos').append('<div id="arquivo_'+qtdanexos+'">' +
                    '<div class="form-group col-sm-11">'+
                    '<label for="anexos['+qtdanexos+'][nome]">Nome:</label>'+
                    '<input class="form-control" name="anexos[' + qtdanexos + '][nome]" type="text"/>'+
                    '</div>'+
                    '<div class="form-group col-sm-11">'+
                    '<label for="anexos['+qtdanexos+'][arquivo]">Imagem:</label>'+
                    '<input class="form-control" name="anexos[' + qtdanexos + '][arquivo]" type="file" required="required" />'+
                    '</div>'+
                    '<div class="form-group col-sm-1"><button type="button" onclick="remExtra('+qtdanexos+',\'arquivo_\')" class="btn btn-danger" style="margin-top: 24px" title="Remover"><i class="fa fa-times"></i></button> </div>'+
                    '</div>');
        }

        function remExtra(qual, nome){
            $('#'+nome+''+qual).remove();
        }
    </script>
@stop
