<!-- nome Field -->
<div class="form-group col-sm-12">
    {!! Form::label('nome', 'Nome:') !!}
    {!! Form::text('nome', null, ['class' => 'form-control', 'required']) !!}
</div>
<div class="form-group col-sm-12">
    {!! Form::label('descricao', 'Descrição:') !!}
    {!! Form::textarea('descricao', null, ['class' => 'form-control']) !!}
</div>
{{--@php $grupo_obra = \App\Models\Grupo::where('codigo', '01')->whereNull('grupo_id')->first(); @endphp--}}
{{--@if(isset($mascaraPadrao))--}}
{{--<div class="row">--}}
    {{--<!-- SubGrupos1 de insumo Field -->--}}
    {{--<div class="form-group col-sm-12">--}}
        {{--<div class="form-group col-sm-9">--}}
            {{--{!! Form::label('subgrupo1_id', 'SubGrupo-1:') !!}--}}
            {{--{!! Form::select('subgrupo1_id', [''=>'-'], null, ['class' => 'form-control select2', 'id'=>'subgrupo1_id', 'onchange'=>'selectgrupo(this.value, \'subgrupo2_id\', \'grupos\');', 'required']) !!}--}}
        {{--</div>--}}
        {{--<div class="form-group col-sm-3">--}}
            {{--<a class="btn btn-info pull-right flat" data-toggle="modal" data-target="#cadastrarGrupo"--}}
               {{--style="margin-top: 20px; display: none;" id="cadastrar_subgrupo1_id"--}}
               {{--onclick="atribuirGrupoId('grupo_id', 'subgrupo1_id');">--}}
                {{--<i class="fa fa-save"></i> Cadastrar SubGrupo-1--}}
            {{--</a>--}}
        {{--</div>--}}
    {{--</div>--}}

    {{--<!-- SubGrupos2 de insumo Field -->--}}
    {{--<div class="form-group col-sm-12">--}}
        {{--<div class="form-group col-sm-9">--}}
            {{--{!! Form::label('subgrupo2_id', 'SubGrupo-2:') !!}--}}
            {{--{!! Form::select('subgrupo2_id', [''=>'-'], null, ['class' => 'form-control select2', 'id'=>'subgrupo2_id', 'disabled'=>'disabled', 'onchange'=>'selectgrupo(this.value, \'subgrupo3_id\', \'grupos\');', 'required']) !!}--}}
        {{--</div>--}}
        {{--<div class="form-group col-sm-3">--}}
            {{--<a class="btn btn-info pull-right flat" data-toggle="modal" data-target="#cadastrarGrupo"--}}
               {{--style="margin-top: 20px; display: none;" id="cadastrar_subgrupo2_id"--}}
               {{--onclick="atribuirGrupoId('subgrupo1_id', 'subgrupo2_id');">--}}
                {{--<i class="fa fa-save"></i> Cadastrar SubGrupo-2--}}
            {{--</a>--}}
        {{--</div>--}}
    {{--</div>--}}

    {{--<!-- SubGrupos3 de insumo Field -->--}}
    {{--<div class="form-group col-sm-12">--}}
        {{--<div class="form-group col-sm-9">--}}
            {{--{!! Form::label('subgrupo3_id', 'SubGrupo-3:') !!}--}}
            {{--{!! Form::select('subgrupo3_id', [''=>'-'], null, ['class' => 'form-control select2', 'id'=>'subgrupo3_id', 'disabled'=>'disabled', 'onchange'=>'selectgrupo(this.value, \'servico_id\', \'servicos\');', 'required']) !!}--}}
        {{--</div>--}}
        {{--<div class="form-group col-sm-3">--}}
            {{--<a class="btn btn-info pull-right flat" data-toggle="modal" data-target="#cadastrarGrupo"--}}
               {{--style="margin-top: 20px; display: none;" id="cadastrar_subgrupo3_id"--}}
               {{--onclick="atribuirGrupoId('subgrupo2_id', 'subgrupo3_id');">--}}
                {{--<i class="fa fa-save"></i> Cadastrar SubGrupo-3--}}
            {{--</a>--}}
        {{--</div>--}}
    {{--</div>--}}

    {{--<!-- SubGrupos4 de insumo Field -->--}}
    {{--<div class="form-group col-sm-12">--}}
        {{--<div class="form-group col-sm-9">--}}
            {{--{!! Form::label('servico_id', 'Serviço:') !!}--}}
            {{--{!! Form::select('servico_id', [''=>'-'], null, ['class' => 'form-control select2', 'id'=>'servico_id', 'disabled'=>'disabled', 'onchange'=>'selectgrupo(this.value, null, \'servicos\');', 'required']) !!}--}}
        {{--</div>--}}
        {{--<div class="form-group col-sm-3">--}}
            {{--<a class="btn btn-info pull-right flat" data-toggle="modal" data-target="#cadastrarGrupo"--}}
               {{--style="margin-top: 20px; display: none;" id="cadastrar_servico_id"--}}
               {{--onclick="atribuirGrupoId('subgrupo3_id', 'servico_id');">--}}
                {{--<i class="fa fa-save"></i> Cadastrar Serviço--}}
            {{--</a>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}
{{--@endif--}}
{{--<div class="modal fade" id="cadastrarGrupo" tabindex="-1" role="dialog" aria-labelledby="cadastrarGrupo">--}}
    {{--<div class="modal-dialog" role="document">--}}
        {{--<div class="modal-content">--}}
            {{--<div class="modal-header">--}}
                {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
                {{--<h4 class="modal-title" id="cadastrarGrupo">Cadastrar grupo</h4>--}}
            {{--</div>--}}
            {{--<div class="modal-body">--}}
                {{--<form>--}}
                    {{--<div class="form-group">--}}
                        {{--<label for="codigo" class="control-label">Código:</label>--}}
                        {{--<input type="text" class="form-control" id="codigo_grupo" name="codigo_grupo" maxlength="255">--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--<label for="nome" class="control-label">Nome:</label>--}}
                        {{--<input type="text" class="form-control" id="nome_grupo" name="nome_grupo" maxlength="255">--}}
                    {{--</div>--}}
                    {{--<input type="hidden" name="subgrupo_de" id="subgrupo_de">--}}
                    {{--<input type="hidden" name="subgrupo_de_nome" id="subgrupo_de_nome">--}}
                {{--</form>--}}
            {{--</div>--}}
            {{--<div class="modal-footer">--}}
                {{--<button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>--}}
                {{--<button type="button" class="btn btn-success flat" onclick="cadastrarGrupo();" data-dismiss="modal"><i class="fa fa-save"></i> Salvar</button>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right flat', 'type'=>'submit']) !!}
    <a href="{!! route('admin.mascara_padrao.index') !!}" class="btn btn-danger flat"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>

@section('scripts')
    <script>
        {{--$(function () {--}}
            {{--selectgrupo('{{$grupo_obra->id}}', 'subgrupo1_id', 'grupos');--}}
        {{--});--}}

        {{--function selectgrupo(id, change, tipo){--}}
            {{--console.log(id, change, tipo);--}}
            {{--var rota = "{{url('admin/mascara_padrao/grupos')}}/";--}}
            {{--if(tipo == 'servicos'){--}}
                {{--rota = "{{url('admin/mascara_padrao/servicos')}}/";--}}
            {{--}--}}
            {{--if(id){--}}
                {{--$('.box.box-primary').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');--}}
                {{--$.ajax({--}}
                    {{--url: rota + id,--}}
                    {{--data: {--}}
                        {{--campo_join: change--}}
                    {{--}--}}
                {{--}).done(function(retorno) {--}}
                    {{--options = '';--}}
                    {{--options = '<option value="">Selecione</option>';--}}
                    {{--$('#'+change).html(options);--}}
                    {{--$.each(retorno,function(index, value){--}}
                        {{--options += '<option value="'+index+'">'+value+'</option>';--}}
                    {{--});--}}
                    {{--$('#'+change).html(options);--}}
                    {{--$('#'+change).attr('disabled',false);--}}

                    {{--$('#cadastrar_'+change).css('display', '');--}}
                {{--});--}}
            {{--}else{--}}
                {{--if(change == 'subgrupo1_id'){--}}
                    {{--$('#subgrupo1_id').val(null).trigger('change');--}}
                    {{--$('#subgrupo2_id').val(null).trigger('change');--}}
                    {{--$('#subgrupo3_id').val(null).trigger('change');--}}
                    {{--$('#servico_id').val(null).trigger('change');--}}

                    {{--$('#subgrupo1_id').attr('disabled',true);--}}
                    {{--$('#subgrupo2_id').attr('disabled',true);--}}
                    {{--$('#subgrupo3_id').attr('disabled',true);--}}
                    {{--$('#servico_id').attr('disabled',true);--}}
                {{--}else if(change == 'subgrupo2_id'){--}}
                    {{--$('#subgrupo2_id').val(null).trigger('change');--}}
                    {{--$('#subgrupo3_id').val(null).trigger('change');--}}
                    {{--$('#servico_id').val(null).trigger('change');--}}

                    {{--$('#subgrupo2_id').attr('disabled',true);--}}
                    {{--$('#subgrupo3_id').attr('disabled',true);--}}
                    {{--$('#servico_id').attr('disabled',true);--}}
                {{--}else if(change == 'subgrupo3_id'){--}}
                    {{--$('#subgrupo3_id').val(null).trigger('change');--}}
                    {{--$('#servico_id').val(null).trigger('change');--}}

                    {{--$('#subgrupo3_id').attr('disabled',true);--}}
                    {{--$('#servico_id').attr('disabled',true);--}}
                {{--}else if(change == 'servico_id'){--}}
                    {{--$('#servico_id').attr('disabled',true);--}}
                {{--}--}}
            {{--}--}}
            {{--$('.overlay').remove();--}}
        {{--}--}}

        {{--function cadastrarGrupo() {--}}
            {{--var codigo_grupo = $('#codigo_grupo').val();--}}
            {{--var nome_grupo = $('#nome_grupo').val();--}}
            {{--var subgrupo_de = $('#subgrupo_de').val();--}}
            {{--var subgrupo_de_nome = $('#subgrupo_de_nome').val();--}}
            {{--$.ajax({--}}
                {{--url: '/compras/insumos/orcamento/cadastrar/grupo',--}}
                {{--data: {--}}
                    {{--'codigo_grupo': codigo_grupo,--}}
                    {{--'nome_grupo': nome_grupo,--}}
                    {{--'subgrupo_de': subgrupo_de,--}}
                    {{--'subgrupo_de_nome': subgrupo_de_nome--}}
                {{--}--}}
            {{--}).done(function (json) {--}}
                {{--if(json.salvo){--}}
                    {{--swal("Cadastro realizado com sucesso!", "", "success");--}}

                    {{--$('#codigo_grupo').val('');--}}
                    {{--$('#nome_grupo').val('');--}}
                    {{--$('#subgrupo_de').val('');--}}

                    {{--if(!subgrupo_de){--}}
                        {{--$('#grupo_id').append('<option value="'+json.grupo.id+'">'+json.grupo.codigo+ ' ' +json.grupo.nome+'</option>');--}}
                    {{--}--}}
                    {{--if(subgrupo_de_nome == 'subgrupo1_id' || subgrupo_de_nome == 'subgrupo2_id' || subgrupo_de_nome == 'subgrupo3_id') {--}}
                        {{--selectgrupo(subgrupo_de, subgrupo_de_nome, 'grupos')--}}
                    {{--}else{--}}
                        {{--selectgrupo(subgrupo_de, subgrupo_de_nome, 'servicos')--}}
                    {{--}--}}
                {{--}else{--}}
                    {{--swal("O campo código e nome são obrigatórios.", "", "info");--}}
                {{--}--}}
            {{--});--}}
        {{--}--}}

        {{--function atribuirGrupoId(sub_grupo_de, grupo) {--}}
            {{--$('#subgrupo_de_nome').val(grupo);--}}
            {{--if(sub_grupo_de){--}}
                {{--var subgrupo = $('#'+sub_grupo_de).val();--}}
                {{--if(subgrupo !== undefined){--}}
                    {{--$('#subgrupo_de').val(subgrupo);--}}
                {{--}--}}
            {{--}--}}
        {{--}--}}
    </script>
@stop