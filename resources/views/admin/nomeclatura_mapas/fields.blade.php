<!-- Nome Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nome', 'Nome:') !!}
    {!! Form::text('nome', null, ['class' => 'form-control', 'required'=>'required']) !!}
</div>

<!-- Tipo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tipo', 'Tipo:') !!}
    {!! Form::select('tipo', ['1'=>'Bloco','2'=>'Linha','3'=>'Coluna'], null,
    ['class' => 'form-control', 'required'=>'required']) !!}
</div>

<!-- apenas_cartela Field -->
<div class="form-group col-sm-6">
    {!! Form::label('apenas_cartela', 'Apenas em Cartela:') !!}
    {!! Form::checkbox('apenas_cartela', 1, null, ['class' => 'form-control']) !!}
</div>

<!-- Unidade Field -->
<div class="form-group col-sm-6">
    {!! Form::label('apenas_unidade', 'Apenas em Unidade:') !!}
    {!! Form::checkbox('apenas_unidade', 1, null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success  btn-flat btn-lg pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('admin.nomeclaturaMapas.index') !!}" class="btn btn-danger btn-flat btn-lg "><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>
@section('scripts')
<script type="text/javascript">
    function alteraCombo(){
        if($('input[name="apenas_cartela"]').is(':checked')||$('input[name="apenas_unidade"]').is(':checked')){
            $('option[value="1"]').text('Bloco');
            $('option[value="2"]').text('Linha');
            $('option[value="3"]').text('Coluna');
        }else{
            $('option[value="1"]').text('Estrutura');
            $('option[value="2"]').text('Pavimento');
            $('option[value="3"]').text('Trecho');
        }
    }
    $(function () {
        alteraCombo();
        $('input:not(.btn > input):not(.no-icheck)').on('ifToggled', function(){
            alteraCombo();
        });
    });

</script>
@stop