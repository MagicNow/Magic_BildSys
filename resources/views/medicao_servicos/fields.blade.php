<!-- Periodo Inicio Field -->
<div class="form-group col-sm-6">
    {!! Form::label('periodo_inicio', 'Periodo Início:') !!}
    {!! Form::date('periodo_inicio', null, ['class' => 'form-control']) !!}
</div>

<!-- Periodo Termino Field -->
<div class="form-group col-sm-6">
    {!! Form::label('periodo_termino', 'Periodo Término:') !!}
    {!! Form::date('periodo_termino', null, ['class' => 'form-control']) !!}
</div>

<!-- Qtd Funcionarios Field -->
<div class="form-group col-sm-4">
    {!! Form::label('qtd_funcionarios', 'Quantidade de Funcionários:') !!}
    {!! Form::number('qtd_funcionarios', null, ['class' => 'form-control', 'min'=>'0']) !!}
</div>

<!-- Qtd Ajudantes Field -->
<div class="form-group col-sm-4">
    {!! Form::label('qtd_ajudantes', 'Quantidade de Ajudantes:') !!}
    {!! Form::number('qtd_ajudantes', null, ['class' => 'form-control text-right', 'min'=>'0']) !!}
</div>

<!-- Qtd Outros Field -->
<div class="form-group col-sm-4">
    {!! Form::label('qtd_outros', 'Quantidade de Outros:') !!}
    {!! Form::number('qtd_outros', null, ['class' => 'form-control text-right', 'min'=>'0']) !!}
</div>

<!-- Descontos Field -->
<div class="form-group col-sm-6">
    {!! Form::label('descontos', 'Descontos:') !!}
    {!! Form::text('descontos', null, ['class' => 'form-control text-right money']) !!}
</div>

<!-- Descontos Field -->
<div class="form-group col-sm-6">
    {!! Form::label('descricao_descontos', 'Descrição dos Descontos:') !!}
    {!! Form::text('descricao_descontos', null, ['class' => 'form-control']) !!}
</div>

{!! Form::hidden('contrato_item_apropriacao_id', request('contrato_item_apropriacao_id')) !!}



<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success btn-lg btn-flat pull-right', 'type'=>'submit']) !!}
    @if(isset($medicaoServico))
        @if(request()->segment(count(request()->segments()))=='edit' && !$medicaoServico->finalizado)
            @if(!$medicaoServico->medicoes()->where('aprovado','0')->count())
            {!! Form::button( '<i class="fa fa-check"></i> Salvar e Enviar para Aprovação', [
                                'class' => 'btn btn-warning btn-lg btn-flat pull-right',
                                'value'=>'1',
                                'style'=>'margin-right:10px',
                                'name'=>'finalizado',
                                'type'=>'submit']) !!}
            @else
                <button title="Impossível enviar para aprovação, pois existem itens reprovados!" type="button"
                        disabled class="btn btn-warning btn-lg btn-flat pull-right" data-toggle="tooltip"
                        data-placement="top" style="margin-right:10px">
                    <i class="fa fa-check"></i>  Salvar e Enviar para Aprovação
                </button>
            @endif
        @endif
    @endif
    <button type="button" onclick="history.go(-1);" class="btn btn-default btn-lg btn-flat"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</button>
</div>
