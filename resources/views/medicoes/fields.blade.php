{!! Form::hidden('mc_medicao_previsao_id', $mcMedicaoPrevisao->id) !!}
{!! Form::hidden('contrato_item_apropriacao_id', $contratoItemApropriacao->id) !!}
{!! Form::hidden('medicao_servico_id', $medicaoServico->id) !!}
<!-- Qtd Field -->
<div class="form-group col-sm-6">
    {!! Form::label('qtd', 'Qtd:') !!}
    {!! Form::text('qtd', null, ['required'=>'required','class' => 'form-control money','id'=>'qtd','onblur'=>'checaTotal(0);','onkeyup'=>'atualizaValor(0);']) !!}
</div>
<!-- Qtd Field -->
<div class="form-group col-sm-6">
    {!! Form::label('percentual', 'Percentual:') !!}
    {!! Form::text('percentual', (!isset($medicao)?null: number_format( ($medicao->qtd/$mcMedicaoPrevisao->qtd)*100,'2',',','.') ), ['required'=>'required','class' => 'form-control money','id'=>'percentual','onblur'=>'checaTotal(1);','onkeyup'=>'atualizaValor(1);']) !!}
</div>

<div class="form-group col-sm-12" id="blocoObs" style="{{ (!isset($medicao)?'display: none': strlen($medicao->obs)?'':'display: none' ) }}">
    {!! Form::label('obs', 'Observação da Medição:') !!}
    {!! Form::textarea('obs', null, ['class' => 'form-control', 'id'=>'obs', 'rows'=>4]) !!}
</div>
<div class="col-md-4 col-md-offset-4">
    <button type="button" class="btn btn-lg btn-primary btn-flat btn-block" onclick="addImagem();" title="Adicionar imagem">
        <i class="fa fa-plus" aria-hidden="true"></i>
        Adicionar imagens
        <i class="fa fa-picture-o" aria-hidden="true"></i>
    </button>
</div>
<div class="col-md-12">
    <ol id="imagens">
        <?php $countImagens = 0; ?>
        @if(isset($medicao))
            @if($medicao->medicaoImagens->count())
                @foreach($medicao->medicaoImagens as $imagem)
                    <li id="blocoArquivo{{ ++$countImagens }}">
                        {!! Form::hidden('medicaoImagemExistente['.$countImagens.'][imagem]',$imagem->imagem) !!}
                        {!! Form::hidden('medicaoImagemExistente['.$countImagens.'][id]',$imagem->id) !!}

                        <div class="input-group">
                            <a class="colorbox-photo input-group-addon" style="padding: 0px;"
                               id="imgPreview{{ $countImagens }}" rel="galeria"
                               href="{!! url('/imagem?file='.$imagem->imagem.'&mode=resize&height=940&width=940') !!}">
                                <img height="45"
                                     src="{!! url('/imagem?file='.$imagem->imagem.'&mode=resize&height=45') !!}">
                            </a>
                            <span class="form-control input-lg" aria-describedby="imgPreview{{ $countImagens }}" >
                                <?php $nomeImagem = str_replace('public/medicao/'.$mcMedicaoPrevisao->id.'/','', $imagem->imagem); ?>
                                {{ $nomeImagem }}
                            </span>

                            <span class="input-group-btn">
                                <button type="button" title="remover imagem" class="btn btn-lg btn-danger btn-flat"
                                    onclick="removerImagem({{ $countImagens }})">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </button>
                            </span>
                        </div>
                    </li>
                @endforeach
            @endif
        @endif
    </ol>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success btn-lg btn-flat pull-right', 'type'=>'submit']) !!}
    <button type="button" onclick="history.go(-1);" class="btn btn-default btn-lg btn-flat"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</button>
</div>
@section('scripts')
    @parent
    <script type="text/javascript">
        var countImagens = {{ $countImagens }};
        function addImagem() {
            countImagens++;
            $('#imagens').append('<li id="blocoArquivo'+countImagens+'">' +
                    '   <div class="input-group">' +
                    '       <span class="input-group-addon" style="padding: 0px;" id="imgPreview'+countImagens+'">' +
                    '       <img id="img'+countImagens+'" src="#" style="display: none; height: 45px;" height="45" alt="imagem" />' +
                    '       </span>' +
                    '       <input name="medicaoImagens[]" aria-describedby="imgPreview'+countImagens+'" required="required" ' +
                    '            accept="image/*" id="arquivo'+countImagens+'" class="form-control input-lg" type="file" onchange="readURL(this,'+countImagens+');">' +
                    '       <span class="input-group-btn">' +
                    '           <button type="button" title="remover imagem" class="btn btn-danger btn-lg btn-flat" ' +
                    '               onclick="removerImagem('+countImagens+')">' +
                    '               <i class="fa fa-times" aria-hidden="true"></i>' +
                    '           </button>' +
                    '       </span>' +
                    '   </div>' +
                    '</li>');
        }
        function removerImagem(qual) {
            $('#blocoArquivo'+qual).remove();
        }

        var valor_a_medir = {!! $mcMedicaoPrevisao->qtd !!};
        var maximo_qtd = {!! $mcMedicaoPrevisao->qtd !!};
        var maximo_percentual = 100;
        @if($medicoes)
            @if($medicoes->count())
                maximo_qtd = {{ ($mcMedicaoPrevisao->qtd - $medicoes->first()->qtd) }};
                maximo_percentual = {{ number_format( (100 - ($medicoes->first()->qtd/$mcMedicaoPrevisao->qtd) * 100),2,'.','') }};
            @endif
        @endif

        function readURL(input, qual) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#img'+qual).attr('src', e.target.result);
                    $('#img'+qual).show();
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function atualizaValor(percentual){
            var valor_qtd = $('#qtd').val();
            var valor_percentual = $('#percentual').val();
            var valor_final = 0;
            if(percentual){
                if(valor_percentual!=''){
                    valor_percentual = moneyToFloat(valor_percentual);
                    if(valor_percentual > maximo_percentual){
                        valor_percentual = maximo_percentual;
                        $('#percentual').val(floatToMoney(valor_percentual.toFixed(2),''));
                    }
                    valor_final = (valor_percentual / 100) * valor_a_medir;

                    valor_final_txt = floatToMoney(valor_final.toFixed(2),'');
                    $('#qtd').val(valor_final_txt);
                }
            }else{
                if(valor_qtd!=''){
                    valor_qtd = moneyToFloat(valor_qtd);
                    if(valor_qtd > maximo_qtd){
                        valor_qtd = maximo_qtd;
                        $('#qtd').val(floatToMoney(valor_qtd.toFixed(2),''));
                    }
                    valor_final = (valor_qtd / valor_a_medir) * 100;
                    valor_final_txt = floatToMoney(valor_final.toFixed(2),'');
                    $('#percentual').val(valor_final_txt);
                }
            }
        }

        function checaTotal() {

            var valor_percentual = $('#percentual').val();
            if(valor_percentual!=''){
                valor_percentual = moneyToFloat(valor_percentual);
                if(valor_percentual<100){
                    $('#blocoObs').show();
                    $('#obs').attr('required',true);
                }else{
                    $('#blocoObs').hide();
                    $('#obs').attr('required',false);
                }
            }
        }

    </script>
@stop