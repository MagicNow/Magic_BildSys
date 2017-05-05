<style type="text/css">
    .bloco_filtro {
        height: 100px;
        overflow-y: scroll;
        border: solid 2px #474747;
        background-color: #fff;
        font-size: 12px;
    }

    .list-group-item {
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
                    {!! Form::select('fornecedores', ['' => 'Escolha...'],
                    null,
                    [
                        'class' => 'form-control',
                        'id'=>'fornecedor'
                    ]) !!}
                </div>
                <div class="col-md-12">
                    <button type="button" title="Cadastrar Fornecedor Temporariamente" style="margin-top: 10px"
                            id="cadastrarFornecedorTemporariamente"
                            onclick="cadastraFornecedor()" class="btn btn-block btn-lg btn-flat btn-info">
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
                            <input type="hidden" name="qcFornecedores[{{ $qcFornecedor->id }}][id]"
                                   value="{{ $qcFornecedor->id }}">
                            <input type="hidden" name="qcFornecedores[{{ $qcFornecedor->id }}][fornecedor_id]"
                                   value="{{ $qcFornecedor->fornecedor_id }}">
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
                @if(count($quadroDeConcorrencia->tipoEqualizacaoTecnicas()->count()))
                    @foreach($quadroDeConcorrencia->tipoEqualizacaoTecnicas as $tipoEqualizacaoTecnica)
                        @foreach($tipoEqualizacaoTecnica->itens as $EQTitem)
                            <li class="list-group-item eqt_{{ $tipoEqualizacaoTecnica->id }}">
                                {!!  $EQTitem->obrigatorio ? '<i class="fa fa-exclamation text-danger" title="Obrigatório" aria-hidden="true"></i>' : '' !!}
                                {{ $EQTitem->nome }}
                                <button type="button" class="btn btn-xs btn-flat btn-default pull-right">
                                    <i class="fa fa-info-circle" title="{{ $EQTitem->descricao }}"
                                       onclick="swal({{ $EQTitem->nome }}','{{ $EQTitem->descricao }}','info')"
                                       aria-hidden="true"></i>
                                </button>
                            </li>
                        @endforeach
                    @endforeach
                @endif
                @if($quadroDeConcorrencia->equalizacaoTecnicaExtras()->count())
                    @foreach( $quadroDeConcorrencia->equalizacaoTecnicaExtras as $qcEqtExtra)
                    <li class="list-group-item" id="eqt_custom_{{ $qcEqtExtra->id }}">
                        <i class="fa fa-pencil-square-o text-warning" title="Apenas para esta QC" aria-hidden="true"></i>
                        {!!  $qcEqtExtra->obrigatorio ? '<i class="fa fa-exclamation text-danger" title="Obrigatório" aria-hidden="true"></i>' : '' !!}
                        {{ $qcEqtExtra->nome }}
                        <div class="btn-group pull-right">
                            <button type="button" class="btn btn-xs btn-flat btn-default">
                                 <i class="fa fa-info-circle" title="{{ $qcEqtExtra->descricao }}"
                                    onclick="swal('{{ $qcEqtExtra->nome }}','{{ $qcEqtExtra->descricao }}','info')"
                                 aria-hidden="true"></i>
                            </button>
                            <button type="button" class="btn btn-xs btn-flat btn-warning">
                                 <i class="fa fa-pencil" title="Editar" onclick="editarEQT({{ $qcEqtExtra->id }})"
                                 aria-hidden="true"></i>
                            </button>
                            <button type="button" class="btn btn-xs btn-flat btn-danger">
                                   <i class="fa fa-trash" title="Remover" onclick="removerEQT({{ $qcEqtExtra->id }})"
                                   aria-hidden="true"></i>
                            </button>
                        </div>
                    </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>

</div>

<!-- Texto Field -->
<div class="form-group col-sm-6">
    {!! Form::label('obrigacoes_fornecedor', 'Obrigações Fornecedor:') !!}
    {!! Form::textarea('obrigacoes_fornecedor', null, ['class' => 'form-control', 'rows'=>3]) !!}
</div>
<!-- Texto Field -->
<div class="form-group col-sm-6">
    {!! Form::label('obrigacoes_bild', 'Obrigações Fornecedor:') !!}
    {!! Form::textarea('obrigacoes_bild', null, ['class' => 'form-control', 'rows'=>3]) !!}
</div>

<!-- Modal -->
<div class="modal fade" id="modalCadastroEQT" tabindex="-1" role="dialog" aria-labelledby="modalCadastroEQTLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalCadastroEQTLabel"></h4>
            </div>
            <div class="modal-body">
                <input type="hidden" value="" id="item_eqt_id">
                <div class="row">
                    <div class="form-group col-sm-9">
                        <label for="itens_nome">Nome:</label>
                        <input class="form-control" type="text" id="item_eqt_nome" required="required"/>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="item_eqt_obrigatorio">Obrigatório: </label>
                        <input type="checkbox" value="1" id="item_eqt_obrigatorio"><br>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-12">
                        <label for="item_eqt_descricao">Descrição:</label>
                        <textarea class="form-control" type="text" id="item_eqt_descricao"/></textarea>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-flat btn-default btn-lg pull-left" data-dismiss="modal">Cancelar
                </button>
                <button type="button" class="btn btn-flat btn-success btn-lg"
                        id="btn_add_eq" onclick="addEQitemSave();">
                    Adicionar
                </button>
                <button type="button" class="btn btn-flat btn-success btn-lg" style="display: none"
                        id="btn_edit_eq" onclick="editEQitemSave();">
                    Alterar
                </button>
            </div>
        </div>
    </div>
</div>


@section('scripts')
    @parent
    <script type="text/javascript">
        function addEQitemSave(){
            var item_eqt_nome = $('#item_eqt_nome').val();
            var item_eqt_obrigatorio = $('#item_eqt_obrigatorio').is(':checked');
            var item_eqt_descricao = $('#item_eqt_descricao').val();

            $.ajax({
                type: 'POST',
                url: "{{ url('quadro-de-concorrencia/'.$quadroDeConcorrencia->id.'/adiciona-eqt') }}",
                data: {
                    nome: item_eqt_nome,
                    obrigatorio: (item_eqt_obrigatorio?1:0),
                    descricao: item_eqt_descricao,
                    _token: $('meta[name="csrf-token"]').attr('content')
                }
            }).success(function (obj) {
                item_eqt = '<li class="list-group-item" id="eqt_custom_' + obj.id + '"> ' +
                        '<i class="fa fa-pencil-square-o text-warning" title="Apenas para esta QC" aria-hidden="true"></i> ' +
                        (obj.obrigatorio ? '<i class="fa fa-exclamation text-danger" title="Obrigatório" aria-hidden="true"></i>' : '' ) +
                        obj.nome +
                        '<div class="btn-group pull-right">' +
                        '<button type="button" class="btn btn-xs btn-flat btn-default"> ' +
                        ' <i class="fa fa-info-circle" title="' +
                        obj.descricao +
                        '" onclick="swal(\'' + obj.nome + '\',\'' + obj.descricao + '\',\'info\')" ' +
                        ' aria-hidden="true"></i> ' +
                        ' </button>' +
                        '<button type="button" class="btn btn-xs btn-flat btn-warning"> ' +
                        ' <i class="fa fa-pencil" title="Editar" onclick="editarEQT(' + obj.id + ')" ' +
                        ' aria-hidden="true"></i> ' +
                        ' </button>' +
                        '<button type="button" class="btn btn-xs btn-flat btn-danger"> ' +
                        '   <i class="fa fa-trash" title="Remover" onclick="removerEQT(' + obj.id + ')" ' +
                        '   aria-hidden="true"></i> ' +
                        '</button>' +
                        '</div>' +
                        '</li>';
                $('#equalizacaoTecnicaItens').append(item_eqt);
                $('#modalCadastroEQT').modal('hide');
                // Limpa os dados
                $('#item_eqt_nome').val('')
                $('#item_eqt_obrigatorio').attr('checked', false);
                $('#item_eqt_descricao').val('');
            }).fail(function (jqXHR, textStatus, errorThrown) {
                // Optionally alert the user of an error here...
                var textResponse = jqXHR.responseText;
                var alertText = "Confira as mensagens abaixo:\n\n";
                var jsonResponse = jQuery.parseJSON(textResponse);

                $.each(jsonResponse, function (n, elem) {
                    alertText = alertText + elem + "\n";
                });
                swal({title: "", text: alertText, type: 'error'});
            });

        }

        function editEQitemSave(){
            var item_eqt_nome = $('#item_eqt_nome').val();
            var item_eqt_obrigatorio = $('#item_eqt_obrigatorio').is(':checked');
            var item_eqt_descricao = $('#item_eqt_descricao').val();
            var item_eqt_id = $('#item_eqt_id').val();

            $.ajax({
                type: 'POST',
                url: "{{ url('quadro-de-concorrencia/'.$quadroDeConcorrencia->id.'/editar-eqt') }}/"+item_eqt_id,
                data: {
                    nome: item_eqt_nome,
                    obrigatorio: (item_eqt_obrigatorio?1:0),
                    descricao: item_eqt_descricao,
                    _token: $('meta[name="csrf-token"]').attr('content')
                }
            }).success(function (obj) {
                item_eqt =
                        '<i class="fa fa-pencil-square-o text-warning" title="Apenas para esta QC" aria-hidden="true"></i> ' +
                        ( parseInt(obj.obrigatorio) ? '<i class="fa fa-exclamation text-danger" title="Obrigatório" aria-hidden="true"></i>' : '' ) +
                        obj.nome +
                        '<div class="btn-group pull-right">' +
                        '<button type="button" class="btn btn-xs btn-flat btn-default"> ' +
                        ' <i class="fa fa-info-circle" title="' +
                        obj.descricao +
                        '" onclick="swal(\'' + obj.nome + '\',\'' + obj.descricao + '\',\'info\')" ' +
                        ' aria-hidden="true"></i> ' +
                        ' </button>' +
                        '<button type="button" class="btn btn-xs btn-flat btn-warning"> ' +
                        ' <i class="fa fa-pencil" title="Editar" onclick="editarEQT(' + obj.id + ')" ' +
                        ' aria-hidden="true"></i> ' +
                        ' </button>' +
                        '<button type="button" class="btn btn-xs btn-flat btn-danger"> ' +
                        '   <i class="fa fa-trash" title="Remover" onclick="removerEQT(' + obj.id + ')" ' +
                        '   aria-hidden="true"></i> ' +
                        '</button>' +
                        '</div>';
                $('#eqt_custom_'+obj.id).html(item_eqt);
                $('#modalCadastroEQT').modal('hide');
                // Limpa os dados
                $('#item_eqt_nome').val('')
                $('#item_eqt_obrigatorio').attr('checked', false);
                $('#item_eqt_obrigatorio').iCheck('update');
                $('#item_eqt_descricao').val('');
                $('#item_eqt_id').val('');
            }).fail(function (jqXHR, textStatus, errorThrown) {
                // Optionally alert the user of an error here...
                var textResponse = jqXHR.responseText;
                var alertText = "Confira as mensagens abaixo:\n\n";
                var jsonResponse = jQuery.parseJSON(textResponse);

                $.each(jsonResponse, function (n, elem) {
                    alertText = alertText + elem + "\n";
                });
                swal({title: "", text: alertText, type: 'error'});
            });

        }

        function removerEQT(qual) {
            swal({
                title:'Deseja remover esta Equalização Técnica?',
                text: 'Após a remoção não será possível mais recuperar o registro.',
                type:'warning',
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Sim, tenho certeza!",
                cancelButtonText: "Não",
                closeOnConfirm: false
            }, function(){
                $.ajax("{{ url('quadro-de-concorrencia/'.$quadroDeConcorrencia->id.'/remover-eqt') }}/"+qual)
                .success(function(retorno){
                    $('#eqt_custom_'+qual).remove();
                    swal('Removido','','success');
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    swal('Erro',jqXHR.responseText, 'error');
                });
            });
        }
        function editarEQT(qual) {
            $.ajax("{{ url('quadro-de-concorrencia/'.$quadroDeConcorrencia->id.'/exibir-eqt') }}/"+qual)
                    .success(function(retorno){
                        console.log(parseInt(retorno.obrigatorio),(parseInt(retorno.obrigatorio)?true:false), $('#item_eqt_obrigatorio').is(':checked'));
                        $('#item_eqt_nome').val(retorno.nome);
                        if(parseInt(retorno.obrigatorio)){
                            $('#item_eqt_obrigatorio').attr('checked', true );
                            $('#item_eqt_obrigatorio').iCheck('check');
                            $('#item_eqt_obrigatorio').iCheck('update');
                            console.log('Checar');
                        }else{
                            $('#item_eqt_obrigatorio').attr('checked', false );
                            $('#item_eqt_obrigatorio').iCheck('uncheck');
                            $('#item_eqt_obrigatorio').iCheck('update');
                            console.log('Deschecar');
                        }

                        console.log(parseInt(retorno.obrigatorio),(parseInt(retorno.obrigatorio)?true:false), $('#item_eqt_obrigatorio').is(':checked'));

                        $('#item_eqt_descricao').val(retorno.descricao);
                        $('#item_eqt_id').val(retorno.id);

                        $('#btn_add_eq').hide();
                        $('#btn_edit_eq').show();
                        $('#modalCadastroEQTLabel').html('Editar Equalização técnica para este Q.C.');
                        $('#modalCadastroEQT').modal('show');

                    }).fail(function (jqXHR, textStatus, errorThrown) {
                swal('Erro',jqXHR.responseText, 'error');
            });
        }

        function cadastraFornecedor() {
            funcaoPosCreate = "preencheFornecedor();";
            $.colorbox({
                href: "{{ url('/admin/fornecedores/create?modal=1') }}",
                iframe: true,
                width: '90%',
                height: '90%',
            });
        }

        function preencheFornecedor() {
            qtdFornecedores++;
            var nomeFornecedor = novoObjeto.nome;
            var qcFornecedorHTML = '<li class="list-group-item" id="qcFornecedor_id' + qtdFornecedores + '">' +
                    '<input type="hidden" name="qcFornecedores[][fornecedor_id]" value="' + novoObjeto.id + '">' +
                    nomeFornecedor +
                    '<button type="button" title="Remover" class="btn btn-flat btn-danger btn-xs pull-right" ' +
                    ' onclick="removerFornecedor(' + qtdFornecedores + ',0)">' +
                    '<i class="fa fa-trash" aria-hidden="true"></i>' +
                    '</button>' +
                    '</li>';

            $('#fornecedor').val(null).trigger("change");
            $('#fornecedoresSelecionados').append(qcFornecedorHTML);
        }


        function addEQitem() {
            $('#btn_add_eq').show();
            $('#btn_edit_eq').hide();
            $('#modalCadastroEQTLabel').html('Cadastrar Equalização técnica para este Q.C.');
            $('#modalCadastroEQT').modal('show');
        }
        function formatResult(obj) {
            if (obj.loading) return obj.text;

            var markup = "<div class='select2-result-obj clearfix'>" +
                    "   <div class='select2-result-obj__meta'>" +
                    "       <div class='select2-result-obj__title'>" + obj.agn_st_nome + "</div>" +
                    "   </div>" +
                    "</div>";

            return markup;
        }

        function formatResultSelection(obj) {
            if (obj.agn_st_nome) {
                return obj.agn_st_nome;
            }
            return obj.text;
        }

        $(function () {
            $('#fornecedor').select2({
                allowClear: true,
                placeholder: "-",
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
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 1,
                templateResult: formatResult, // omitted for brevity, see the source of this page
                templateSelection: formatResultSelection // omitted for brevity, see the source of this page

            });
            $('#fornecedor').on('select2:select', function (e) {
                addFornecedor()
            });

            $('.tiposEqT input').on('ifChecked', function (event) {
                var tipo_eqt_id = event.target.value;

                $.getJSON("{{ url('tipos-equalizacoes-tecnicas/itens') }}/" + tipo_eqt_id)
                        .done(function (retorno) {
                            if (retorno) {
                                $.each(retorno, function (index, obj) {
                                    item_eqt = '<li class="list-group-item eqt_' + tipo_eqt_id + '">' +
                                            (obj.obrigatorio ? '<i class="fa fa-exclamation text-danger" title="Obrigatório" aria-hidden="true"></i>' : '' ) +
                                            obj.nome +
                                            '<button type="button" class="btn btn-xs btn-flat btn-default pull-right"> ' +
                                            ' <i class="fa fa-info-circle" title="' +
                                            obj.descricao
                                            + '" onclick="swal(\'' + obj.nome + '\',\'' + obj.descricao + '\',\'info\')" ' +
                                            ' aria-hidden="true"></i> ' +
                                            ' </button>' +
                                            '</li>';
                                    $('#equalizacaoTecnicaItens').append(item_eqt);
                                });
                            }

                        })
                        .fail(function () {
                            swal('Erro ao buscar Tipo de Equalização Técnica', '', "error");
                        });
            });
            $('.tiposEqT input').on('ifUnchecked', function (event) {
                var tipo_eqt_id = event.target.value;
                $('.eqt_' + tipo_eqt_id).remove();
            });
        });
        var qtdFornecedores = {{ $qcFornecedorCount }};
        function addFornecedor() {
            qtdFornecedores++;
            if ($('#fornecedor').val()) {
                var nomeFornecedor = $('#fornecedor').select2('data');

                var qcFornecedorHTML = '<li class="list-group-item" id="qcFornecedor_id' + qtdFornecedores + '">' +
                        '<input type="hidden" name="qcFornecedoresMega[]" value="' + $('#fornecedor').val() + '">' +
                        nomeFornecedor[0].agn_st_nome +
                        '<button type="button" title="Remover" class="btn btn-flat btn-danger btn-xs pull-right" ' +
                        ' onclick="removerFornecedor(' + qtdFornecedores + ',0)">' +
                        '<i class="fa fa-trash" aria-hidden="true"></i>' +
                        '</button>' +
                        '</li>';

                $('#fornecedor').val(null).trigger("change");
                $('#fornecedoresSelecionados').append(qcFornecedorHTML);
                $('#fornecedor').select2('open');
            }
        }

        function removerFornecedor(qual, qcFornecedorId) {
            if (qcFornecedorId) {
                // Remover no banco
            } else {
                // Apenas remove o HTML
                $('#qcFornecedor_id' + qual).remove();
            }
        }
    </script>
@stop