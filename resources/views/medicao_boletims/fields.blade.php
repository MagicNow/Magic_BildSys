<!-- Obra Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('obra_id', 'Obra:') !!}
    @if(!isset($medicaoBoletim))
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
    @else
        <div class="form-control">
            {{ $medicaoBoletim->obra->nome }}
        </div>
    @endif
</div>

<!-- Contrato Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('contrato_id', 'Contrato:') !!}
    @if(!isset($medicaoBoletim))
        {!! Form::select('contrato_id',[], null, ['class' => 'form-control select2','required' => 'required']) !!}
    @else
        <div class="form-control">
            {{ $medicaoBoletim->contrato_id . ' - '. $medicaoBoletim->contrato->fornecedor->nome }}
        </div>
    @endif
</div>
<div class="form-group col-sm-12">
    {!! Form::label('obs', 'Observações:') !!}
    {!! Form::textarea('obs', null, ['class' => 'form-control', 'id'=>'obs', 'rows'=>2]) !!}
</div>
<div class="form-group col-sm-12">
    <table class="table table-hover table-condensed table-striped table-striped" id="medicoes">
        <thead>
        <tr>
            <th width="10%">
                Medição
            </th>
            <th width="40%">
                Insumo
            </th>
            <th width="10%">
                Quant. Trechos
            </th>
            <th width="30%">
                Valor
            </th>
            <th width="10%">
                Remover
            </th>
        </tr>
        </thead>
        <tbody>
        <?php
        $somaTotal = 0;
        ?>
        @if(isset($medicaoBoletim))

            @foreach($medicaoBoletim->medicaoServicos as $medicaoServico)
                <tr id="medicaoServico{{ $medicaoServico->id }}">
                    <td>   {{ $medicaoServico->id }}
                        <input type="hidden" name="medicaoServicos[]" value="{{ $medicaoServico->id }}">
                    </td>
                    <td class="text-left">
                        {{ $medicaoServico->contratoItemApropriacao->insumo->codigo . ' - '.  $medicaoServico->contratoItemApropriacao->insumo->nome }}
                    </td>
                    <td class="text-right">
                        {{ $medicaoServico->medicoes()->count() }}
                    </td>
                    <td class="text-right">
                        <?php
                            $valorItem = $medicaoServico->medicoes()->sum('qtd')* $medicaoServico->contratoItemApropriacao->contratoItem->valor_unitario;
                            $somaTotal += $valorItem;
                        ?>
                        {{ float_to_money($valorItem) }}
                    </td>
                    <td>
                        <button type="button" onclick="removerMedicaoServicoSalva( {{ $medicaoServico->id }} )"
                                class="btn btn-sm btn-danger btn-flat"><i class="fa fa-times"></i></button>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
        <tfoot>
            <tr class="warning">
                <td colspan="4" class="text-right" style="font-weight: bold !important; font-size: 16px;" id="somaTotal">
                    @if(isset($medicaoBoletim))
                        {{ float_to_money($somaTotal) }}
                    @endif
                </td>
                <td>

                </td>
            </tr>
        </tfoot>

    </table>
</div>
<div class="form-group col-sm-12" id="blocoMedicoes" style="display: none">
    @include('medicao_servicos.table')
</div>
<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ),
    ['class' => 'btn btn-success btn-flat btn-lg pull-right', 'type'=>'submit']) !!}
    <button type="button" onclick="history.go(-1);" class="btn btn-default btn-flat btn-lg">
        <i class="fa fa-times"></i> {{ ucfirst( trans('common.cancel') )}}
    </button>
</div>

@section('scripts')
    @parent
    <script type="text/javascript">
        var obra_id = null;
        var contrato_id = null;
        var v_somaTotal = {{ $somaTotal }};
        var valores_array = [];
        @if(isset($medicaoBoletim))
            @foreach($medicaoBoletim->medicaoServicos as $medicaoServico)
                valores_array[{{ $medicaoServico->id }}] = {{ ($medicaoServico->medicoes()->sum('qtd')* $medicaoServico->contratoItemApropriacao->contratoItem->valor_unitario) }};
            @endforeach

            function removerMedicaoServicoSalva(medicao_servico_id) {
                swal({
                            title: "Remover " + medicao_servico_id + "?",
                            text: "Ao confirmar não será possível voltar atrás",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Remover",
                            cancelButtonText: 'Cancelar',
                            closeOnConfirm: false,
                            confirmButtonColor: '#dd4b39',
                            showLoaderOnConfirm: true,
                        },
                        function() {

                            $.ajax("/boletim-medicao/{{ $medicaoBoletim->id }}/remover-medicao/"+medicao_servico_id)
                                    .done(function () {
                                        swal.close()
                                        removerMedicaoServico(medicao_servico_id);
                                        LaravelDataTables.dataTableBuilder.draw();
                                    });
                        });

            }
        @endif

        function adicionaMedicaoServico(medicao_servico_id, insumo, soma, trechos) {
            valores_array[medicao_servico_id] = soma;
            v_somaTotal += soma;
            var novaMedicao = '<tr id="medicaoServico' + medicao_servico_id + '">' +
                    '<td>   ' + medicao_servico_id +
                    '   <input type="hidden" name="medicaoServicos[]" value="' + medicao_servico_id + '">' +
                    '</td>' +
                    '<td class="text-left">' + insumo +
                    '</td>' +
                    '<td class="text-right">' + trechos +
                    '</td>' +
                    '<td class="text-right">' + floatToMoney(soma) +
                    '</td>' +
                    '<td> <button type="button" onclick="removerMedicaoServico(' + medicao_servico_id + ')" ' +
                    ' class="btn btn-sm btn-danger btn-flat"> <i class="fa fa-times"></i> </button> ' +
                    '</td>' +
                    '</tr>';
            $('#medicoes tbody').append(novaMedicao);
            $('#btnAdicionarMedicaoServico' + medicao_servico_id).parent().parent().parent().hide();
            $('#somaTotal').html(floatToMoney(v_somaTotal));
        }

        function removerMedicaoServico(qual) {
            v_somaTotal -= valores_array[qual];
            $('#medicaoServico' + qual).remove();
            $('#btnAdicionarMedicaoServico' + qual).parent().parent().parent().show();
            $('#somaTotal').html(floatToMoney(v_somaTotal));
        }





        function buscaContratos() {
            if (!obra_id) {
                $('#blocoMedicoes').hide();
                $('#contrato_id').html('');
                $('#contrato_id').trigger('change.select2');
            } else {
                $.ajax('/medicoes/contratos-por-obra', {
                    data: {
                        obra: obra_id
                    }
                })
                        .done(function (retorno) {
                            var options_contratos = '<option value="" selected>-</option>';
                            if (retorno.data) {
                                $.each(retorno.data, function (index, valor) {
                                    options_contratos += '<option value="' + valor.id + '">' + valor.id + ' | ' + valor.fornecedor.nome + '</option>';
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
                            $('#blocoMedicoes').hide();
                        });
            }

        }

        $(function () {
            // Colocar OnChange na Obra buscar Fornecedores com contratos
            $('#obra_id').on('change', function (evt) {
                var v_obra = $(evt.target).val();
                obra_id = v_obra;

                $('#filtro_obra').val($('#obra_id option:selected').text()).trigger("change");

                buscaContratos();

            });
            @if(old('obra_id'))

                $('#obra_id').val('').trigger("change");
            @endif
            @if(isset($medicaoBoletim))
                setTimeout(function () {
                $('#filtro_obra').val('{{ $medicaoBoletim->obra->nome }}').trigger("change");
                $('#filtro_contrato').val({{ $medicaoBoletim->contrato_id }}).trigger("change");
                $('#blocoMedicoes').show();
            }, 1000);

            @endif
            // Na hora q selecionar o Fornecedor trazer os insumos q este contrato tem contrato
            $('#contrato_id').on('change', function (evt) {
                contrato_id = $(evt.target).val();
                if (contrato_id > 0) {
                    $('#filtro_contrato').val(contrato_id).trigger("change");
                    $('#blocoMedicoes').show();
                } else {
                    $('#blocoMedicoes').hide();
                }

            });


        });
    </script>
@stop