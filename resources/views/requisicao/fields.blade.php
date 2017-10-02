<!-- Obra Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('obra_id', 'Obra:') !!}
    {!! Form::select('obra_id',[''=>'Selecione...'] + $obras, null, ['class' => 'form-control select2']) !!}

</div>

<div class="form-group col-sm-6">
    {!! Form::label('local', 'Local:') !!}
    {!! Form::select('local',[''=>'Selecione','torre' => 'Torre', 'canteiro' => 'Canteiro', 'escritorio' => 'Escritório'], null, ['class' => 'form-control select2']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('torre', 'Torre:') !!}
    {!! Form::select('torre', ['Selecione uma Obra'], null, ['class' => 'form-control select2']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('pavimento', 'Pavimento:') !!}
    {!! Form::select('pavimento', ['Selecione uma Torre'], null, ['class' => 'form-control select2']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('trecho', 'Trecho:') !!}
    {!! Form::select('trecho', ['Selecione um Pavimento'], null, ['class' => 'form-control select2']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('andar', 'Andar:') !!}
    {!! Form::select('andar', ['Selecione um Pavimento'], null, ['class' => 'form-control select2']) !!}
</div>

<div class="form-group col-sm-12 text-center">
    <button type="button" class="btn btn-primary hide" id="js-btn-buscar-insumos">
        Buscar Insumos
    </button>
</div>

<div id="hidden-fields"></div>

<div class="form-group col-sm-12">

<table id="insumos-table" class="table table-striped table-responsive hide">
    <thead>
        <tr align="left">
            <th width="30%">Insumo</th>
            <th width="12%">Un de Medida</th>
            <th width="12%">Previsto</th>
            <th width="12%">Disponível</th>
            <th width="12%">Em Estoque</th>
            <th width="18%">Qtde</th>
            <th>Detalhes</th>
        </tr>
    </thead>

    <tbody id="body-insumos-table">

    </tbody>
</table>

</div>


<!-- Modal -->
<div class="modal fade" id="modal-insumos-comodo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Insumos por Cômodo</h4>
            </div>
            <div class="modal-body">

                <input type="hidden" id="insumo-comodo-modal" value="">

                <table id="insumos-comodo-table" class="table table-striped table-responsive">
                    <thead>
                        <tr align="left">
                            <th width="30%">Apartamento</th>
                            <th width="12%">Cômodo</th>
                            <th width="12%">Disponível</th>
                            <th width="18%">Qtde</th>
                        </tr>
                    </thead>

                    <tbody id="body-insumos-comodo-table">

                    </tbody>
                </table>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Salvar</button>
            </div>
        </div>
    </div>
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right hide', 'type'=>'submit', 'id' => 'btn-create-requisicao']) !!}
    <a href="{!! route('requisicao.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>