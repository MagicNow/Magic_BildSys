<!-- Mascara Padrao Id Field -->
<div class="form-group col-sm-12">
    {!! Form::label('mascara_padrao_id', 'Máscara Padrão:') !!}
    {!! Form::select('mascara_padrao_id',[''=>'Escolha...']+$mascaras, (isset($mascaraPadrao) ?$mascaraPadrao->id : null), ['class' => 'form-control select2', 'disabled'=>true]) !!}
</div>

@php
    $count_subgrupo = 0;
    $count_subgrupo1 = 0;
    $count_subgrupo2 = 0;
    $count_subgrupo3 = 0;
    $count = [];
@endphp
<div class="form-group col-sm-12">
    @if(count($mascaraPadraoEstruturas))
        <div class="col-md-8" style="padding-bottom: 5px;">
            <div class="col-md-12">
                <div class="input-group">
                    <span class="input-group-addon">{{$grupo->codigo}}</span>
                    <p class="form-control">{{ $grupo->nome }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            {!! Form::hidden('estrutura[0][id]', $grupo->id, ['id'=>'select_grupo_0_select', 'class'=>'estrutura_0']) !!}
            <button type="button" class="btn btn-primary"
                    onclick="addSubItem('select_grupo_0', 0, null, null, null, 'estrutura[0][itens]')"
                    style="margin-left: 53px;width: 145.3px;">
                <i class="fa fa-plus" aria-hidden="true"></i> SubGrupo-1
            </button>
        </div>
        <ul id="select_grupo_0_ul">
            @foreach($subgrupos1 as $subgrupo1)
                @if($subgrupo1->grupo_id == $grupo->id)
                    @php
                        if (!isset($count[$count_subgrupo])) {
                            $count[$count_subgrupo] = [];
                        } else {
                            $count[$count_subgrupo][] = [];
                        }
                    @endphp
                    <li style="list-style-type: none;margin: 0px;" id="subgrupo1_{{$count_subgrupo}}">
                        <div class="col-md-8" style="padding-bottom: 5px;">
                            <div class="input-group">
                                <span class="input-group-addon">{{$subgrupo1->codigo}}</span>
                                {!! Form::select('estrutura[0][itens]['.$count_subgrupo.'][id]',[''=>'Escolha...']+$selectSubgrupos1, $subgrupo1->id, ['id'=>'subgrupo1_'.$count_subgrupo.'_select','class' => 'form-control select2 estrutura_1', 'onchange'=>'percorreBloco()']) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-primary" style="margin-left: 40px;width: 145.3px;"
                                    onclick="addSubItem('subgrupo1_{{$count_subgrupo}}', {{ $count_subgrupo }}, {{ $count_subgrupo1 }}, null, null , 'estrutura[0][itens][{{$count_subgrupo}}][itens]')">
                                <i class="fa fa-plus" aria-hidden="true"></i> SubGrupo-2
                            </button>
                        </div>
                        <ul style="margin: 0px;" id="subgrupo1_{{$count_subgrupo}}_ul">
                            @foreach($subgrupos2 as $subgrupo2)
                                @if($subgrupo2->grupo_id == $subgrupo1->id)
                                    @php
                                        if (!isset($count[$count_subgrupo][$count_subgrupo1])) {
                                            $count[$count_subgrupo][$count_subgrupo1] = [];
                                        } else {
                                            $count[$count_subgrupo][$count_subgrupo1][] = [];
                                        }
                                    @endphp
                                    <li style="list-style-type: none;margin: 0px;" id="subgrupo2_{{$count_subgrupo1}}">
                                        <div class="col-md-8" style="padding-bottom: 5px;">

                                            <div class="input-group">
                                                <span class="input-group-addon">{{$subgrupo2->codigo}}</span>
                                                {!! Form::select("estrutura[0][itens][". $count_subgrupo ."][itens][".$count_subgrupo1.'][id]',[''=>'Escolha...']+$selectSubgrupos2, $subgrupo2->id, ['id'=>'subgrupo2_'.$count_subgrupo1.'_select', 'class' => 'form-control select2 estrutura_2', 'onchange'=>'percorreBloco()']) !!}
                                            </div>

                                        </div>
                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-primary"
                                                    style="margin-left: 27px;width: 145.3px;"
                                                    onclick="addSubItem('subgrupo2_{{$count_subgrupo1}}', {{ $count_subgrupo }}, {{ $count_subgrupo1 }}, {{ $count_subgrupo2 }}, null, 'estrutura[0][itens][{{$count_subgrupo}}][itens][{{$count_subgrupo1}}][itens]')">
                                                <i class="fa fa-plus" aria-hidden="true"></i> SubGrupo-3
                                            </button>
                                        </div>
                                        <ul style="margin: 0px;" id="subgrupo2_{{$count_subgrupo1}}_ul">
                                            @php
                                                $count_subgrupo2 = 0;
                                            @endphp
                                            @foreach($subgrupos3 as $subgrupo3)
                                                @if($subgrupo3->grupo_id == $subgrupo2->id)
                                                    @php
                                                        if (!isset($count[$count_subgrupo][$count_subgrupo1][$count_subgrupo2])) {
                                                            $count[$count_subgrupo][$count_subgrupo1][$count_subgrupo2] = [];
                                                        } else {
                                                            $count[$count_subgrupo][$count_subgrupo1][$count_subgrupo2][] = [];
                                                        }
                                                    @endphp
                                                    <li style="list-style-type: none; margin: 0px;"
                                                        id="subgrupo3_{{$count_subgrupo2}}">
                                                        <div class="col-md-8" style="padding-bottom: 5px;">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">{{$subgrupo3->codigo}}</span>
                                                                {!! Form::select("estrutura[0][itens][".$count_subgrupo."][itens][".$count_subgrupo1."][itens][".$count_subgrupo2.'][id]',[''=>'Escolha...']+$selectSubgrupos3, $subgrupo3->id, ['id'=>'subgrupo3_'.$count_subgrupo2.'_select', 'class' => 'form-control select2 estrutura_3', 'onchange'=>'percorreBloco()']) !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <button type="button" class="btn btn-primary"
                                                                    style="margin-left: 13px;width: 145.3px;"
                                                                    onclick="addSubItem('subgrupo3_{{$count_subgrupo2}}', {{ $count_subgrupo }}, {{ $count_subgrupo1 }},  {{ $count_subgrupo2 }}, {{ $count_subgrupo3 }}, 'estrutura[0][itens][{{$count_subgrupo}}][itens][{{$count_subgrupo1}}][itens][{{$count_subgrupo2}}][itens]')">
                                                                <i class="fa fa-plus" aria-hidden="true"></i> Serviço
                                                            </button>
                                                        </div>
                                                        <ul style="margin: 0px;" id="subgrupo3_{{$count_subgrupo2}}_ul">
                                                            @foreach($servicos as $servico)
                                                                @if($servico->grupo_id == $subgrupo3->id)
                                                                    @php
                                                                        if (!isset($count[$count_subgrupo][$count_subgrupo1][$count_subgrupo2][$count_subgrupo3])) {
                                                                            $count[$count_subgrupo][$count_subgrupo1][$count_subgrupo2][$count_subgrupo3] = [];
                                                                        } else {
                                                                            $count[$count_subgrupo][$count_subgrupo1][$count_subgrupo2][$count_subgrupo3][] = [];
                                                                        }
                                                                    @endphp
                                                                    <li style="list-style-type: none; margin: 0px;"
                                                                        id="subgrupo4_{{$count_subgrupo3}}">
                                                                        <div class="col-md-8"
                                                                             style="padding-bottom: 5px;">
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon">{{$servico->codigo}}</span>
                                                                                {!! Form::select("estrutura[0][itens][".$count_subgrupo."][itens][".$count_subgrupo1."][itens][".$count_subgrupo2.'][itens]['.$count_subgrupo3.'][id]',[''=>'Escolha...']+$selectServicos, $servico->id, ['id'=>'subgrupo4_'.$count_subgrupo3.'_select','class' => 'form-control select2 estrutura_4', 'onchange'=>'percorreBloco()']) !!}
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <button type="button"
                                                                                    class="btn btn-warning"
                                                                                    style="width: 145px"
                                                                                    onclick="RedirectAddInsumo('subgrupo4_{{$count_subgrupo3}}', '')">
                                                                                <i class="fa fa-floppy-o" aria-hidden="true"></i> Insumos
                                                                            </button>
                                                                        </div>
                                                                    </li>
                                                                    @php
                                                                        $count_subgrupo3++;
                                                                    @endphp
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    </li>
                                                    @php
                                                        $count_subgrupo2++;
                                                    @endphp
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                    @php
                                        $count_subgrupo1++;
                                    @endphp
                                @endif
                            @endforeach
                        </ul>
                    </li>
                    @php
                        $count_subgrupo++;
                    @endphp
                @endif
            @endforeach
        </ul>
    @else
        <div class="col-md-8" style="padding-bottom: 5px;">
            <div class="input-group">
                <span class="input-group-addon">{{$grupo->codigo}}</span>
                <p class="form-control">{{ $grupo->nome }}</p>
            </div>
        </div>
        <div class="col-md-4">
            {!! Form::hidden('estrutura[0][id]', $grupo->id, ['id'=>'select_grupo_0_select', 'class'=>'estrutura_0']) !!}
            <button type="button" class="btn btn-primary" style="margin-left: 53px; width: 145px;"
                    onclick="addSubItem('select_grupo_0', 0, null, null, null, 'estrutura[0][itens]')">
                <i class="fa fa-plus" aria-hidden="true"></i> SubGrupo-1
            </button>
        </div>
        <ul id="select_grupo_0_ul">
        </ul>
    @endif
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12 btn-toolbar">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save-continue') ), ['name' => 'save', 'value' => 'save-continue', 'class' => 'btn btn-success pull-right', 'type' => 'submit', 'onclick' => '']) !!}

    <a href="{!! route('admin.mascaraPadraoEstruturas.index') !!}" class="btn btn-default"><i
                class="fa fa-times"></i> {{ ucfirst( trans('common.cancel') )}}</a>
</div>

@section('scripts')
    <style>
        #select_grupo_0_ul ul > li {
            margin-left: 10px;
        }
    </style>
    <script type="text/javascript">
        var bloco_aberto = true;

        $(function () {
            percorreBloco();
        });

        $("form").submit(function (event) {
            if (bloco_aberto) {
                event.preventDefault();
                swal('Existe um bloco em aberto', '', 'error');
                $('.overlay').remove();

            }
        });

                @if(isset($count))
        var $count = {!! json_encode($count) !!};
        @endif

        function addSubItem(id_atual, nivel1, nivel2, nivel3, nivel4, nome) {
//            debugger;

            var $length;
            var nivel;
            var adicionar = '';
            var novo = '';
            bloco_aberto = true;

            if (nivel1 != null && nivel2 != null && nivel3 != null && nivel4 != null) {
                $length = $count[nivel1][nivel2][nivel3][nivel4].length;
                nivel = 4;
            } else if (nivel1 != null && nivel2 != null && nivel3 != null && nivel4 == null) {
                $length = $count[nivel1][nivel2][nivel3].length;
                nivel = 3;
            } else if (nivel1 != null && nivel2 != null && nivel3 == null && nivel4 == null) {
                $length = $count[nivel1][nivel2].length;
                nivel = 2;
            } else if (nivel1 != null && nivel2 == null && nivel3 == null && nivel4 == null) {
                if (typeof  $count[nivel1] != 'undefined') {
                    $length = $count[nivel1].length;
                } else {
                    $length = 0;
                }
                nivel = 1;
            }
            console.log('nivel: ', nivel);

            var rota = "{{url('/admin/mascara-padrao-estruturas/grupos')}}/";
            if (nivel > 3) {
                rota = "{{url('/admin/mascara-padrao-estruturas/servicos')}}/";
            }

            var $idGeral = 'subgrupo' + nivel + '_' +nivel1+'_'+nivel2+'_'+nivel3+'_'+nivel4+'_' + $length ;

            if (nivel == 1) {
                adicionar = '<button type="button" ' +
                    'class="btn btn-primary" ' +
                    'style="margin-left: 40px;width: 145.3px;"' +
                    'onclick="addSubItem(\'' + $idGeral + '\',' + nivel1 + ','+$length+' ,null ,null, \'' + nome + '[' + $length + '][itens]' + '\')">' +
                    '<i class="fa fa-plus" aria-hidden="true"></i> SubGrupo-2' +
                    '</button>';
                novo = '<button type="button" class="btn btn-primary" style="margin-left: -65px;" onclick="">Novo</button>';
            }

            if (nivel == 2) {
                adicionar = '<button type="button" ' +
                    'class="btn btn-primary" ' +
                    'style="margin-left: 27px;width: 145px;"'+
                    'onclick="addSubItem(\'' + $idGeral + '\', '+nivel1+' ,'+nivel2+','+$length+'  ,null, \'' + nome + '[' + $length + '][itens]' + '\')">' +
                    '<i class="fa fa-plus" aria-hidden="true"></i> SubGrupo-3' +
                    '</button>';
                novo = '<button type="button" class="btn btn-primary" style="margin-left: -69px;" onclick="">Novo</button>';
            }

            if (nivel == 3) {
                adicionar = '<button type="button" ' +
                    'class="btn btn-primary" ' +
                    'style="margin-left: 13px;width: 145.3px;"' +
                    'onclick="addSubItem(\'' + $idGeral + '\', '+nivel1+' ,'+nivel2+' , '+nivel3+', '+$length+', \'' + nome + '[' + $length + '][itens]' + '\')">' +
                    '<i class="fa fa-plus" aria-hidden="true"></i> Serviços' +
                    '</button>';
                novo = '<button type="button" class="btn btn-primary" style="margin-left: -72px;" onclick="">Novo</button>';
            }

            if (nivel == 4) {
                adicionar = '<button type="button" ' +
                    'class="btn btn-warning" ' +
                    'style="width: 145px"' +
                    'onclick="RedirectAddInsumo(\'' + $idGeral +'\',\''+nome+'['+$length+']'+'\')">' +
                    '<i class="fa fa-floppy-o" aria-hidden="true"></i> Insumos' +
                    '</button>';
                novo = '<button type="button" class="btn btn-primary" style="margin-left: -75px;" onclick="">Novo</button>';
            }

            var id = $('#' + id_atual + '_select').val();
            if (id) {
                $('.box.box-primary').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
                $.ajax({
                    url: rota + id
                }).done(function (retorno) {
                    options = '<option value="">Selecione</option>';
                    $.each(retorno, function (index, value) {
                        options += '<option value="' + index + '">' + value + '</option>';
                    });

                    selectHTML = '' +
                        '<div class="col-md-8" style="padding-bottom: 5px;">' +
                        '<li style="list-style-type: none; margin: 0px;" id="subgrupo' + nivel + '_' + $length + '">' +
                        '<select class="form-control select2 estrutura_' + nivel + '" onchange="percorreBloco()" name="' + nome + '[' + $length + '][id]" id="subgrupo' + nivel + '_' +nivel1+'_'+nivel2+'_'+nivel3+'_'+nivel4+'_' + $length + '_select">' +
                        options +
                        '</select>' +
                        '</li>' +
                        '</div>' +
                        '<div class="col-md-3">' +
                        adicionar +
                        '</div>' +
                        '<div class="col-md-1">' +
                        novo +
                        '</div>' +
                        '<ul id="subgrupo' + nivel + '_' +nivel1+'_'+nivel2+'_'+nivel3+'_'+nivel4+'_' + $length + '_ul">' +
                        '</ul>';
                    $('#' + id_atual + '_ul').append(selectHTML);
                    $('.overlay').remove();

                    if (nivel4 != null) {
                        $count[nivel1][nivel2][nivel3][nivel4].push([]);
                    } else if (nivel1 != null && nivel2 != null && nivel3 != null) {
                        $count[nivel1][nivel2][nivel3].push([]);
                    } else if (nivel1 != null && nivel2 != null) {
                        $count[nivel1][nivel2].push([]);
                    } else if (nivel1 != null) {
                        if (typeof  $count[nivel1] != 'undefined') {
                            $count[nivel1].push([]);
                        } else {
                            $count[nivel1] = [[]];
                        }
                    }
                }).fail(function () {
                    $('.overlay').remove();
                });
            }
        }

        function RedirectAddInsumo(nome, count) {
            $('form').append([
                '<input type="hidden" name="' + nome + '[' + (count + 1) + '][id]' + '">',
                '<input type="hidden" name="save" value="save-continue">',
                '<input type="hidden" name="btn_insumo" value="1">'
            ]).submit();
        }

        function percorreBloco() {
            console.log('entrei aqui');
            bloco_aberto = false;
            if (!$('.estrutura_0').length) {
                bloco_aberto = true;
                return false;
            }
            $('.estrutura_0').each(function (idx) {
                if (!$(this).val()) {
                    bloco_aberto = true;
                    return false;
                }
                if (!$('.estrutura_1').length) {
                    bloco_aberto = true;
                    return false;
                }
                $('.estrutura_1').each(function (idx) {
                    if (!$(this).val()) {
                        bloco_aberto = true;
                        return false;
                    }
                    if (!$('.estrutura_2').length) {
                        bloco_aberto = true;
                        return false;
                    }
                    $('.estrutura_2').each(function (idx) {
                        if (!$(this).val()) {
                            bloco_aberto = true;
                            return false;
                        }
                        if (!$('.estrutura_3').length) {
                            bloco_aberto = true;
                            return false;
                        }
                        $('.estrutura_3').each(function (idx) {
                            if (!$(this).val()) {
                                bloco_aberto = true;
                                return false;
                            }
                            if (!$('.estrutura_4').length) {
                                bloco_aberto = true;
                                return false;
                            }
                            $('.estrutura_4').each(function (idx) {
                                if (!$(this).val()) {
                                    bloco_aberto = true;
                                    return false;
                                }
                            });
                        });
                    });
                });
            });
        }
    </script>
@stop
