@extends('layouts.front')

@section('content')
    <section class="content-header">
        <div class="modal-header">
            <h3>Incluir insumo</h3>
        </div>
    </section>
    <div class="content">
        <div class="form-group col-sm-6">
            {!! Form::label('insumo', 'Insumo:') !!}
            {!! Form::select('insumo', ['' => 'Escolha...'], null, ['class' => 'form-control','id'=>'insumo','required'=>'required']) !!}
        </div>

        <div class="col-md-12">
            <div class="col-md-12 thumbnail">
                <div class="col-md-12">
                    <div class="caption">
                        <div class="card-description">
                            <!-- Grupos de insumo Field -->
                            <div class="form-group col-sm-6" style="width:20%">
                                {!! Form::label('grupo_id', 'Grupos:') !!}
                                {!! Form::select('grupo_id', [''=>'-']+$grupos, null, ['class' => 'form-control', 'id'=>'grupo_id','onchange'=>'selectgrupo(this.value, \'subgrupo1_id\', \'grupos\');']) !!}
                            </div>

                            <!-- SubGrupos1 de insumo Field -->
                            <div class="form-group col-sm-6" style="width:20%">
                                {!! Form::label('subgrupo1_id', 'SubGrupo-1:') !!}
                                {!! Form::select('subgrupo1_id', [''=>'-'], null, ['class' => 'form-control', 'id'=>'subgrupo1_id', 'disabled'=>'disabled', 'onchange'=>'selectgrupo(this.value, \'subgrupo2_id\', \'grupos\');']) !!}
                            </div>

                            <!-- SubGrupos2 de insumo Field -->
                            <div class="form-group col-sm-6" style="width:20%">
                                {!! Form::label('subgrupo2_id', 'SubGrupo-2:') !!}
                                {!! Form::select('subgrupo2_id', [''=>'-'], null, ['class' => 'form-control', 'id'=>'subgrupo2_id', 'disabled'=>'disabled', 'onchange'=>'selectgrupo(this.value, \'subgrupo3_id\', \'grupos\');']) !!}
                            </div>

                            <!-- SubGrupos3 de insumo Field -->
                            <div class="form-group col-sm-6" style="width:20%">
                                {!! Form::label('subgrupo3_id', 'SubGrupo-3:') !!}
                                {!! Form::select('subgrupo3_id', [''=>'-'], null, ['class' => 'form-control', 'id'=>'subgrupo3_id', 'disabled'=>'disabled', 'onchange'=>'selectgrupo(this.value, \'servico_id\', \'servicos\');']) !!}
                            </div>

                            <!-- SubGrupos4 de insumo Field -->
                            <div class="form-group col-sm-6" style="width:20%">
                                {!! Form::label('servico_id', 'Serviço:') !!}
                                {!! Form::select('servico_id', [''=>'-'], null, ['class' => 'form-control', 'id'=>'servico_id', 'disabled'=>'disabled', 'onchange'=>'selectgrupo(this.value, null, \'servicos\')']) !!}
                            </div>
                            <input type="hidden" name="planejamento_id" value="{{$obra->id}}">

                            <div class="col-md-12" id="list-insumos"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(function () {
            $('#insumo').select2({
                allowClear: true,
                placeholder: "Escolha...",
                language: "pt-BR",

                ajax: {
                    url: "{{ route('admin.catalogo_contratos.busca_insumos') }}",
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
                templateResult: formatInsumoResult, // omitted for brevity, see the source of this page
                templateSelection: formatInsumoResultSelection // omitted for brevity, see the source of this page
            });
        });

        function formatInsumoResultSelection (obj) {
            if(obj.nome){
                return obj.nome;
            }
            return obj.text;
        }

        function formatInsumoResult (obj) {
            if (obj.loading) return obj.text;

            var markup_insumo =    "<div class='select2-result-obj clearfix'>" +
                    "   <div class='select2-result-obj__meta'>" +
                    "       <div class='select2-result-obj__title'>" + obj.nome + "</div>"+
                    "   </div>"+
                    "</div>";

            return markup_insumo;
        }

        function selectgrupo(id, change, tipo){
            var rota = "{{url('/admin/planejamentos/atividade/grupos')}}/";
            if(tipo == 'servicos'){
                rota = "{{url('/admin/planejamentos/atividade/servicos')}}/";
            }
            if(id){
                $('.box.box-primary').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
                $.ajax({
                    url: rota + id
                }).done(function(retorno) {
                    options = '<option value="">Selecione</option>';
                    $('#'+change).html(options);
                    $.each(retorno,function(index, value){
                        options += '<option value="'+index+'">'+value+'</option>';
                    });
                    $('#'+change).html(options);
                    $('.overlay').remove();
                    $('#'+change).attr('disabled',false);
                }).fail(function() {
                    $('.overlay').remove();
                });
            }
        }
    </script>
@stop