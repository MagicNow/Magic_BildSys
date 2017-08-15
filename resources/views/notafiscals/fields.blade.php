<div class="col-sm-6" style="height: 700px !important;">
        <div class="col-md-12" style="margin-top: 10px;height: 100%;">
            {{--<img src="" class="img-rounded" id="arquivoNfe">--}}
            <iframe type="application/pdf"
                    src="/danfe/{{ $notafiscal->id }}"
                    id="arquivoNfe"
                    frameborder="0"
                    marginheight="0"
                    marginwidth="0"
            width="100%"
            height="100%">
            </iframe>
        </div>
</div>
<div class="col-md-6">
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                  NOTA FISCAL
                </h4>
            </div>
            <div>
                <div class="panel-body">
                    <!-- Contrato Id Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::label('contrato_id', 'Contrato:') !!}
                        {!! Form::select('contrato_id',[''=>'Escolha...'] + (isset($contrato) ? $contrato : []), null, ['class' => 'form-control select2']) !!}
                    </div>

                    <!-- Codigo Field -->
                    <div class="form-group col-sm-4">
                        {!! Form::label('codigo', 'Número NFe:') !!}
                        {!! Form::text('codigo', null, ['class' => 'form-control']) !!}
                    </div>

                    <!-- Versao Field -->
                    <div class="form-group col-sm-4">
                        {!! Form::label('versao', 'Versão:') !!}
                        {!! Form::text('versao', null, ['class' => 'form-control']) !!}
                    </div>

                    <!-- Natureza Operacao Field -->
                    <div class="form-group col-sm-4">
                        {!! Form::label('natureza_operacao', 'Natureza Operação:') !!}
                        {!! Form::text('natureza_operacao', null, ['class' => 'form-control']) !!}
                    </div>

                    <!-- Cnpj Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('cnpj', 'Cnpj:') !!}
                        {!! Form::text('cnpj', null, ['class' => 'form-control']) !!}
                    </div>

                    <!-- Cnpj Destinatario Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('cnpj_destinatario', 'Cnpj Destinatário:') !!}
                        {!! Form::text('cnpj_destinatario', null, ['class' => 'form-control']) !!}
                    </div>

                    <!-- Razao Social Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('razao_social', 'Razão Social:') !!}
                        {!! Form::text('razao_social', null, ['class' => 'form-control']) !!}
                    </div>

                    <!-- Fantasia Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('fantasia', 'Fantasia:') !!}
                        {!! Form::text('fantasia', null, ['class' => 'form-control']) !!}
                    </div>

                    <!-- Data Emissao Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('data_emissao', 'Data Emissão:') !!}
                        {!! Form::date('data_emissao', $notafiscal->data_emissao, ['class' => 'form-control']) !!}
                    </div>

                    <!-- Data Saida Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('data_saida', 'Data Saída:') !!}
                        {!! Form::date('data_saida', $notafiscal->data_saida, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right" style="margin-top: 10px;">
            <p>
                <button type="button" class="btn btn-primary btn-ms" onclick="addItens()">
                    ITENS DA NOTA FISCAL
                </button>
            </p>
        </div>
        <div id="itens">
            <?php
            $qtdItens = 0;

            ?>
            @if(isset($notafiscal))
                @foreach($notafiscal->items as $item)
                    <?php
                        $qtdItens = $item->id;
                        ?>
                    <div id="item_{{$qtdItens}}">
                        <!-- idioma Id Field -->
                        <div class="form-group col-sm-11">
                            {!! Form::label('notaFiscalItens['.$qtdItens.'][nome_produto]', 'Nome:') !!}
                            {!! Form::text('notaFiscalItens['.$qtdItens.'][nome_produto]', $item->nome_produto, ['class' => 'form-control']) !!}
                        </div>

                        {!! Form::hidden('notaFiscalItens['.$qtdItens.'][id]',$item->id) !!}
                        {!! Form::hidden('notaFiscalItens['.$qtdItens.'][tipo_equalizacao_tecnica_id]',$item->tipo_equalizacao_tecnica_id) !!}
                        <!--
                        <div class="form-group col-sm-1">
                            <button type="button" onclick="remExtra({{$qtdItens}},'item_')" class="btn btn-danger" style="margin-top: 24px" title="Remover">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                        //-->
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12" style="margin-top: 20px;">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('notafiscals.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>

@section('scripts')
    <script type="text/javascript">
        var qtditens = {{ isset($notafiscal)? $qtdItens:'0' }};

        function addItens() {
            qtditens++;
            $('#itens').append(
                    '<div id="item_'+qtditens+'" class="panel panel-default">' +
                        '<div class="panel-heading" role="tab" id="heading_'+qtditens+'">' +
                            '<h4 class="panel-title">' +
                                '<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_'+qtditens+'" aria-expanded="false" aria-controls="collapse_'+qtditens+'">' +
                                    "Item: " +qtditens + '<span type="button" onclick="remExtra('+qtditens+',\'item_\')" class="btn btn-danger btn-xs pull-right" title="Remover"><i class="fa fa-times"></i></span>'+
                                '</a>' +
                            '</h4>' +
                        '</div>' +
                        '<div id="collapse_'+qtditens+'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_'+qtditens+'">' +
                            '<div class="panel-body">' +
                                "Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS."+
                            '</div>' +
                        '</div>' +
                    '</div>'
//                    '<div id="item_'+qtditens+'">' +
//                    '<div class="form-group col-sm-11">'+
//                    '<label for="itens['+qtditens+'][nome]">Nome:</label>'+
//                    '<input class="form-control" name="itens[' + qtditens + '][nome]" type="text" id="itens['+qtditens+'][nome]" required="required" />'+
//                    '</div>'+
//                    '<div class="form-group col-sm-1"><button type="button" onclick="remExtra('+qtditens+',\'item_\')" class="btn btn-danger" style="margin-top: 24px" title="Remover"><i class="fa fa-times"></i></button> </div>'+
//                    '</div>'
            );
        }

        function readURL(input) {
            startLoading();
            if (input.files && input.files[0]) {
                var view = new FileReader();
                view.onload = function (e) {
//                    window.open(e.target.result);
                    $('#arquivoNfe')
                            .attr('src', e.target.result)
                            .width(620)
                            .height(700);
                };
                view.readAsDataURL(input.files[0]);
            }
            stopLoading();
            $('#arquivo_nfe').val($('#arquivoNfe').val());
        }

        function remExtra(qual, nome){
            $('#'+nome+''+qual).remove();
        }
    </script>
@stop
