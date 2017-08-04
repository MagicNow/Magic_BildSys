<!-- nome Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nome', 'Nome:') !!}
    {!! Form::text('nome', null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('obraUsers', 'Usuários nesta obra:') !!}
    {!! Form::select('obraUsers[]', $relacionados , (!isset($obra )? null : $obraUsers), ['class' => 'form-control', 'id'=>"obraUsers", 'multiple'=>"multiple"]) !!}
</div>

<!-- logo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('logo', 'Logo:') !!}
    @if(@isset($obra))
        @if($obra->logo)
            <a href="{{$obra->logo}}" class="colorbox">Ver logo</a>
        @endif
    @endif
    {!! Form::file('logo', null, ['class' => 'form-control']) !!}
</div>

<!-- Cidade Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('cidade_id', 'Cidade:') !!}
    {!! Form::select('cidade_id', $cidades, null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('regional_id', 'Regional:') !!}
    {!! Form::select('regional_id', $regionais, null, ['class' => 'form-control select2']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('padrao_empreendimento_id', 'Padrão de empreendimento:') !!}
    {!! Form::select('padrao_empreendimento_id', $padrao_empreendimentos, null, ['class' => 'form-control select2']) !!}
</div>

<!-- area_terreno Field -->
<div class="form-group col-sm-6">
    {!! Form::label('area_terreno', 'Área do terreno:') !!}
    {!! Form::text('area_terreno', null, ['class' => 'form-control money']) !!}
</div>

<!-- area_privativa Field -->
<div class="form-group col-sm-6">
    {!! Form::label('area_privativa', 'Área privativa:') !!}
    {!! Form::text('area_privativa', null, ['class' => 'form-control money']) !!}
</div>

<!-- area_construida Field -->
<div class="form-group col-sm-6">
    {!! Form::label('area_construida', 'Área construída:') !!}
    {!! Form::text('area_construida', null, ['class' => 'form-control money']) !!}
</div>

<!-- eficiencia_projeto Field -->
<div class="form-group col-sm-6">
    {!! Form::label('eficiencia_projeto', 'Eficiencia do projeto:') !!}
    {!! Form::text('eficiencia_projeto', null, ['class' => 'form-control money']) !!}
</div>

<!-- num_apartamentos Field -->
<div class="form-group col-sm-6">
    {!! Form::label('num_apartamentos', 'Número de apartamentos:') !!}
    {!! Form::number('num_apartamentos', null, ['class' => 'form-control']) !!}
</div>

<!-- num_torres Field -->
<div class="form-group col-sm-6">
    {!! Form::label('num_torres', 'Número de torres:') !!}
    {!! Form::number('num_torres', null, ['class' => 'form-control']) !!}
</div>

<!-- num_pavimento_tipo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('num_pavimento_tipo', 'Número pavimento tipo:') !!}
    {!! Form::number('num_pavimento_tipo', null, ['class' => 'form-control']) !!}
</div>

<!-- data_inicio Field -->
<div class="form-group col-sm-6">
    {!! Form::label('data_inicio', 'Data de início:') !!}
    {!! Form::date('data_inicio', @isset($obra) ? $obra->data_inicio ? $obra->data_inicio->format('Y-m-d') : null : null, ['class' => 'form-control']) !!}
</div>

<!-- data_cliente Field -->
<div class="form-group col-sm-6">
    {!! Form::label('data_cliente', 'Data cliente:') !!}
    {!! Form::date('data_cliente', @isset($obra) ? $obra->data_cliente ? $obra->data_cliente->format('Y-m-d') : null : null, ['class' => 'form-control']) !!}
</div>

<!-- indice_bild_pre Field -->
<div class="form-group col-sm-6">
    {!! Form::label('indice_bild_pre', 'Índice BILD - Pré:') !!}
    {!! Form::text('indice_bild_pre', null, ['class' => 'form-control money_3']) !!}
</div>

<!-- indice_bild_oi Field -->
<div class="form-group col-sm-6">
    {!! Form::label('indice_bild_oi', 'Índice BILD - OI:') !!}
    {!! Form::text('indice_bild_oi', null, ['class' => 'form-control money_3']) !!}
</div>

<!-- razao_social Field -->
<div class="form-group col-sm-6">
    {!! Form::label('razao_social', 'Razão social:') !!}
    {!! Form::text('razao_social', null, ['class' => 'form-control']) !!}
</div>

<!-- cnpj Field -->
<div class="form-group col-sm-6">
    {!! Form::label('cnpj', 'CNPJ:') !!}
    {!! Form::text('cnpj', null, ['class' => 'form-control cnpj']) !!}
</div>

<!-- inscricao_estadual Field -->
<div class="form-group col-sm-6">
    {!! Form::label('inscricao_estadual', 'Inscrição estadual:') !!}
    {!! Form::text('inscricao_estadual', null, ['class' => 'form-control']) !!}
</div>

<!-- endereco_faturamento Field -->
<div class="form-group col-sm-6">
    {!! Form::label('endereco_faturamento', 'Endereço de faturamento:') !!}
    {!! Form::text('endereco_faturamento', null, ['class' => 'form-control']) !!}
</div>

<!-- endereco_obra Field -->
<div class="form-group col-sm-6">
    {!! Form::label('endereco_obra', 'Endereço da obra:') !!}
    {!! Form::text('endereco_obra', null, ['class' => 'form-control']) !!}
</div>

<!-- entrega_nota_fisca_e_boleto Field -->
<div class="form-group col-sm-6">
    {!! Form::label('entrega_nota_fisca_e_boleto', 'Entrega de nota fiscal e boleto:') !!}
    {!! Form::text('entrega_nota_fisca_e_boleto', null, ['class' => 'form-control']) !!}
</div>

<!-- adm_obra_nome Field -->
<div class="form-group col-sm-6">
    {!! Form::label('adm_obra_nome', 'Administrativo de obra - Nome:') !!}
    {!! Form::text('adm_obra_nome', null, ['class' => 'form-control']) !!}
</div>

<!-- adm_obra_email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('adm_obra_email', 'Administrativo de obra - Email:') !!}
    {!! Form::text('adm_obra_email', null, ['class' => 'form-control']) !!}
</div>

<!-- adm_obra_telefone Field -->
<div class="form-group col-sm-6">
    {!! Form::label('adm_obra_telefone', 'Administrativo de obra - Telefone:') !!}
    {!! Form::text('adm_obra_telefone', null, ['class' => 'form-control telefone']) !!}
</div>

<!-- eng_obra_nome Field -->
<div class="form-group col-sm-6">
    {!! Form::label('eng_obra_nome', 'Engenheiro obra - Nome:') !!}
    {!! Form::text('eng_obra_nome', null, ['class' => 'form-control']) !!}
</div>

<!-- eng_obra_email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('eng_obra_email', 'Engenheiro obra - Email:') !!}
    {!! Form::text('eng_obra_email', null, ['class' => 'form-control']) !!}
</div>

<!-- eng_obra_telefone Field -->
<div class="form-group col-sm-6">
    {!! Form::label('eng_obra_telefone', 'Engenheiro obra - Telefone:') !!}
    {!! Form::text('eng_obra_telefone', null, ['class' => 'form-control telefone']) !!}
</div>

<!-- horario_entrega_na_obra Field -->
<div class="form-group col-sm-6">
    {!! Form::label('horario_entrega_na_obra', 'Horário de entrega na obra:') !!}
    {!! Form::text('horario_entrega_na_obra', null, ['class' => 'form-control']) !!}
</div>

<!-- referencias_bancarias Field -->
<div class="form-group col-sm-12">
    {!! Form::label('referencias_bancarias', 'Referências bancárias:') !!}
    {!! Form::text('referencias_bancarias', null, ['class' => 'form-control']) !!}
</div>

<!-- referencias_comerciais Field -->
<div class="form-group col-sm-12">
    {!! Form::label('referencias_comerciais', 'Referências comerciais:') !!}
    {!! Form::textarea('referencias_comerciais', null, ['class' => 'form-control', 'rows' => '5']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success btn-lg pull-right btn-flat', 'type'=>'submit']) !!}
    <a href="{!! route('admin.obras.index') !!}" class="btn btn-danger btn-lg btn-flat"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>

@section('scripts')
    <script type="text/javascript">
        function formatResult (obj) {
            if (obj.loading) return obj.text;

            var markup =    "<div class='select2-result-obj clearfix'>" +
                "   <div class='select2-result-obj__meta'>" +
                "       <div class='select2-result-obj__title'>" + obj.name + "</div>"+
                "   </div>"+
                "</div>";

            return markup;
        }

        function formatResultSelection (obj) {
            if(obj.name){
                return obj.name;
            }
            return obj.text;
        }

        function formatResultCidade (obj) {
            if (obj.loading) return obj.text;

            var markup =    "<div class='select2-result-obj clearfix'>" +
                    "   <div class='select2-result-obj__meta'>" +
                    "       <div class='select2-result-obj__title'>" + obj.nome +' - ' + obj.uf  +
                    "</div>"+
                    "   </div>"+
                    "</div>";

            return markup;
        }

        function formatResultSelectionCidade (obj) {
            if(obj.nome){
                return obj.nome + ' - '+ obj.uf;
            }
            return obj.text;
        }

        $(function(){
            $('#obraUsers').select2({
                language: "pt-BR",
                theme:'bootstrap',
                ajax: {
                    url: "/admin/users/busca",
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

            $('#cidade_id').select2({
                language: "pt-BR",
                theme: "bootstrap",
                placeholder: 'Digite a cidade...',
                allowClear: true,
                ajax: {
                    url: "/busca-cidade",
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
                minimumInputLength: 2,
                templateResult: formatResultCidade, // omitted for brevity, see the source of this page
                templateSelection: formatResultSelectionCidade // omitted for brevity, see the source of this page
            });
        });
    </script>
@stop
