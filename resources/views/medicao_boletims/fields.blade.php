<!-- Obra Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('obra_id', 'Obra Id:') !!}
    {!!
        Form::select(
          'obra_id',
          $obras,
          null,
          [
            'id'       => 'obra_id',
            'class'    => 'form-control select2',
            'required' => 'required'
          ]
        )
      !!}
</div>

<!-- Contrato Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('contrato_id', 'Contrato:') !!}
    {!! Form::select('contrato_id',[], null, ['class' => 'form-control select2','required' => 'required']) !!}
</div>

<div class="form-group col-sm-12" id="medicoes">
    <ol>
        <li>
            <input type="hidden" name="medicaoServicos[]" value="1">
            
        </li>
    </ol>
</div>
<div class="form-group col-sm-12" id="blocoMedicoes">
    @include('medicao_servicos.table')
</div>
<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success btn-flat btn-lg pull-right', 'type'=>'submit']) !!}
    <button type="button" onclick="history.go(-1);" class="btn btn-default btn-flat btn-lg"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</button>
</div>

@section('scripts')
    @parent
    <script type="text/javascript">
        var obra_id = null;
        var contrato_id = null;

        function buscaContratos() {
            if (!obra_id) {
                $('#contrato_id').html('');
                $('#contrato_id').trigger('change.select2');
            }else{
                $.ajax('/medicoes/contratos-por-obra', {
                    data: {
                        obra: obra_id
                    }
                })
                        .done(function (retorno) {
                            var options_contratos = '<option value="" selected>-</option>';
                            if(retorno.data){
                                $.each(retorno.data, function (index, valor) {
                                    options_contratos += '<option value="' + valor.id + '">' + valor.id + ' | '  + valor.fornecedor.nome + '</option>';
                                });
                            }
                            $('#contrato_id').html(options_contratos);
                            $('#contrato_id').trigger('change.select2');

                        })
                        .fail(function (retorno) {
                            erros = '';
                            $.each(retorno.responseJSON, function (index, value) {
                                if (erros.length) {
                                    erros += '<br>';
                                }
                                erros += value;
                            });
                            swal("Oops", erros, "error");
                        });
            }

        }

        $(function () {
            // Colocar OnChange na Obra buscar Fornecedores com contratos
            $('#obra_id').on('change', function (evt) {
                var v_obra = $(evt.target).val();
                obra_id = v_obra;

                $('#filtro_obra').val($('#obra_id option:selected').text()).trigger( "change" );

                buscaContratos();

            });
            @if(old('obra_id'))

                $('#obra_id').val('').trigger( "change" );
            @endif
            // Na hora q selecionar o Fornecedor trazer os insumos q este contrato tem contrato
            $('#contrato_id').on('change', function (evt) {
                contrato_id = $(evt.target).val();
                $('#filtro_contrato').val(contrato_id).trigger( "change" );
            });


        });
    </script>
@stop