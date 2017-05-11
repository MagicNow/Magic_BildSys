@extends('layouts.front')

@section('content')
    <section class="content-header">
        <div class="modal-header">
            <h3>Incluir insumo</h3>
        </div>
    </section>
    <div class="content">
        {!! Form::open(['url' => URL::to('/compras/insumos/orcamento/incluir')]) !!}
            <div class="form-group col-sm-6">
                {!! Form::label('insumo_id', 'Insumo:') !!}
                {!! Form::select('insumo_id', ['' => 'Escolha...'], null, ['class' => 'form-control','id'=>'insumo','required'=>'required']) !!}
            </div>

            <div class="form-group col-sm-6">
                {!! Form::label('qtd_total', 'Quantidade:') !!}
                {!! Form::text('qtd_total', null, ['class' => 'form-control money','required'=>'required']) !!}
            </div>

            <div class="col-md-12">
                <div class="col-md-12 thumbnail">
                    <div class="col-md-12">
                        <div class="caption">
                            <div class="card-description">
                                <!-- Grupos de insumo Field -->
                                <div class="form-group col-sm-6" style="width:20%">
                                    {!! Form::label('grupo_id', 'Grupos:') !!}
                                    {!! Form::select('grupo_id', [''=>'-']+$grupos, null, ['class' => 'form-control', 'id'=>'grupo_id','onchange'=>'selectgrupo(this.value, \'subgrupo1_id\', \'grupos\');', 'required']) !!}
                                </div>

                                <!-- SubGrupos1 de insumo Field -->
                                <div class="form-group col-sm-6" style="width:20%">
                                    {!! Form::label('subgrupo1_id', 'SubGrupo-1:') !!}
                                    {!! Form::select('subgrupo1_id', [''=>'-'], null, ['class' => 'form-control', 'id'=>'subgrupo1_id', 'disabled'=>'disabled', 'onchange'=>'selectgrupo(this.value, \'subgrupo2_id\', \'grupos\');', 'required']) !!}
                                </div>

                                <!-- SubGrupos2 de insumo Field -->
                                <div class="form-group col-sm-6" style="width:20%">
                                    {!! Form::label('subgrupo2_id', 'SubGrupo-2:') !!}
                                    {!! Form::select('subgrupo2_id', [''=>'-'], null, ['class' => 'form-control', 'id'=>'subgrupo2_id', 'disabled'=>'disabled', 'onchange'=>'selectgrupo(this.value, \'subgrupo3_id\', \'grupos\');', 'required']) !!}
                                </div>

                                <!-- SubGrupos3 de insumo Field -->
                                <div class="form-group col-sm-6" style="width:20%">
                                    {!! Form::label('subgrupo3_id', 'SubGrupo-3:') !!}
                                    {!! Form::select('subgrupo3_id', [''=>'-'], null, ['class' => 'form-control', 'id'=>'subgrupo3_id', 'disabled'=>'disabled', 'onchange'=>'selectgrupo(this.value, \'servico_id\', \'servicos\');', 'required']) !!}
                                </div>

                                <!-- SubGrupos4 de insumo Field -->
                                <div class="form-group col-sm-6" style="width:20%">
                                    {!! Form::label('servico_id', 'Serviço:') !!}
                                    {!! Form::select('servico_id', [''=>'-'], null, ['class' => 'form-control', 'id'=>'servico_id', 'disabled'=>'disabled', 'onchange'=>'selectgrupo(this.value, null, \'servicos\');', 'required']) !!}
                                </div>
                                <input type="hidden" name="obra_id" value="{{$obra_id}}">

                                <div class="col-md-12" id="list-insumos"></div>
                            </div>
                        </div>
                    </div>
                    <a class="btn btn-info pull-right" data-toggle="modal" data-target="#cadastrarGrupo">
                        <i class="fa fa-save"></i> Cadastrar grupo
                    </a>
                </div>
            </div>

        <!-- Submit Field -->
        <div class="form-group col-sm-12">
            {!! Form::button( '<i class="fa fa-save"></i> '. 'Incluir', ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
            <a href="{{URL::to('/compras/obrasInsumos?obra_id='.$obra_id)}}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
        </div>

        {!! Form::close() !!}
    </div>

    <div class="modal fade" id="cadastrarGrupo" tabindex="-1" role="dialog" aria-labelledby="cadastrarGrupo">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="cadastrarGrupo">Cadastrar grupo</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="codigo" class="control-label">Código:</label>
                            <input type="text" class="form-control" id="codigo_grupo" name="codigo_grupo" maxlength="255">
                        </div>
                        <div class="form-group">
                            <label for="nome" class="control-label">Nome:</label>
                            <input type="text" class="form-control" id="nome_grupo" name="nome_grupo" maxlength="255">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                    <button type="button" class="btn btn-success" onclick="cadastrarGrupo();" data-dismiss="modal"><i class="fa fa-save"></i> Salvar</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(function () {
            @if(session('salvo'))
                swal({
                    title: "Insumo incluído!",
                    text: "Deseja continuar incluindo insumos no orçamento?",
                    type: "success",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: "Não",
                    confirmButtonText: "Sim"
                },
                function(isConfirm){
                    if (!isConfirm) {
                        window.location = "/compras/obrasInsumos?obra_id={{$obra_id}}";
                    }
                });
            @endif
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

        function cadastrarGrupo() {
            var codigo_grupo = $('#codigo_grupo').val();
            var nome_grupo = $('#nome_grupo').val();

            $.ajax({
                url: '/compras/insumos/orcamento/cadastrar/grupo',
                data: {
                    'codigo_grupo': codigo_grupo,
                    'nome_grupo': nome_grupo
                }
            }).done(function (json) {
                if(json.salvo){
                    if(!json.grupo.grupo_id){
                        $('#grupo_id').append('<option value="'+json.grupo.id+'">'+json.grupo.nome+'</option>');
                    }
                    swal("Grupo cadastrado com sucesso!", "", "success")
                }
            });
        }
    </script>
@stop