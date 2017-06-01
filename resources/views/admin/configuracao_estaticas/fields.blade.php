<!-- Chave Field -->
<div class="form-group col-sm-12">
    {!! Form::label('chave', 'Nome:') !!}
    {!! Form::text('chave', null, ['class' => 'form-control']) !!}
</div>

<!-- Teste Field -->
<div class="form-group col-sm-12">
    {!! Form::label('valor', 'Descrição:') !!}
    {!! Form::textarea('valor', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('admin.configuracaoEstaticas.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>
