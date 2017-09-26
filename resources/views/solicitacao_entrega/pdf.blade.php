@extends('layouts.printable')

@section('content')
    <style>
        thead, tfoot { display: table-row-group !important; }
        /*html{*/
            /*zoom: 0.9;*/
        /*}*/
    </style>
    <div style="padding-top: 120px;">
        <section>
            <div class="row">
                <div class="col-xs-6 form-group">
                    <p class="form-control input-lg highlight" style="height: 120px;border-color: #000000;">
                        <b>DADOS DO FORNECEDOR:</b><br>
                        {!!
                            $entrega->fornecedor_id
                            ? $entrega->fornecedor->nome
                            : $entrega->contrato->fornecedor->nome
                         !!} <br>
                        {!!
                            $entrega->fornecedor_id
                            ? $entrega->fornecedor->cnpj
                            : $entrega->contrato->fornecedor->cnpj
                        !!}<br>
                        {!!
                            $entrega->fornecedor_id
                            ?
                                $entrega->fornecedor->tipo_logradouro.'. '.$entrega->fornecedor->logradouro.', '
                                .$entrega->fornecedor->numero.' - '.$entrega->fornecedor->municipio.' - '
                                .$entrega->fornecedor->estado
                            :
                                $entrega->contrato->fornecedor->tipo_logradouro.'. '.$entrega->contrato->fornecedor->logradouro.', '
                                .$entrega->contrato->fornecedor->numero.' - '.$entrega->contrato->fornecedor->municipio.' - '
                                .$entrega->contrato->fornecedor->estado
                        !!}
                    </p>
                </div>

                <div class="col-xs-3 form-group">
                    <p class="form-control input-lg highlight" style="height: 120px;border-color: #000000;">
                        <b>CONTATO DO FORNECEDOR:</b><br>
                        {!!
                            $entrega->fornecedor_id
                            ? $entrega->fornecedor->telefone ?:'<span class="text-danger">Sem telefone</span>'
                            : $entrega->contrato->fornecedor->telefone ?:'<span class="text-danger">Sem telefone</span>'
                        !!}
                        <br>
                        {!!
                            $entrega->fornecedor_id
                            ? $entrega->fornecedor->email ?: '<span class="text-danger">Sem email</span>'
                            : $entrega->contrato->fornecedor->email ?: '<span class="text-danger">Sem email</span>'
                        !!}
                    </p>
                </div>

                <div class="col-xs-3 form-group">
                    <p class="form-control input-lg highlight" style="height: 120px;border-color: #000000;">
                        <b>DADOS DO PEDIDO:</b><br>
                        {!! $entrega->id !!}<br>
                        {!! $entrega->created_at->format('d/m/Y') !!}
                    </p>
                </div>
            </div>
        </section>

        @include('solicitacao_entrega.table')

        <section>
            <div class="row">
                <div class="col-xs-6 form-group">
                    <p class="form-control input-lg highlight" style="height: 120px;border-color: #000000;">
                        <b>DADOS DE ENTREGA:</b><br>
                        {!! $entrega->contrato->obra->nome !!}<br>
                        {!! $entrega->contrato->obra->endereco_obra !!}<br>
                        {!! $entrega->contrato->obra->adm_obra_nome !!}
                    </p>
                </div>

                <div class="col-xs-2 form-group">
                </div>

                <div class="col-xs-4 form-group">
                    <p class="form-control input-lg highlight" style="height: 120px;border-color: #000000;">
                        <b>TOTAL GERAL:</b><br>
                        {{ float_to_money($entrega->total) }}
                    </p>
                </div>
            </div>
        </section>
        <section>
            <div class="row">
                <div class="col-xs-12 form-group">
                    <p class="form-control input-lg highlight" style="height: 120px;border-color: #000000;">
                        <b>CONSIDERAÇÕES DO PEDIDO:</b><br>
                        <span style="color:red;">
                            - Este pedido segue os critérios do ACORDO DE PREÇO firmado entre as partes,
                            conforme o Anexo 2 (anexar minuta do catálogo)<br>
                            - Materiais objetos deste PEDIDO deverão ser fornecidos de acordo com as especificações fornecidas
                            pela CONTRATANTE, inclusive as especificações de plantas e desenhos relacionados no ACORDO DE
                            PREÇO, assim como as normas correspondentes no ANEXO 1.
                        </span>
                    </p>
                </div>
            </div>
        </section>
    </div>
@stop