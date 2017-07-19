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
    {!! Form::text('percentual', null, ['required'=>'required','class' => 'form-control money','id'=>'percentual','onblur'=>'checaTotal(1);','onkeyup'=>'atualizaValor(1);']) !!}
</div>

<div class="form-group col-sm-12" id="blocoObs" style="display: none">
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
        var countImagens = 0;
        function addImagem() {
            countImagens++;
            $('#imagens').append('<li id="blocoArquivo'+countImagens+'">' +
                    '   <div class="input-group">' +
                    '       <input name="medicaoImagens[]" required="required"  accept="image/*" id="arquivo'+countImagens+'" class="form-control" type="file">' +
                    '       <span class="input-group-btn">' +
                    '           <button type="button" title="remover imagem" class="btn btn-danger btn-flat" ' +
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
    </script>
@stop