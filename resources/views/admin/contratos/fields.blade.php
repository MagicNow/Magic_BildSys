<div class="form-group col-sm-6">
    {!! Form::label('obra_id', 'Obra:') !!}
    {!! Form::select('obra_id',[''=>'Escolha...']+ \App\Models\Obra::pluck('nome','id')->toArray() , null, ['class' => 'form-control','required'=>'required']) !!}
</div>

<!-- Data Field -->
<div class="form-group col-sm-6">
    {!! Form::label('data', 'Data:') !!}
    {!! Form::date('data', null, ['class' => 'form-control','required'=>'required']) !!}
</div>

<!-- Valor Field -->
<div class="form-group col-sm-6">
    {!! Form::label('valor', 'Valor:') !!}
    {!! Form::text('valor', null, ['class' => 'form-control money','required'=>'required']) !!}
</div>

<!-- Arquivo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('arquivo', 'Arquivo:') !!}
    @if(@isset($contratos))
        @if($contratos->arquivo)
            <a href="{{$contratos->arquivo}}" download>Baixar arquivo</a>
        @endif
    @endif
    {!! Form::file('arquivo', null, ['class' => 'form-control']) !!}
</div>

<?php
$count_insumos = 0;
?>

<div class="modal-header">
    <div class="col-md-12">
        <div class="col-md-9">
                <span class="pull-left title">
                    Insumos
                </span>
        </div>
    </div>
</div>

@if(isset($pessoa))
    @foreach ($pessoa->emails as $insumo )
        <?php
        $count_insumos = $insumo->id;
        ?>
        <div class="form-group col-md-12" id="block_insumos{{$insumo->id}}">
            {!! Form::hidden('insumos['.$insumo->id.'][id]', $insumo->id) !!}
            <div class="col-md-11">\
                <label>Insumo:</label>\
                {!! Form::select('insumos['.$insumo->id.'][insumo_id]',[''=>'Escolha...']+ \App\Models\Obra::pluck('nome','id')->toArray() , null, ['class' => 'form-control','required'=>'required']) !!}
            </div>\
            <div class="col-md-1" align="right" style="margin-top:25px;">\
                <button type="button" onclick="removeInsumo({{$insumo->id}})" class="btn btn btn-danger" aria-label="Close" title="Remover" >\
                    <i class="fa fa-times"></i>\
                </button>\
            </div>\
            <div class="col-md-4">\
                <label>Quantidade:</label>\
                <input type="number" value="{{$insumo->qtd}}" class="form-control" name="insumos[{{$insumo->id}}][qtd]" required>\
            </div>\
            <div class="col-md-4">\
                <label>Valor unitário:</label>\
                <input type="text" value="{{$insumo->valor_unitario}}" class="form-control money" name="insumos[{{$insumo->id}}][valor_unitario]" required>\
            </div>\
            <div class="col-md-4">\
                <label>Valor total:</label>\
                <input type="text" value="{{$insumo->valor_total}}" disabled class="form-control money" name="insumos[{{$insumo->id}}][valor_total]" required>\
            </div>\
            <div class="col-md-12 border-separation"></div>
        </div>
    @endforeach
@endif
<div id="insumos"></div>
<div id="add_insumos" class="col-md-12" style="margin-bottom:25px;margin-top:25px">
    <span class="btn btn-info" onclick="addInsumo()">
        <i class="fa fa-plus"></i> Adicionar insumo
    </span>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('admin.contratos.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>


<script>
    var count_insumos = '{{$count_insumos}}';

    function addInsumo(){
        var options_insumos = '';
        count_insumos++;
        @foreach($insumos as $insumo)
            options_insumos += '<option value="{{$insumo->id}}">{{$insumo->nome}}</option>';
        @endforeach
        var block_insumos = '<div class="form-group col-md-12" id="block_insumos'+count_insumos+'">\
                                <div class="col-md-11">\
                                <label>Insumo:</label>\
                                    <select class="form-control" name="insumos['+count_insumos+'][insumo_id]" required>\
                                        <option value="" >Selecione um insumo</option>' + options_insumos + '\
                                    </select>\
                                </div>\
                                <div class="col-md-1" align="right" style="margin-top:25px;">\
                                    <button type="button" onclick="removeInsumo('+count_insumos+')" class="btn btn btn-danger" aria-label="Close" title="Remover" >\
                                        <i class="fa fa-times"></i>\
                                    </button>\
                                </div>\
                                <div class="col-md-4">\
                                    <label>Quantidade:</label>\
                                    <input type="number" class="form-control" name="insumos['+count_insumos+'][qtd]" required>\
                                </div>\
                                <div class="col-md-4">\
                                    <label>Valor unitário:</label>\
                                    <input type="text" class="form-control money" name="insumos['+count_insumos+'][valor_unitario]" required>\
                                </div>\
                                <div class="col-md-4">\
                                    <label>Valor total:</label>\
                                    <input type="text" disabled class="form-control money" name="insumos['+count_insumos+'][valor_total]" required>\
                                </div>\
                                <div class="col-md-12 border-separation"></div>\
                            </div>';
        $("#add_insumos").animate({
            // distância do topo
            marginTop: "200px"
            // tempo de execucao - milissegundos
        }, 1000, function() {
            $('#insumos').append(block_insumos);
            $('#add_insumos').css('margin-top','25px');
        });
    }

    function removeInsumo(what){
        $('#block_insumos'+what).slideUp('slow', function(){$('#block_insumos'+what).remove(); });
    }

    function deleteInsumo(what){
        swal({
                    title: "Você tem certeza?",
                    text: "Você não poderá mais recuperar este registro!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: "Cancelar",
                    confirmButtonText: "Sim, Remover",
                    closeOnConfirm: false
                },
                function(){
                    $.ajax({
                        url: "/admin/insumo/delete",
                        data: {email : what}
                    }).done(function(retorno) {
                        if(retorno.sucesso){
                            removeInsumo(what);
                            swal(retorno.resposta.toString());
                        }else{
                            swal(retorno.resposta.toString());
                        }
                    });
                });
    }
</script>