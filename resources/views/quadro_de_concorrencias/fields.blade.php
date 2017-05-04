
<!-- Fornecedores Field -->
<div class="form-group col-sm-4">
    <div class="row">
        {!! Form::label('qcFornecedores', 'Fornecedores:') !!}
        <div class="col-md-5">
            {!! Form::select('fornecedores', ['' => 'Escolha...']+$fornecedoresRelacionados,
            isset($quadroDeConcorrencia) ? $qcFornecedores_ids : null,
            [
                'class' => 'form-control',
                'id'=>'fornecedor',
                'required'=>'required'
            ]) !!}
            {!! Form::button('cadastrar temporariamente','cadastrar',[
            'onclick'=>'cadastraFornecedor()',
            'type'=>'button',
            'class'=> 'btn btn-lg btn-flat btn-info',
            'id'=>'cadastrarFornecedorTemporariamente']) !!}
        </div>
        <div class="col-md-1">
            <button type="button" onclick="addFornecedor()" title="Adicionar">
                <i class="fa fa-angle-double-right" aria-hidden="true"></i>
            </button>
        </div>

        <div class="col-md-6 well-sm">
            <ul class="list-group"  id="fornecedoresSelecionados">
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
                            <button type="button" class="btn btn-flat btn-danger btn-xs" title="remover"
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

<!-- Fornecedores Field -->
<div class="form-group col-sm-4">
    {!! Form::label('tiposEqualizacaoTecnicas', 'Tipo Equalização Técnica:') !!}
    {!! Form::select('tiposEqualizacaoTecnicas[]', ['' => 'Escolha...']+
    $tiposEqualizacaoTecnicasRelacionadas ,(!isset($quadroDeConcorrencia)? null: $tiposEqualizacaoTecnicasRelacionadas_ids),
    [
        'class' => 'form-control',
        'id'=>'tiposEqualizacaoTecnicas',
        'required'=>'required',
        'multiple'=>'multiple'
    ]) !!}
    {!! Form::button('cadastrar temporariamente','cadastrar',[
    'onclick'=>'cadastraFornecedor()',
    'type'=>'button',
    'class'=> 'btn btn-lg btn-flat btn-info',
    'id'=>'cadastrarFornecedorTemporariamente']) !!}
</div>

@section('scripts')
    @parent
    <script type="text/javascript">
        function cadastraFornecedor() {
            swal('!TODO Abre Cadastro de Fornecedor');
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
        });
        var qtdFornecedores = {{ $qcFornecedorCount }};
        function addFornecedor(){
            qtdFornecedores++;
            if($('#fornecedor').val()){
                var nomeFornecedor = $('#fornecedor').select2('data');

                var qcFornecedorHTML = '<li class="list-group-item" id="qcFornecedor_id'+qtdFornecedores+'">' +
                        '<input type="hidden" name="qcFornecedoresMega[]" value="'+$('#fornecedor').val()+'">' +
                        nomeFornecedor.text +
                '<button type="button" title="Remover" class="btn btn-flat btn-danger btn-xs" ' +
                        ' onclick="removerFornecedor('+qtdFornecedores+',0)">' +
                        '<i class="fa fa-trash" aria-hidden="true"></i>' +
                        '</button>' +
                '</li>';
                $('#fornecedor').select2("val", "");
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