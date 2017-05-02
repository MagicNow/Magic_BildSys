<!-- Fornecedores Field -->
<div class="form-group col-sm-12">
    {!! Form::label('fornecedor_cod', 'Fornecedores:') !!}
    {!! Form::select('fornecedor_cod', ['' => 'Escolha...']+$fornecedores, null, ['class' => 'form-control','id'=>'fornecedor_cod','required'=>'required']) !!}
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

@if(isset($contratos))
    @foreach ($contratos->contratoInsumos as $insumo )
        <?php
        $count_insumos = $insumo->id;
        ?>
        <div class="form-group col-md-12" id="block_insumos{{$insumo->id}}">
            {!! Form::hidden('insumos['.$insumo->id.'][id]', $insumo->id) !!}
            <div class="col-md-11">
                <label>Insumo:</label>
                {!! Form::select('insumos['.$insumo->id.'][insumo_id]',[''=>'Escolha...']+ \App\Models\Insumo::pluck('nome','id')->toArray(), $insumo->insumo_id, ['class' => 'form-control','required'=>'required']) !!}
            </div>
            <div class="col-md-1" align="right" style="margin-top:25px;">
                <button type="button" onclick="deleteInsumo({{$insumo->id}})" class="btn btn btn-danger" aria-label="Close" title="Remover" >
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="col-md-4">
                <label>Quantidade:</label>
                <input type="text" value="{{$insumo->qtd}}" id="qtd_{{$insumo->id}}" class="form-control money" name="insumos[{{$insumo->id}}][qtd]" required onkeyup="calcularValorTotalInsumo({{$insumo->id}});">
            </div>
            <div class="col-md-4">
                <label>Valor unitário:</label>
                <input type="text" value="{{$insumo->valor_unitario}}" id="valor_unitario_{{$insumo->id}}" class="form-control money" name="insumos[{{$insumo->id}}][valor_unitario]" required onkeyup="calcularValorTotalInsumo({{$insumo->id}});">
            </div>
            <div class="col-md-4">
                <label>Valor total:</label>
                <span class="form-control" id="valor_total_span_{{$insumo->id}}">{{$insumo->valor_total}}</span>
                <input type="hidden" value="{{$insumo->valor_total}}" id="valor_total_{{$insumo->id}}" name="insumos[{{$insumo->id}}][valor_total]">
            </div>
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
    <a href="{!! route('admin.catalogo_contratos.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>

@section('scripts')
<script type="text/javascript">
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
                                    <input type="text" class="form-control money" id="qtd_'+count_insumos+'" name="insumos['+count_insumos+'][qtd]" required onkeyup="calcularValorTotalInsumo('+count_insumos+');">\
                                </div>\
                                <div class="col-md-4">\
                                    <label>Valor unitário:</label>\
                                    <input type="text" class="form-control money" id="valor_unitario_'+count_insumos+'" name="insumos['+count_insumos+'][valor_unitario]" required onkeyup="calcularValorTotalInsumo('+count_insumos+');">\
                                </div>\
                                <div class="col-md-4">\
                                    <label>Valor total:</label>\
                                    <span class="form-control" id="valor_total_span_'+count_insumos+'"></span>\
                                    <input type="hidden" id="valor_total_'+count_insumos+'" name="insumos['+count_insumos+'][valor_total]">\
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
                data: {insumo : what}
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

    function calcularValorTotalInsumo(what) {
        var quantidade = $('#qtd_'+what).val();
        var valor_unitario = $('#valor_unitario_'+what).val();

        $.ajax({
            url: "/admin/insumo/valor_total",
            data: {
               'quantidade' : quantidade,
               'valor_unitario' : valor_unitario
            }
        }).done(function(retorno) {
            if(retorno.valor_total){
                $('#valor_total_span_'+what).html(retorno.valor_total).addClass('money');
                $('#valor_total_'+what).val(retorno.valor_total);
            }else{
                swal('Erro ao calcular o valor total');
            }
        });
    }

    function formatResult (obj) {
        if (obj.loading) return obj.text;

        var markup =    "<div class='select2-result-obj clearfix'>" +
                "   <div class='select2-result-obj__meta'>" +
                "       <div class='select2-result-obj__title'>" + obj.agn_st_nome + "</div>"+
                "   </div>"+
                "</div>";

        return markup;
    }

    function formatResultSelection (obj) {
        if(obj.agn_st_nome){
            return obj.agn_st_nome;
        }
        return obj.text;
    }

    $(function(){
        $('#fornecedor_cod').select2({
            allowClear: true,
            placeholder:"-",
            language: "pt-BR",
            ajax: {
                url: "{{ route('admin.catalogo_contratos.busca_fornecedores') }}",
                dataType: 'json',
                delay: 250,

                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },

                processResults: function (result, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                    params.page = params.page || 1;

                    return {
                        results: result.data,
                        pagination: {
                            more: (params.page * result.per_page) < result.total
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 1,
            templateResult: formatResult, // omitted for brevity, see the source of this page
            templateSelection: formatResultSelection // omitted for brevity, see the source of this page

        });
    });
</script>
@endsection


