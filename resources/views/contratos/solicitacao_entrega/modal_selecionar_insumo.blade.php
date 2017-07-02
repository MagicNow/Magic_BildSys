<div class="modal fade" id="modal-selecionar-insumos" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Selecionar Insumos</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('insumo_id', 'Insumo') !!}
                            {!!
                                Form::select(
                                    'insumo_id',
                                    [],
                                    null,
                                    [
                                        'class'    => 'form-control js-input',
                                        'id'       => 'insumo_id',
                                        'required' => 'required'
                                    ]
                                )
                            !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('qtd_total', 'Quantidade') !!}
                            {!!
                                Form::text(
                                    'qtd_total',
                                    null,
                                    [
                                        'class'               => 'form-control money js-input',
                                        'required'            => 'required',
                                        'id'                  => 'qtd_total',
                                        'data-allow-zero'     => 'true',
                                        'data-allow-negative' => 'false',
                                    ]
                                )
                            !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Valor Unitário</label>
                            {!!
                                Form::text(
                                    'valor_unitario',
                                    null,
                                    [
                                        'class'               => 'form-control money js-input',
                                        'required'            => 'required',
                                        'id'                  => 'valor_unitario',
                                        'data-allow-zero'     => 'true',
                                        'data-allow-negative' => 'false',
                                    ]
                                )
                            !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <button class="btn btn-info btn-flat btn-block" id="add-to-list" disabled>
                            Adicionar na lista
                        </button>
                    </div>
                    <div class="col-sm-6">
                        <button class="btn btn-warning btn-flat btn-block"
                            id="solicitar-insumo">
                            Solicitar novo insumo
                        </button>
                    </div>
                </div>
                <div id="lista-de-troca" class="hidden">
                    <h4>Insumos Selecionados</h4>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nome</th>
                                <th>Unidade</th>
                                <th>Quantidade</th>
                                <th>Valor Unitário</th>
                                <th>Valor Total</th>
                                <th style="width: 5%">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success btn-flat" data-dismiss="modal">
                    Salvar Seleção
                </button>
            </div>
        </div>
    </div>
</div>
