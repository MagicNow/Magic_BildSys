<div class="col-md-12 loading">
    <h3>Relacionamento de Máscara Padrão / Insumos</h3>
    <div class="col-md-12 thumbnail">
        <div class="col-md-12">
            <div class="caption">
                <div class="card-description">
                    
					<!-- Máscara Padrão Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::label('mascara_padrao_id', 'Máscara Padrão:') !!}
                        {!! Form::select('mascara_padrao_id', [''=>'-']+$mascaraPadrao, null, ['class' => 'form-control select2', 'id'=>'mascara_padrao_id', 'required'=>'required']) !!}
                    </div>	
					
					<!-- SubGrupos1 de Insumo Field -->
					<div class="form-group col-sm-12 {{ 'col-md-3' }}">
						{!! Form::label('subgrupo1_id', 'SubGrupo-1:') !!}
						{!! Form::text('subgrupo1_id', null, ['class' => 'form-control', 'required']) !!}
					</div>

					<!-- SubGrupos2 de Insumo Field -->
					<div class="form-group col-sm-12 {{ 'col-md-3' }}">
						{!! Form::label('subgrupo2_id', 'SubGrupo-2:') !!}
						{!! Form::text('subgrupo2_id', null, ['class' => 'form-control', 'required']) !!}
					</div>

					<!-- SubGrupos3 de Insumo Field -->
					<div class="form-group col-sm-12 {{ 'col-md-3' }}">
						{!! Form::label('subgrupo3_id', 'SubGrupo-3:') !!}
						{!! Form::text('subgrupo3_id', null, ['class' => 'form-control', 'required']) !!}
					</div>

					<!-- SubGrupos4 de Insumo Field -->
					<div class="form-group col-sm-12 {{ 'col-md-3' }}">
						{!! Form::label('servico_id', 'Serviço:') !!}
						{!! Form::text('servico_id', null, ['class' => 'form-control', 'required']) !!}
					</div>													

                    <!-- Grupo de insumos Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::label('grupo_insumo_id', 'Grupo de insumos:') !!}
                        {!! Form::select('grupo_insumo_id', [''=>'-']+$grupoInsumos, null, ['class' => 'form-control select2', 'id'=>'grupo_insumo_id', 'required'=>'required', 'onchange'=>'insumos(this.value)']) !!}
                    </div>
					
					<!-- Coeficiente Field -->
					<div class="form-group col-sm-3">
						{!! Form::label('coeficiente', 'Coeficiente Padrão:') !!}
						{!! Form::text('coeficiente', null, ['class' => 'form-control', 'required']) !!}
					</div>

                    <div id="insumos" class="col-md-12"></div>					

                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
                        <a href="{!! route('admin.mascara_padrao_insumos.index') !!}" class="btn btn-danger"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>