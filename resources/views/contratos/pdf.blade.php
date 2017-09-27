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

        @include('contratos.table', compact($espelho))

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
                    @if(!$contrato->quadroDeConcorrencia->user_id)
                        @if($contrato->pagamentoCondicao)
                            <p class="form-control input-lg highlight" style="height: 120px;border-color: #000000;">
                                <b>CONDIÇÕES DE PAGAMENTO:</b><br>
                                {{$contrato->pagamentoCondicao->nome.' - '.$contrato->pagamentoCondicao->codigo}}
                            </p>
                        @endif
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

        <section>
            <div class="row">
                <div class="col-xs-12 form-group">
                    <p class="form-control input-lg highlight" style="height: 120px;border-color: #000000;">
                        @if(!$contrato->quadroDeConcorrencia->user_id)
                        <b>CONSIDERAÇÕES DO PEDIDO:</b><br>
                        <span style="color:red;">
                            - Este pedido segue os critérios do ACORDO DE PREÇO firmado entre as partes,
                            conforme o Anexo 2 (anexar minuta do catálogo)<br>
                            - Materiais objetos deste PEDIDO deverão ser fornecidos de acordo com as especificações fornecidas
                            pela CONTRATANTE, inclusive as especificações de plantas e desenhos relacionados no ACORDO DE
                            PREÇO, assim como as normas correspondentes no ANEXO 1.
                        </span>
                        @else
                            <b>DADOS DE FATURAMENTO / COBRANÇA:</b><br>
                            {{$contrato->obra->razao_social}}<br>
                            {{$contrato->obra->cnpj}}<br>
                            {{$contrato->obra->endereco_obra}}
                        @endif
                    </p>
                </div>
            </div>
        </section>

        <div class="col-xs-12 form-group">
            <p class="text-center">
                <b>ANEXO 1– <u>TABELA DE NORMAS DOS MATERIAS CONTROLADOS</u></b><br>
            </p>

            <div class="panel panel-default panel-normal-table">
                <div class="panel-body table-responsive">
                    <table class="table table-bordered table-all-center">
                        <thead>
                        <tr>
                            <th>MATERIAL</th>
                            <th>NORMAS NBR’S</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    Areia e Brita
                                </td>
                                <td>
                                    - NBR 7211 Agregados para concreto.<br>
                                    - Licença ambiental de extração, dentro do prazo de validade (manter cópia na obra).
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Argamassa Usinada (Revestimento e Assentamento)
                                </td>
                                <td>
                                    -NBR 13281 – Argamassa industrializada para assentamentos de paredes e revestimentos de paredes e tetos – Requisitos
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Bancadas (Granito, Sintética ou similar)
                                </td>
                                <td>
                                    - NBR 15845-1 - Rochas para revestimento Parte 1: Análise petrográfica.<br>
                                    - NBR 15844 - Rochas para revestimento - Requisitos para granitos.
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Barras, Telas e Fios de Aço
                                </td>
                                <td>
                                    - NBR 7480 Aço destinado a armaduras para estruturas de concreto armado.<br>
                                    - NBR 7481 Tela de aço soldada - Armadura para concreto.<br>
                                    - Certificado de qualidade do lote (verificações laboratoriais).
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Batentes
                                </td>
                                <td>
                                    - NBR 15930-1 - Portas de madeira para edificações Parte 1: Terminologia e simbologia.<br>
                                    - NBR 15930-2- Portas de madeira para edificações Parte 2: Requisitos.<br>
                                    - NBR 8542 – Desempenho de porta de madeira de edificação.
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Blocos de Concreto (estrutural e vedação
                                </td>
                                <td>
                                    - NBR 6136 – Blocos vazados de concreto simples para alvenaria.<br>
                                    - NBR 15575 – Edificações Habitacionais - Desempenho
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Bloco Cerâmico
                                </td>
                                <td>
                                    - NBR 15270 – 1 – Componentes Cerâmicos – Parte 1: Blocos Cerâmicos para Alvenaria de Vedação – Terminologia e Requisitos.<br>
                                    - NBR 15270 - 2 – Componentes Cerâmicos – Parte 2: Bloco Cerâmico para Alvenaria Estrutural – Terminologia e Requisitos.<br>
                                    - NBR 15812 – Alvenaria estrutural — Blocos Cerâmicos.<br>
                                    - NBR 7170 – Tijolo Maciço Cerâmico para Alvenaria.
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Chapas de Madeira Compensada
                                </td>
                                <td>
                                    - NBR ISO 1096 - Madeira compensada.<br>
                                    - NBR ISO 12466-1-2- Madeira compensada – qualidade de colagem.
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Chapas de Madeira Compensada
                                </td>
                                <td>
                                    - NBR ISO 1096 - Madeira compensada.<br>
                                    - NBR ISO 12466-1-2- Madeira compensada – qualidade de colagem.<br>
                                    - NBR (ISO 2426-1-3- Madeira compensada – classificação pela aparência superficial).
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Concreto
                                </td>
                                <td>
                                    - NBR 7212 – Execução de concreto dosado em central<br>
                                    - NBR 12655 – Concreto – Preparo, Controle e Recebimento – Procedimento
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Cubas de Inox
                                </td>
                                <td>
                                    - NBR 6666 - Produtos planos de aço inoxidável - Propriedades mecânicas.
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Ensacados (Cimento, Argamassa, Cal, Gesso em Pó, Rejunte, etc.)
                                </td>
                                <td>
                                    - NBR 5732 para o CP-I.<br>
                                    - NBR 11578 para o CP-II.<br>
                                    - NBR 5735 para o CP-III.<br>
                                    - NBR 5736 para o CP-IV.<br>
                                    - NBR 5733 para o CP-V.<br>
                                    - NBR 13281 Argamassa utilizada em assentamento e revestimento de paredes e tetos.<br>
                                    - NBR 7175 - Cal hidratada para argamassas.<br>
                                    - Cimento: Selo de qualidade da ABCP – Associação Brasileira de Cimento Portland.<br>
                                    - NBR 13207 – Gesso para construção civil.<br>
                                    - NBR 12127 – Gesso para construção civil - Determinação das propriedades físicas do pó.
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Esquadrias de alumínio e Contramarco
                                </td>
                                <td>
                                    - NBR 10821-1 - Esquadrias externas para edificações Parte 1: Terminologia.<br>
                                    - NBR 10821-2 - Esquadrias externas para edificações Parte 2: Requisitos e classificação.<br>
                                    - NBR 10821-3 - Esquadrias externas para edificações Parte 3: Métodos de ensaio.<br>
                                    - NBR 13756 – Esquadrias de Alumínio – Guarnição e Elastomérica em EPDM para vedação – Especificação.<br>
                                    - NBR 15575 – Edificações Habitacionais – Desempenho.
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Fios e Cabos
                                </td>
                                <td>
                                    - NBR 6813 Fios e cabos elétricos - Ensaio de resistência de isolamento - Método de ensaio.<br>
                                    - NBR 6814 Fios e cabos elétricos - Ensaio de resistência elétrica - Método do ensaio.
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Forma Pronta
                                </td>
                                <td>
                                    - NBR 11700 - Madeira serrada de coníferas provenientes de reflorestamento para uso geral – classificação.<br>
                                    - NBR 12498- Madeira serrada de coníferas provenientes de reflorestamento para uso geral - dimensões e lotes.<br>
                                    - NBR 9487- Classificação de madeira serrada de folhosas.<br>
                                    - NBR ISO 1096- Madeira compensada – classificação.<br>
                                    - NBR ISO 12466-1-2- Madeira compensada – qualidade de colagem.<br>
                                    - NBR ISO 2426-1-3- Madeira compensada – classificação pela aparência superficial).
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Fechaduras e Dobradiças
                                </td>
                                <td>
                                    - NBR 14913 - Fechadura de embutir – Requisitos, classificação e métodos de ensaio.<br>
                                    - NBR 12927 – Fechaduras<br>
                                    - NBR 7178 – Dobradiças de abas – Especificação e Desempenho
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Interruptores e Tomadas
                                </td>
                                <td>
                                    - NBR 60669 2-(1), (2) e (3) Interruptores para instalações fixas domésticas fixas domésticas e análogas.
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Intertravados
                                </td>
                                <td>
                                    - NBR 15953 - Pavimento intertravado com peças de concreto — Execução.
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Kits Porta Pronta e Folhas de Porta de Madeira
                                </td>
                                <td>
                                    - NBR 8542- Desempenho de porta de madeira de edificação.<br>
                                    - NBR 15575 – Edificações Habitacionais – Desempenho
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Louças
                                </td>
                                <td>
                                    - NBR 15097- Aparelhos sanitários de material cerâmico.<br>
                                    - NBR 15099 – Aparelhos sanitários de material cerâmico – Dimensões padronizadas.
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Manta Asfáltica
                                </td>
                                <td>
                                    - NBR 9952- Manta asfáltica para impermeabilização.<br>
                                    - NBR 15575 – Edificações Habitacionais – Desempenho
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Madeiras Nativas - Cedrinho, Peroba, Canela, etc.
                                </td>
                                <td>
                                    - NBR 9487- Classificação de madeira serrada de folhosas.
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Madeiras do tipo Pinus, Eucalipto, etc.
                                </td>
                                <td>
                                    - NBR 11700- Madeira serrada de coníferas provenientes de reflorestamento para uso geral-classificação;<br>
                                    - NBR 12498- Madeira serrada de coníferas provenientes de reflorestamento para uso geral.
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Metais Sanitários
                                </td>
                                <td>
                                    - NBR 10281- Torneira de pressão – requisitos e métodos de ensaio.<br>
                                    - NBR 15705- Instalações hidráulicas prediais -registro de gaveta.<br>
                                    - NBR 12904- Válvula de descarga.<br>
                                    - NBR 11815- Misturador para pia de cozinha tipo parede.<br>
                                    - NBR 11535- Misturador para pia de cozinha tipo mesa.<br>
                                    - NBR 15423- Válvulas escoamento - requisitos e métodos de ensaio.<br>
                                    - NBR 14878- Ligações flexíveis para aparelhos hidráulicos sanitários - requisitos e métodos de ensaio.<br>
                                    - NBR 14390- Misturador para lavatório - requisitos e métodos de ensaio.<br>
                                    - NBR 14162- Aparelhos sanitários – sifão - requisitos e métodos de ensaio.
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Pedras Naturais
                                </td>
                                <td>
                                    - NBR 7205 - Placa de mármore natural para revestimentos superficiais verticais externos.<br>
                                    - NBR 7206 – Placas de mármore natural para revestimentos de pisos.
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Placas de Gesso
                                </td>
                                <td>
                                    - NBR 12775 - Placas lisas de gesso para forro – Determinação das dimensões e propriedades físicas.
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Portas e Janelas Metálicas
                                </td>
                                <td>
                                    - NBR 10821 - Esquadrias externas para edificações.<br>
                                    - NBR 15575 – Edificações Habitacionais – Desempenho
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Piso e Azulejos Cerâmicos
                                </td>
                                <td>
                                    - NBR 13818 - Placas cerâmicas para revestimento, especificação e métodos de ensaio.<br>
                                    - NBR 15575 – Edificações Habitacionais – Desempenho
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Porta Corta Fogo
                                </td>
                                <td>
                                    - NBR 11742 - Porta corta-fogo para saída de emergência.
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Piso em Madeira
                                </td>
                                <td>
                                    - NBR 15345 - Instalação predial de tubos e conexões de cobre e ligas de cobre –Procedimento.<br>
                                    - NBR 5688 - Tubos e conexões de PVC-U para sistemas prediais de água pluvial, esgoto sanitário e ventilação – Requisitos.
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Telhas
                                </td>
                                <td>
                                    - NBR 7581 – Telha ondulada de fibrocimento.<br>
                                    - NBR 7196 - Telhas de fibrocimento - Execução de coberturas e fechamentos laterais – Procedimento.<br>
                                    - NBR 13858 – 2 – Telha de Concreto – Requisitos e métodos de ensaio.<br>
                                    - NBR 14514 – Telhas de aço revestido de seção trapezoidal – Requisitos.<br>
                                    - NBR 15310 - Componentes cerâmicos - Telhas - Terminologia, requisitos e métodos de ensaio.
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Tinta, Desmoldante, Impermeabilizante, etc
                                </td>
                                <td>
                                    - NBR 15079 - Tintas para construção civil - Especificação dos requisitos mínimos de desempenho de tintas para edificações não industriais - Tinta látex nas cores claras.<br>
                                    - NBR 11702 – Tinta para edificações não industriais.
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Vidros
                                </td>
                                <td>
                                    - NBR 14697- Vidro laminado.<br>
                                    - NBR 11706 – Vidros na construção civil
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop