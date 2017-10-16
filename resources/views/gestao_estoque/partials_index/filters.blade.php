<div class="box box-muted">
    <div class="box-body">
        <div class="row">
            <div class="form-group col-sm-3">
                {!! Form::label('obra_id', 'Obra:') !!}
                {!! Form::select('obra_id', $obras, null, ['class' => 'form-control select2', 'onchange' => 'filter();']) !!}
            </div>

            <div class="form-group col-sm-3">
                {!! Form::label('insumo_id', 'Insumo:') !!}
                {!! Form::select('insumo_id', $insumos, null, ['class' => 'form-control select2', 'onchange' => 'filter();']) !!}
            </div>

            <div class="form-group col-sm-6">
                {!! Form::label('visao', 'Visão:') !!}
                <div class="btn-group">
                    <button onclick="mudaVisao('E');" id="visao_E" class="btn btn-warning" style="width:150px;">Estoque</button>
                    <button onclick="mudaVisao('P');" id="visao_P" class="btn btn-success" style="width:150px;">Perda</button>
                    <button onclick="mudaVisao('C');" id="visao_C" class="btn btn-primary" style="width:150px;">Contrato</button>
                </div>
            </div>
        </div>
    </div>
</div>