<style type="text/css">
    .bloco_filtro {
        height: 100px;
        overflow-y: scroll;
        border: solid 2px #474747;
        background-color: #fff;
        font-size: 17px;
    }
.dataTables_length {
    float: left;
    margin-top: -10px;
 font-weight: 600;
    margin-left: -185px;
    padding-top: 10px;

}
    .list-group-item {
        border-radius: 0px !important;
    }
    .sorting_disabled{min-width:60px;}
    .select2-dropdown{    margin-top: -111px !important;
    margin-left: -61px;
    max-width: 277px;}
    body{
            font-size: 18px;
    }
    textarea.form-control {
    height: auto;
    font-size: 16px;
}
td {font-size:17px;}
.table > tfoot > tr > th {
    padding: 4px 2px!important;
}
  table.dataTable thead > tr > th {
    padding-left: 13px;
    padding-right: 13px;
    font-size: 16px !important;
}
th .form-control{
height: 36px;font-size:17px;
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
                    <button type="button" title="Cadastrar Fornecedor Temporariamente"
                            style="margin-top: 5px; margin-bottom: 5px;"
                            id="cadastrarFornecedorTemporariamente"
                            onclick="cadastraFornecedor()" class="btn btn-block btn-sm btn-flat btn-info">
                        <i class="fa fa-user-plus" aria-hidden="true"></i>
                        Cadastrar Temporariamente
                    </button>
                </div>
                <div class="col-md-12">
                    {!! Form::select('fornecedores_temp', ['' => 'Fornecedores Temporários...'],
                    null,
                    [
                        'class' => 'form-control',
                        'id'=>'fornecedor_temp'
                    ]) !!}
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
                            {{ $qcFornecedor->fornecedor->nome }} - <br> {{$qcFornecedor->fornecedor->cnpj}}
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
        <div class="col-md-5 qc-equalizacao">
           
            {!! Form::label('tiposEqualizacaoTecnicas', 'Equalização Técnica') !!}
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
                            <a href="/tipoEqualizacaoTecnicas/{{$tipoEqualizacaoTecnica_id}}/edit" target="_blank" class="btn btn-xs btn-flat btn-info pull-right" title="Ir para equilização técnica">
                                <i class="fa fa-external-link" aria-hidden="true"></i>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
        <div class="col-md-7" style="margin-top: -11px;">
            <label style="margin-top: 10px;">
               Itens Equalização Técnica
            </label>
            <button type="button" class="btn btn-flat btn-primary btn-xs pull-right" onclick="addEQitem();"
                    title="Adicionar Equalização Técnica apenas para este Q.C.">
                <i class="fa fa-plus"></i>
                Adicionar
            </button>
            <button type="button" class="btn btn-flat btn-primary btn-xs pull-right" onclick="addEQitemAnexo();"
                    title="Adicionar Anexo para Equalização Técnica apenas para este Q.C.">
                <i class="fa fa-plus"></i>
                Adicionar Anexo
                <i class="fa fa-paperclip"></i>
            </button>
            <ul id="equalizacaoTecnicaItens" class="list-group bloco_filtro" style="clear: both;">
                @if(count($quadroDeConcorrencia->tipoEqualizacaoTecnicas()->count()))
                    @foreach($quadroDeConcorrencia->tipoEqualizacaoTecnicas as $tipoEqualizacaoTecnica)
                        @foreach($tipoEqualizacaoTecnica->itens as $EQTitem)
                            <li class="list-group-item eqt_{{ $tipoEqualizacaoTecnica->id }}">
                                {!!  $EQTitem->obrigatorio ? '<i class="fa fa-exclamation text-danger" title="Obrigatório" aria-hidden="true"></i> &nbsp; ' : '' !!}
                                {{ $EQTitem->nome }}
                                <button type="button" class="btn btn-xs btn-flat btn-default pull-right">
                                    <i class="fa fa-info-circle" title="{{ $EQTitem->descricao }}"
                                       onclick="swal('{{ $EQTitem->nome }}','{!!  $EQTitem->obrigatorio ? " ITEM OBRIGATÓRIO \\n " : '' !!}{{ $EQTitem->descricao }}','info')"
                                       aria-hidden="true"></i>
                                </button>
                            </li>
                        @endforeach
                        @foreach($tipoEqualizacaoTecnica->anexos as $EQTitem)
                            <li class="list-group-item eqt_{{ $tipoEqualizacaoTecnica->id }}">
                                {{ $EQTitem->nome }}
                                <a href="{{ Storage::url($EQTitem->arquivo) }}"
                                   download="{{ $EQTitem->nome }}" type="button"
                                   class="btn btn-xs btn-flat btn-default pull-right">
                                    <i class="fa fa-paperclip" title="baixar" aria-hidden="true"></i>
                                </a>
                            </li>
                        @endforeach
                    @endforeach
                @endif
                @if($quadroDeConcorrencia->equalizacaoTecnicaExtras()->count())
                    @foreach( $quadroDeConcorrencia->equalizacaoTecnicaExtras as $qcEqtExtra)
                        <li class="list-group-item" id="eqt_custom_{{ $qcEqtExtra->id }}">
                            <i class="fa fa-pencil-square-o text-warning" title="Apenas para esta QC"
                               aria-hidden="true"></i>
                            {!!  $qcEqtExtra->obrigatorio ? '<i class="fa fa-exclamation text-danger" title="Obrigatório" aria-hidden="true"></i> &nbsp; ' : '' !!}
                            {{ $qcEqtExtra->nome }}
                            <div class="btn-group pull-right">
                                <button type="button" class="btn btn-xs btn-flat btn-default">
                                    <i class="fa fa-info-circle" title="{{ $qcEqtExtra->descricao }}"
                                       onclick="swal('{{ $qcEqtExtra->nome }}','{!!  $qcEqtExtra->obrigatorio ? " ITEM OBRIGATÓRIO \\n " : '' !!}{{ $qcEqtExtra->descricao }}','info')"
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
                @if($quadroDeConcorrencia->equalizacaoTecnicaAnexoExtras()->count())
                    @foreach( $quadroDeConcorrencia->equalizacaoTecnicaAnexoExtras as $qcEqtExtra)
                        <li class="list-group-item" id="eqt_custom_anexo_{{ $qcEqtExtra->id }}">
                            <i class="fa fa-pencil-square-o text-warning" title="Apenas para esta QC"
                               aria-hidden="true"></i>
                            {{ $qcEqtExtra->nome }}
                            <div class="btn-group pull-right">
                                <a href="{{ Storage::url($qcEqtExtra->arquivo) }}"
                                   download="{{ $qcEqtExtra->nome }}" type="button"
                                   class="btn btn-xs btn-flat btn-default">
                                    <i class="fa fa-paperclip" title="baixar" aria-hidden="true"></i>
                                </a>
                                <button type="button" class="btn btn-xs btn-flat btn-warning">
                                    <i class="fa fa-pencil" title="Editar"
                                       onclick="editarEQTAnexo({{ $qcEqtExtra->id }})"
                                       aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-xs btn-flat btn-danger">
                                    <i class="fa fa-trash" title="Remover"
                                       onclick="removerEQTAnexo({{ $qcEqtExtra->id }})"
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

<!-- Obrigações Fornecedor Field -->
<div class="form-group col-sm-6">
    {!! Form::label('obrigacoes_fornecedor', 'Obrigações Fornecedor:') !!}
    {!! Form::textarea('obrigacoes_fornecedor', null, ['class' => 'form-control', 'rows'=>3]) !!}
</div>
<!-- Obrigações Bild Field -->
<div class="form-group col-sm-6">
    {!! Form::label('obrigacoes_bild', 'Obrigações Bild:') !!}
    {!! Form::textarea('obrigacoes_bild', null, ['class' => 'form-control', 'rows'=>3]) !!}
</div>

<div class="row text-right form-inline" style="margin-top: 10px;margin-bottom: 10px;">
    <div class="col-sm-4 text-left" style="margin-top: 10px;margin-bottom: 10px;">
        <span class="col-md-4">
           <label>Template de Minuta</label>
        </span>
        <span class="col-md-8 text-left" style="padding: 0px;">
            {!! Form::select('contrato_template_id',[''=>'Selecione...']+
            \App\Models\ContratoTemplate::pluck('nome','id')->toArray(),null,[
            'class'=>'form-control select2 contratoTemplate',
            'required'=>'required',
            'id'=>'contratoTemplate'
            ]) !!}
        </span>
    </div>

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
                        <input class="form-control" type="text" id="item_eqt_nome"/>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="item_eqt_obrigatorio">Estou ciente: </label>
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
                <button type="button" class="btn btn-flat btn-danger btn-lg pull-left" data-dismiss="modal">Cancelar
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

<!-- Modal Add Anexo -->
<div class="modal fade" id="modalCadastroEQTAnexo" tabindex="-1" role="dialog"
     aria-labelledby="modalCadastroEQTAnexoLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalCadastroEQTAnexoLabel"></h4>
            </div>
            <div class="modal-body">
                <input type="hidden" value="" id="item_eqt_anexo_id">


                <div class="row">
                    <div class="form-group col-sm-12">
                        <label for="item_eqt_anexo_nome">Nome:</label>
                        <input class="form-control" type="text" id="item_eqt_anexo_nome"/>
                    </div>
                    <div class="form-group col-sm-12">
                        <span style="display: none" id="item_eqt_anexo_arquivo_span"></span>
                        <input type="file" class="form-control" id="item_eqt_anexo_arquivo"
                               name="item_eqt_anexo_arquivo">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-flat btn-default btn-lg pull-left" data-dismiss="modal">Cancelar
                </button>
                <button type="button" class="btn btn-flat btn-success btn-lg"
                        id="btn_add_eq_anexo" onclick="addEQitemAnexoSave();">
                    Adicionar
                </button>
                <button type="button" class="btn btn-flat btn-success btn-lg" style="display: none"
                        id="btn_edit_eq_anexo" onclick="editEQitemAnexoSave();">
                    Alterar
                </button>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12">
    <h3 class="pull-left" style="margin-top:50px;">
        Itens
        {!! Form::button( '<i class="fa fa-plus"></i> Adicionar insumos', [
                                'class' => 'btn btn-primary btn-sm btn-flat',
                                'value' => '1',
                                'name' => 'adicionar_itens',
                                'style' => 'margin-left:10px',
                                'type'=>'submit']) !!}
    </h3>
    @include('quadro_de_concorrencias.itens-table')
</div>

@foreach($arrayCarteirasEqualizacaoes as $eq)

    <input type="hidden" name="qcEqualizacao[]" class="qcEqualizacao" value="{{ $eq }}">

@endforeach

@section('scripts')
    @parent
    <script type="text/javascript">
        var quadroDeConcorrenciaId = parseInt({{ $quadroDeConcorrencia->id }});
        var qtdFornecedores = parseInt({!! $qcFornecedorCount !!});
    </script>
    <script type="text/javascript" src="{{ asset('js/qc.js') }}"></script>
@stop