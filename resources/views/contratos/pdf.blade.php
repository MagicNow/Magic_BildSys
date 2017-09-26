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
                        {!! $contrato->fornecedor->nome !!} <br>
                        {!! $contrato->fornecedor->cnpj  !!}<br>
                        {!! $contrato->fornecedor->tipo_logradouro.'. '.$contrato->fornecedor->logradouro.', '
                        .$contrato->fornecedor->numero.' - '.$contrato->fornecedor->municipio.' - '
                        .$contrato->fornecedor->estado !!}
                    </p>
                </div>

                <div class="col-xs-3 form-group">
                    <p class="form-control input-lg highlight" style="height: 120px;border-color: #000000;">
                        <b>CONTATO DO FORNECEDOR:</b><br>
                        {!! $contrato->fornecedor->telefone ?: '<span class="text-danger">Sem telefone</span>'  !!}<br>
                        {!! $contrato->fornecedor->email ?: '<span class="text-danger">Sem email</span>' !!}
                    </p>
                </div>

                <div class="col-xs-3 form-group">
                    <p class="form-control input-lg highlight" style="height: 120px;border-color: #000000;">
                        <b>DADOS DO PEDIDO:</b><br>
                        {!! $contrato->id !!}<br>
                        {!! $contrato->created_at->format('d/m/Y') !!}
                    </p>
                </div>
            </div>
        </section>

        @if(!$espelho)
            @include('contratos.table')
        @endif

        <section>
            <div class="row">
                <div class="col-xs-6 form-group">
                    <p class="form-control input-lg highlight" style="height: 120px;border-color: #000000;">
                        <b>DADOS DE ENTREGA:</b><br>
                        {!! $contrato->obra->nome !!}<br>
                        {!! $contrato->obra->endereco_obra !!}<br>
                        {!! $contrato->obra->adm_obra_nome !!}
                    </p>
                </div>

                <div class="col-xs-3 form-group">
                    @if($espelho)
                        <p class="form-control input-lg highlight" style="height: 120px;border-color: #000000;">
                            <b>CONDIÇÕES DE PAGAMENTO:</b><br>
                            COLOCAR CONDIÇÕES DE
                            PAGAMENTO. (Minuta)
                        </p>
                    @endif
                </div>

                <div class="col-xs-3 form-group">
                    <p class="form-control input-lg highlight" style="height: 120px;border-color: #000000;">
                        <b>TOTAL GERAL DO CONTRATO:</b><br>
                        SOMATÓRIA DO CONTRATO
                    </p>
                </div>
            </div>
        </section>

        @if($espelho)
            <section>
                <div class="row">
                    <div class="col-xs-6 form-group">
                        <p style="padding-top: 175px">
                            <b>ANEXO 1– <u>TABELA DE NORMAS DOS MATERIAS CONTROLADOS</u></b><br>
                        </p>
                    </div>

                    <div class="col-xs-6 form-group">
                        <p class="form-control input-lg highlight" style="height: 190px;border-color: #000000;">
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
        @endif
    </div>
@stop