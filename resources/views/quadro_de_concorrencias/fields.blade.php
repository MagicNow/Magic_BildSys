
<style type="text/css">
    .bloco_filtro{
        height: 100px;
        overflow-y: scroll;
        border: solid 2px #474747;
        background-color: #fff;
        font-size: 12px;
    }
    .list-group-item{
        border-radius: 0px !important;
    }
</style>
<!-- Fornecedores Field -->
<div class="form-group col-sm-6">
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    {!! Form::label('qcFornecedores', 'Fornecedores:') !!}
                </div>
                <div class="col-md-12">
                    {!! Form::select('fornecedores', ['' => 'Escolha...']+$fornecedoresRelacionados,
                    isset($quadroDeConcorrencia) ? $qcFornecedores_ids : null,
                    [
                        'class' => 'form-control',
                        'id'=>'fornecedor'
                    ]) !!}
                </div>
                <div class="col-md-12">
                    <button type="button" title="Cadastrar Fornecedor Temporariamente" style="margin-top: 10px"
                            id="cadastrarFornecedorTemporariamente"
                            onclick="cadastraFornecedor()" class="btn btn-block btn-flat btn-info">
                        <i class="fa fa-user-plus" aria-hidden="true"></i>
                        Cadastrar Temporariamente
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            {!! Form::label('qcFornecedores[]', 'Fornecedores Adicionados') !!}
            <ul class="list-group bloco_filtro" id="fornecedoresSelecionados">
                <?php
                    $qcFornecedorCount = 0;
                ?>
                @if($quadroDeConcorrencia->qcFornecedores()->where('rodada',$quadroDeConcorrencia->rodada_atual)->count())
                    @foreach($quadroDeConcorrencia->qcFornecedores()->where('rodada',
                                $quadroDeConcorrencia->rodada_atual)->get() as $qcFornecedor)
                            <?php
                            $qcFornecedorCount = $qcFornecedor->id;
                            ?>
                        <li class="list-group-item" id="qcFornecedor_id{{ $qcFornecedor->id }}">
                            <input type="hidden" id="qcFornecedores[]" value="{{ $qcFornecedor->fornecedor_id }}">
                            {{ $qcFornecedor->fornecedor->nome }}
                            <button type="button" class="btn btn-flat btn-danger btn-xs pull-right" title="remover"
                                    onclick="removerFornecedor({{ $qcFornecedor->id }},{{ $qcFornecedor->id }})">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                        </li>
                    @endforeach
                @endif
            </ul>

        </div>

    </div>

</div>

<!-- Tipo Equalização Técnica Field -->
<div class="form-group col-sm-6">
    <div class="row">
        <div class="col-md-5">
            {!! Form::label('tiposEqualizacaoTecnicas', 'Tipo Equalização Técnica:') !!}
            @if(count(\App\Models\TipoEqualizacaoTecnica::count()))
                <ul class="list-group bloco_filtro tiposEqT">
                    @foreach(\App\Models\TipoEqualizacaoTecnica::pluck('nome','id')->toArray() as
                                                        $tipoEqualizacaoTecnica_id => $tipoEqualizacaoTecnica_nome)
                        <li class="list-group-item">
                            {!! Form::checkbox('tipoEqualizacaoTecnicas[]', $tipoEqualizacaoTecnica_id, null,
                            [ 'id'=>'filter_tipoEqualizacaoTecnica_'.$tipoEqualizacaoTecnica_id ]) !!}
                            <label for="filter_tipoEqualizacaoTecnica_{{ $tipoEqualizacaoTecnica_id }}">
                                {{ $tipoEqualizacaoTecnica_nome }}
                            </label>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
        <div class="col-md-7">
            <label>
                Equalização Técnica
            </label>
            <button type="button" class="btn btn-flat btn-info btn-xs pull-right" onclick="addEQitem();"
                            title="Adicionar Equalização Técnica apenas para este Q.C.">
                <i class="fa fa-plus"></i>
                Adicionar
            </button>
            <ul id="equalizacaoTecnicaItens" class="list-group bloco_filtro">

            </ul>
        </div>
    </div>

</div>

@section('scripts')
    @parent
    <script type="text/javascript">
        function cadastraFornecedor() {
            swal('!TODO Abre Cadastro de Fornecedor');
        }
        function addEQitem() {
            swal('!TODO Abre Cadastro de qc_equalizacao_tecnica_extras');
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
            $('#fornecedor').select2({
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
            $('#fornecedor').on('select2:select', function (e) {
                addFornecedor()
            });

            $('.tiposEqT input').on('ifChecked', function(event) {
                var tipo_eqt_id = event.target.value;

                $.getJSON( "{{ url('tipos-equalizacoes-tecnicas/itens') }}/"+tipo_eqt_id)
                        .done(function(retorno) {
                            if(retorno){
                                $.each(retorno, function (index,obj) {
                                    item_eqt = '<li class="list-group-item eqt_'+tipo_eqt_id+'">' +
                                                    obj.nome +
                                            '<button type="button" class="btn btn-xs btn-flat btn-default pull-right"> ' +
                                            ' <i class="fa fa-info-circle" title="'+
                                                    obj.descricao
                                            +'" onclick="swal(\''+obj.nome+'\',\''+obj.descricao+'\',\'info\')" ' +
                                            ' aria-hidden="true"></i> ' +
                                            ' </button>' +
                                            '</li>';
                                    $('#equalizacaoTecnicaItens').append(item_eqt);
                                });
                            }

                        })
                        .fail(function() {
                            swal('Erro ao buscar Tipo de Equalização Técnica','', "error");
                        });
            });
            $('.tiposEqT input').on('ifUnchecked', function(event) {
                var tipo_eqt_id = event.target.value;
                $('.eqt_'+tipo_eqt_id).remove();
            });
        });
        var qtdFornecedores = {{ $qcFornecedorCount }};
        function addFornecedor(){
            qtdFornecedores++;
            if($('#fornecedor').val()){
                var nomeFornecedor = $('#fornecedor').select2('data');

                var qcFornecedorHTML = '<li class="list-group-item" id="qcFornecedor_id'+qtdFornecedores+'">' +
                        '<input type="hidden" name="qcFornecedoresMega[]" value="'+$('#fornecedor').val()+'">' +
                        nomeFornecedor[0].agn_st_nome +
                '<button type="button" title="Remover" class="btn btn-flat btn-danger btn-xs pull-right" ' +
                        ' onclick="removerFornecedor('+qtdFornecedores+',0)">' +
                        '<i class="fa fa-trash" aria-hidden="true"></i>' +
                        '</button>' +
                '</li>';

                $('#fornecedor').val(null).trigger("change");
                $('#fornecedoresSelecionados').append(qcFornecedorHTML);
                $('#fornecedor').select2('open');
            }
        }

        function removerFornecedor(qual, qcFornecedorId){
            if(qcFornecedorId){
                // Remover no banco
            }else{
                // Apenas remove o HTML
                $('#qcFornecedor_id'+qual).remove();
            }
        }
    </script>
@stop