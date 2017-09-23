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
    {!! Form::select('andar', ['Selecione um Andar'], null, ['class' => 'form-control select2']) !!}
</div>

<div class="form-group col-sm-12">

<table id="responsive-example-table" class="table table-striped table-responsive">
    <tbody>
        <tr align="left">
            <th width="30%">Insumo</th>
            <th width="12%">Un de Medida</th>
            <th width="12%">Previsto</th>
            <th width="12%">Disponível</th>
            <th width="12%">Em Estoque</th>
            <th width="18%">Qtde</th>
            <th>Detalhes</th>
        </tr>
        <tr>
            <td>Cimento</td>
            <td>SC</td>
            <td>1000</td>
            <td>800</td>
            <td>300</td>
            <td><input type="text" class="form-control"></td>
            <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                    Detalhar
                </button></td>
        </tr>
        <tr>
            <td>Tijolo</td>
            <td>UN</td>
            <td>50.000</td>
            <td>45.000</td>
            <td>30.000</td>
            <td><input type="text" class="form-control"></td>
            <td>a</td>
        </tr>
        <tr>
            <td>Cimento</td>
            <td>SC</td>
            <td>1000</td>
            <td>800</td>
            <td>300</td>
            <td><input type="text" class="form-control"></td>
            <td>a</td>
        </tr>
        <tr>
            <td>Tijolo</td>
            <td>UN</td>
            <td>50.000</td>
            <td>45.000</td>
            <td>30.000</td>
            <td><input type="text" class="form-control"></td>
            <td>a</td>
        </tr>
        <tr>
            <td>Cimento</td>
            <td>SC</td>
            <td>1000</td>
            <td>800</td>
            <td>300</td>
            <td><input type="text" class="form-control"></td>
            <td>a</td>
        </tr>
        <tr>
            <td>Tijolo</td>
            <td>UN</td>
            <td>50.000</td>
            <td>45.000</td>
            <td>30.000</td>
            <td><input type="text" class="form-control"></td>
            <td>a</td>
        </tr>
    </tbody>
</table>

</div>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Insumos por Cômodo</h4>
            </div>
            <div class="modal-body">

                <table id="responsive-example-tablee" class="table table-striped table-responsive">
                    <tbody>
                        <tr align="left">
                            <th width="30%">Apartamento</th>
                            <th width="12%">Cômodo</th>
                            <th width="12%">Disponível</th>
                            <th width="18%">Qtde</th>
                        </tr>
                        <tr>
                            <td>21</td>
                            <td>Cozinha</td>
                            <td>1</td>
                            <td><input type="text" class="form-control"></td>
                        </tr>
                        <tr>
                            <td>21</td>
                            <td>Banheiro</td>
                            <td>1</td>
                            <td><input type="text" class="form-control"></td>
                        </tr>
                        <tr>
                            <td>21</td>
                            <td>Suíte</td>
                            <td>1</td>
                            <td><input type="text" class="form-control"></td>
                        </tr>
                    </tbody>
                </table>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary">Salvar</button>
            </div>
        </div>
    </div>
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('requisicao.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>
