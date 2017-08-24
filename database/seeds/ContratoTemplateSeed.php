<?php

use Illuminate\Database\Seeder;

class ContratoTemplateSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('contrato_templates')->whereIn('id',[1,2])->delete();

        $items = [
            [
                'id' => 2,
                'nome' => 'Minuta de Acordo',
                'tipo' => 'A',
                'template' => '<div>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-align: center;">
                                    <span style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:underline;vertical-align:baseline;white-space:pre-wrap;">ACORDO DE PREÇO UNITÁRIO DE MATERIAL PARA CONSTRUÇÃO CIVIL</span>
                                </p>
                                [CABECALHO_MATRIZ]
                                <p dir="ltr" style="line-height:1.2;margin-top:0pt;margin-bottom:0pt;text-align: justify;"> </p>
                                <p dir="ltr" style="line-height: 1.2; margin-top: 0pt; margin-bottom: 0pt;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">B</span><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;"> –</span><span
                                        style="vertical-align: baseline;"> </span><span style="text-align: center;">[NOME_FORNECEDOR] </span><span
                                        style="vertical-align: baseline;">,</span><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;"> pessoa jurídica de direito privado, com sede na</span><span
                                        style="vertical-align: baseline;"> <span style="text-align: center;">[TIPO_LOGRADOURO_FORNECEDOR] </span> </span><span
                                        style="text-align: center;">[LOGRADOURO_FORNECEDOR] </span><span style="vertical-align: baseline;">, <span
                                        style="text-align: center;">[NUMERO_FORNECEDOR]  </span> <span style="text-align: center;">[COMPLEMENTO_FORNECEDOR] </span>, CEP <span
                                        style="text-align: center;">[CEP_FORNECEDOR] </span>,</span><span style="vertical-align: baseline;"> na cidade de <span
                                        style="text-align: center;">[MUNICIPIO_FORNECEDOR] </span>, <span style="text-align: center;">[ESTADO_FORNECEDOR] </span> inscrita no CNPJ sob nº </span><span
                                        style="text-align: center;">[CNPJ_FORNECEDOR] </span><span style="vertical-align: baseline;"> com se</span><span
                                        style="vertical-align: baseline;">u</span><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;"> contrato social devidamente registrado na junta Comercial do Estado de São Paulo, neste ato representada na forma de seus atos constitutivos</span><span
                                        style="vertical-align: baseline;"> por </span><span
                                        style="text-align: center;">[NOME_SOCIO_OU_PROCURADOR]</span><span
                                        style="vertical-align: baseline;">,</span><span
                                        style="vertical-align: baseline;"> nacionalidade </span><span style="text-align: center;">[NACIONALIDADE_SOCIO_OU_PROCURADOR]</span><span
                                        style="vertical-align: baseline;">, </span><span
                                        style="text-align: center;">[ESTADO_CIVIL_SOCIO_PROCURADOR]</span><span style="vertical-align: baseline;">, profissão </span><span
                                        style="text-align: center;">[PROFISSAO_SOCIO_OU_PROCURADOR]</span><span
                                        style="vertical-align: baseline;">,</span><span style="vertical-align: baseline;"> </span><span
                                        style="background-color: transparent; font-size: 12pt; font-family: Arial; font-weight: 400; vertical-align: baseline; white-space: pre-wrap;">inscrito no RG </span><span
                                        style="vertical-align: baseline;">nº </span><span
                                        style="text-align: center;">[RG_SOCIO_OU_PROCURADOR]</span><span style="vertical-align: baseline;">, inscrito no CPF nº </span><span
                                        style="text-align: center;">[CPF_SOCIO_PROCURADOR]</span><span style="vertical-align: baseline;">, domiciliado(s) na </span><span
                                        style="text-align: center;">[ENDERECO_SOCIO_OU_PROCURADOR]</span><span style="vertical-align: baseline;">, na cidade de </span><span
                                        style="text-align: center;">[CIDADE_SOCIO_OU_PROCURADOR]</span><span style="vertical-align: baseline;">, Estado de <span
                                        style="text-align: center;">[ESTADO_SOCIO_OU_PROCURADOR]</span>,</span><span
                                        style="background-color: transparent; font-size: 12pt; font-family: Arial; font-weight: 400; vertical-align: baseline; white-space: pre-wrap;"> </span><span
                                        style="vertical-align: baseline;">CEP <span style="text-align: center;">[CEP_SOCIO_OU_PROCURADOR]</span> , Telefone <span
                                        style="text-align: center;">[TELEFONE_SOCIO_OU_PROCURADOR]</span> , Celular <span
                                        style="text-align: center;">[CELULAR_SOCIO_OU_PROCURADOR]</span>, e-mail  </span><span
                                        style="text-align: center;">[EMAIL_SOCIO_OU_PROCURADOR]</span><span style="vertical-align: baseline;">, doravante denominada simplesmente CONTRATADA.</span>
                                </p>
                                <p dir="ltr" style="line-height:1.2;margin-top:0pt;margin-bottom:0pt;text-align: justify;"> </p>
                                <p dir="ltr" style="line-height: 1.2; margin-top: 0pt; margin-bottom: 0pt;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">B.1 - </span><span
                                        style="text-align: center;">[NOME_DO_VENDEDOR]</span><span style="vertical-align: baseline;">, <span
                                        style="text-align: center;">[TELEFONE_DO_VENDEDOR]</span> </span><span style="text-align: center;">[EMAIL_DO_VENDEDOR]</span><span
                                        style="vertical-align: baseline;">,</span><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;"> doravante denominado simplesmente VENDEDOR DA CONTRATADA.</span>
                                </p>
                                <p dir="ltr" style="line-height:1.2;margin-top:0pt;margin-bottom:0pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;"> .</span>
                                </p>
                                <p dir="ltr" style="line-height:1.2;margin-top:0pt;margin-bottom:0pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">C – </span><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:underline;vertical-align:baseline;white-space:pre-wrap;">NATUREZA DO ACORDO</span><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;"> </span><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">– O presente acordo versa sobre a compra e venda de material com preço individualizado para cada material abaixo descrito garantido pelo prazo de duração do presente acordo, que se regerá pelos termos e cláusulas do presente instrumento.</span>
                                </p>
                                <p dir="ltr" style="line-height:1.2;margin-top:0pt;margin-bottom:0pt;text-align: justify;"> </p>
                                <p dir="ltr" style="line-height:1.2;margin-top:0pt;margin-bottom:0pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">D – </span><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:underline;vertical-align:baseline;white-space:pre-wrap;">ESPECIFICAÇÕES DOS MATERIAIS CONTRATADOS</span>
                                </p>
                                <p dir="ltr" style="line-height:1.2;margin-top:0pt;margin-bottom:0pt;text-align: justify;"> </p>
                                <p dir="ltr" style="line-height:1.2;margin-top:0pt;margin-bottom:0pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">D.1 - Descrição dos Materiais:</span>
                                </p>
                                <p dir="ltr" style="line-height:1.2;margin-top:0pt;margin-bottom:0pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">[CATALOGO_CONTRATO_ITENS]<br></span>
                                </p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-align: justify;"> </p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">D.1.1. </span><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">Os preços, expressos em reais, manter-se-ão fixos e irreajustáveis durante a vigência deste ACORDO, salvo disposição em contrário.</span>
                                </p>
				<p dir="ltr" style="line-height:1.2;margin-top:0pt;margin-bottom:0pt;text-align: justify;">
                                    <span style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">D.3. Regionais:</span>
                                    <span style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">[REGIONAIS]</span>
                                </p>
                                <p dir="ltr" style="line-height:1.2;margin-top:0pt;margin-bottom:0pt;text-align: justify;">&nbsp;</p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-align: justify;"> </p>
                                <p dir="ltr" style="line-height:1.2;margin-top:0pt;margin-bottom:0pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">D.2. Da composição do preço do material:</span>
                                </p>
                                <p dir="ltr" style="line-height:1.2;margin-top:0pt;margin-bottom:0pt;text-align: justify;"> </p>
                                <p dir="ltr" style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;"><span style="text-align: center;">[COMPOSICAO_DO_PRECO]</span><br>
                                </p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-align: justify;"> </p>
                                <p dir="ltr" style="line-height:1.2;margin-top:0pt;margin-bottom:0pt;text-align: justify;"> </p>
                                <p dir="ltr" style="line-height:1.2;margin-top:0pt;margin-bottom:0pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">E – </span><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:underline;vertical-align:baseline;white-space:pre-wrap;">PREÇO</span><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:underline;vertical-align:baseline;white-space:pre-wrap;">:</span>
                                </p>
                                <p dir="ltr" style="line-height:1.2;margin-top:0pt;margin-bottom:0pt;text-align: justify;"> </p>
                                <p dir="ltr" style="line-height:1.2;margin-top:0pt;margin-bottom:0pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">E.1.</span><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;"> O preço de cada material é aquele delimitado no item “D.1” acima, sendo que as quantidades deverão ser solicitadas por escrito pela CONTRATANTE, conforme a necessidade desta, garantindo a CONTRATADA o preço ajustado até o fim do ACORDO.</span>
                                </p>
                                <p dir="ltr" style="line-height:1.2;margin-top:0pt;margin-bottom:0pt;text-align: justify;"> </p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:underline;vertical-align:baseline;white-space:pre-wrap;">F - FORMA E LOCAL DE PAGAMENTO:</span>
                                </p>
                                <p dir="ltr" style="line-height:1.2;margin-top:0pt;margin-bottom:0pt;text-align: justify;"> </p>
                                <p dir="ltr" style="line-height: 1.38; margin-top: 0pt; margin-bottom: 10pt;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">F.1.</span><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;"> A CONTRATANTE realizará o pagamento</span><span
                                        style="vertical-align: baseline;"> </span><span style="vertical-align: baseline;">em <span
                                        style="text-align: center;">[DIAS_PAGAMENTO]</span> (<span style="text-align: center;">[DIAS_PAGAMENTO_POR_EXTENSO]</span>) dias</span><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;"> após a emissão da nota fiscal e entrega do Material; </span>
                                </p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:10pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">F.2. </span><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">A Nota Fiscal deve ser entregue em até 03 (três) dias da data de sua emissão. O recebimento após este prazo implicará automaticamente na prorrogação do prazo de vencimento do citado documento.</span>
                                </p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:10pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">F.3.</span><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;"> A CONTRATANTE deve entregar a Nota Fiscal no endereço indicado no PEDIDO DE COMPRA/CONTRATO/ACORDO.</span>
                                </p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:10pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">F.4. </span><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">A não emissão da nota fiscal ou o não cumprimento de TODAS as obrigações assumidas, inclusive falta de material ou material com defeitos, darão ensejo à retenção do pagamento até o cumprimento das obrigações, sem que seja configurada a mora da CONTRATANTE.</span>
                                </p>
                                <p dir="ltr" style="line-height:1.2;margin-top:0pt;margin-bottom:0pt;text-align: justify;"> </p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:underline;vertical-align:baseline;white-space:pre-wrap;">G - PRAZO DE ENTREGA DO MATERIAL</span><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">: </span><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">O prazo de entrega será determinado em cada solicitação de faturamento do material, que integrar-se-á ao presente instrumento.</span>
                                </p>
                                <p dir="ltr"
                                   style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-indent: -14.2pt;text-align: justify;padding:0pt 0pt 0pt 14.2pt;">
                                     </p>
                                <p dir="ltr"
                                   style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-indent: -14.2pt;text-align: justify;padding:0pt 0pt 0pt 14.2pt;">
                                    <span style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:underline;vertical-align:baseline;white-space:pre-wrap;">H – VIGÊNCIA DO ACORDO DE PREÇO: </span>
                                </p>
                                <p dir="ltr"
                                   style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-indent: -14.2pt;text-align: justify;padding:0pt 0pt 0pt 14.2pt;">
                                     </p>
                                <p dir="ltr" style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">H.1. </span><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">O presente Acordo tem validade</span><span
                                        style="vertical-align: baseline;"> </span><span style="vertical-align: baseline;">por <span
                                        style="text-align: center;">[MESES_VALIDADE_NUMERO]</span> (<span style="text-align: center;">[MESES_VALIDADE_EXTENSO]</span>) meses</span><span
                                        style="vertical-align: baseline;">,</span><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;"> contados da assinatura do presente acordo.</span>
                                </p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">H.2. </span><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;"> Vencido o período de validade do acordo, se nenhuma das PARTES entrar em contato, o mesmo será prorrogado e valido até que uma das PARTES informe a outra por escrito do término do mesmo.</span>
                                </p>
                                <p dir="ltr"
                                   style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-indent: -14.2pt;text-align: justify;padding:0pt 0pt 0pt 14.2pt;">
                                     </p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:underline;vertical-align:baseline;white-space:pre-wrap;">I - SOLICITAÇÃO DO MATERIAL:</span>
                                </p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-align: justify;"> </p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">I.1.</span><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;"> Acordam as PARTES que os preços ora negociados serão aplicados para as contratações a serem realizadas por qualquer das empresas afiliadas ou controladas pela CONTRATANTE, sendo que a compradora do material será devidamente identificada no PEDIDO DE COMPRA/CONTRATO/ACORDO.</span>
                                </p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">I.2.</span><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;"> A solicitação dos materiais ocorrerá através de PEDIDO DE COMPRA/CONTRATO/ACORDO enviada eletrônica e/ou fisicamente pela CONTRATANTE à CONTRATADA, a qual efetivará os termos e condições específicos da contratação, tais como a razão social da CONTRATANTE, o prazo e o local para entrega de materiais, bem como as demais condições comerciais e técnicas acordadas pelas PARTES.</span>
                                </p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:10pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">I.3</span><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">. Os Materiais objetos do PEDIDO DE COMPRA/CONTRATO/ACORDO deverão ser fornecidos de acordo com as especificações fornecidas pela CONTRATANTE, inclusive as especificações, plantas e desenhos relacionadas no PEDIDO DE COMPRA/CONTRATO/ACORDO.</span>
                                </p>
                                <p dir="ltr"
                                   style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-indent: -14.2pt;text-align: justify;padding:0pt 0pt 0pt 14.2pt;">
                                     </p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:underline;vertical-align:baseline;white-space:pre-wrap;">J - DA NÃO EXCLUSIVIDADE</span><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">: A CONTRATADA declara estar ciente de que a CONTRATANTE não está obrigada a adquirir os materiais listados acima, bem como reconhece que a CONTRATANTE poderá celebrar acordos similares com outros fornecedores, reconhecendo ainda que a contratação somente será efetivada com a formalização dos termos e condições específicas do PEDIDO DE COMPRA/CONTRATO/ACORDO.</span>
                                </p>
                                <p dir="ltr"
                                   style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-indent: -14.2pt;text-align: justify;padding:0pt 0pt 0pt 14.2pt;">
                                     </p>
                                <p dir="ltr" style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:underline;vertical-align:baseline;white-space:pre-wrap;">L – GARANTIA</span><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">: </span><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">Acordam as PARTES que o prazo de garantia para os Materiais adquiridos é de: </span><span
                                        style="vertical-align: baseline;"><span style="text-align: center;">[ANOS_GARANTIA_MATERIAIS]</span> anos</span><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;"> a contar da data da emissão da nota fiscal do respectivo material.</span>
                                </p>
                                <p dir="ltr"
                                   style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-indent: -14.2pt;text-align: justify;padding:0pt 0pt 0pt 14.2pt;">
                                     </p>
                                <p dir="ltr" style="line-height:1.2;margin-top:0pt;margin-bottom:0pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:underline;vertical-align:baseline;white-space:pre-wrap;">M - CONFIDENCIALIDADE</span><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">: As partes se comprometem a manter em sigilo e confidencialidade, e a não divulgar tais Informações a terceiros sem o prévio consentimento por escrito.</span>
                                </p>
                                <p dir="ltr"
                                   style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-indent: -14.2pt;text-align: justify;padding:0pt 0pt 0pt 14.2pt;">
                                     </p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:underline;vertical-align:baseline;white-space:pre-wrap;">N – FORO:</span><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;"> </span><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">Fica eleito o Foro da comarca de Ribeirão Preto – SP, para demandas e procedimentos judiciais oriundos deste Acordo, com renúncia expressa das partes quanto a qualquer outro, por mais privilegiado que seja ou que se torne.</span>
                                </p>
                                <p dir="ltr"
                                   style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-indent: -14.2pt;text-align: justify;padding:0pt 0pt 0pt 14.2pt;">
                                     </p>
                                <p dir="ltr" style="line-height:1.2;margin-top:0pt;margin-bottom:0pt;text-align: justify;"> </p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">Ribeirão Preto/SP, [DIA_ATUAL] de [MES_ATUAL_EXTENSO] de [ANO_ATUAL].</span>
                                </p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-align: justify;"> </p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-align: justify;"> </p>
                                <p dir="ltr"
                                   style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;margin-right: -4.65pt;text-align: justify;">
                                     <span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;"><br
                                        class="kix-line-break"></span></p>
                                <p dir="ltr"
                                   style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;margin-right: -4.65pt;text-align: justify;">
                                    <span style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">________________________</span>
                                </p>
                                <p dir="ltr"
                                   style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;margin-right: -4.65pt;text-align: justify;">
                                    <span style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">CONTRATANTE</span>
                                </p>
                                <p dir="ltr"
                                   style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;margin-right: -4.65pt;text-align: justify;">
                                     </p>
                                <p dir="ltr"
                                   style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;margin-right: -4.65pt;text-align: justify;">
                                    <span style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">________________________</span>
                                </p>
                                <p dir="ltr"
                                   style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;margin-right: -4.65pt;text-align: justify;">
                                    <span style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">CONTRATADA</span>
                                </p>
                                <p dir="ltr"
                                   style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;margin-right: -4.65pt;text-align: justify;">
                                     <span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;"><br
                                        class="kix-line-break"></span></p>
                                <p dir="ltr"
                                   style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;margin-right: -4.65pt;text-align: justify;">
                                    <span style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">________________________</span>
                                </p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">VENDEDOR DA CONTRATADA</span>
                                </p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-align: justify;"> </p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-align: justify;"> </p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">Testemunhas: </span>
                                </p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-align: justify;"> </p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">_______________________</span>
                                </p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">Nome:</span>
                                </p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">RG:</span>
                                </p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">CPF:</span>
                                </p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-align: justify;"> </p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">_______________________</span>
                                </p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">Nome:</span>
                                </p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">RG:</span>
                                </p>
                                <p><span style="font-weight:normal;" id="docs-internal-guid-17773ac7-c21f-a5cf-fe6e-e0dc8d1e3aa6"></span></p>
                                <p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:0pt;text-align: justify;"><span
                                        style="font-size:12pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">CPF</span>
                                </p>
                                </div>',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'campos_extras' => '{"18":{"tag":"[DIAS_PAGAMENTO]","nome":"Dias pagamento","tipo":"numero"},"19":{"tag":"[DIAS_PAGAMENTO_POR_EXTENSO]","nome":"Dias pagamento por extenso","tipo":"texto"},"20":{"tag":"[MESES_VALIDADE_NUMERO]","nome":"Meses validade numero","tipo":"numero"},"21":{"tag":"[MESES_VALIDADE_EXTENSO]","nome":"Meses validade extenso","tipo":"texto"},"22":{"tag":"[ANOS_GARANTIA_MATERIAIS]","nome":"Anos Garantia Materiais","tipo":"texto"}}'
            ]
            ,
            [
                'id' => 1,
                'nome' => 'Contrato de Materiais',
                'tipo' => 'M',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'template' => '<div>
                                    <p class="p1"><span class="s1"><b>CONTRATO DE FORNECIMENTO DE MATERIAL PARA CONSTRUÇÃO CIVIL</b></span></p>
                                    
                                    <p class="p2">
                                        <span class="s1"><b></b></span><br></p>
                                    <p class="p3"><b>A</b>&nbsp;– [RAZAO_SOCIAL_OBRA], pessoa
                                        jurídica de direito privado, com sede na&nbsp; [ENDERECO_OBRA_OBRA] &nbsp;, na cidade de XXXXXXXXXXX, Estado de São
                                        Paulo inscrita no CNPJ sob nº XXXXXXXXXXXXXX com seu contrato social devidamente registrado na junta Comercial do
                                        Estado de São Paulo, neste ato representada na forma de seus atos constitutivos, doravante denominada simplesmente
                                        CONTRATANTE.</p>
                                    <p class="p4"><br></p>
                                    <p class="p3"><b>B</b>&nbsp;– [RAZAO_SOCIAL_FORNECEDOR], pessoa jurídica de
                                        direito privado, com sede na Rua XXXXXXXXXXXX, XXXX, XXXXXXXXXX, CEP XXXXXXXXXXXX, na cidade de XXXXXXXXXXX, Estado
                                        de São Paulo inscrita no CNPJ sob nº XXXXXXXXXXXXXX com seu contrato social devidamente registrado na junta
                                        Comercial do Estado de São Paulo, neste ato representada na forma de seus atos constitutivos por COLOCAR O SÓCIO
                                        ADMINISTRADOR OU PROCURADOR, nacionalidade&nbsp;BRASILEIRA, ESTADO CIVIL, profissão&nbsp;XXXXXXXXXX, inscrito no RG
                                        nº&nbsp;XXXXXXXXXX, inscrito no CPF nº&nbsp;XXXXXXXXXX, domiciliado(s) na&nbsp;RUA XXXXXXXXXXX,&nbsp;nº XXX&nbsp;,&nbsp;BAIRRO,
                                        na cidade de XXXXXXXXX, Estado de&nbsp;SP, CEP&nbsp;XXXXXX&nbsp;, Telefone&nbsp;(1X) XXXX-XXXX&nbsp;, Celular&nbsp;(1X)
                                        XXXXX-XXXX, e-mail&nbsp; xxxxxxxxxxxx@xxxxxxxxxxx, doravante denominada simplesmente CONTRATADA.</p>
                                    <p class="p4">
                                        <br></p>
                                    <p class="p3">B.1 - [VENDEDOR_NOME], [VENDEDOR_EMAIL] E [VENDEDOR_TELEFONE], doravante denominado
                                        simplesmente VENDEDOR DA CONTRATADA.</p>
                                    <p class="p3">&nbsp;.</p>
                                    <p class="p3"><b>C – </b><span class="s1"><b>NATUREZA DO CONTRATO</b></span><b> </b>–
                                        <span class="s2">O presente versa sobre a compra e venda com </span>preço individualizado para cada material abaixo
                                        descrito garantido pelo prazo de duração do presente contrato<span class="s2">, que se regerá pelos termos e cláusulas do presente instrumento.</span>
                                    </p>
                                    <p class="p2"><br></p>
                                    <p class="p3"><b>D – </b><span class="s1"><b>ESPECIFICAÇÕES DOS MATERIAIS CONTRATADOS</b></span></p>
                                    <p class="p4"><span class="s1"><b></b></span><br>
                                    </p>
                                    <p class="p3"><b>D.1 - Descrição dos Materiais:</b></p>
                                    
                                    [TABELA_ITENS_CONTRATO]
                                    
                                    <p class="p3"><b><br></b></p>
                                    <p class="p3"><b>D.1.1. </b>Os preços, expressos em reais, manter-se-ão fixos e
                                        irreajustáveis <span class="s2">durante a vigência deste CONTRATO, salvo disposição em contrário.</span></p>
                                    <p class="p2"><br></p>
                                    <p class="p3"><b>D.2. Da composição do preço do material:</b></p>
                                    <p class="p4"><b><br></b></p><p class="p4"><span style="text-align: center;">[COMPOSICAO_DO_PRECO]&nbsp;</span><b><br></b></p>
                                    <p class="p2"><br></p>
                                    <p class="p2"><b>FRETE</b></p><p class="p2"><span style="text-align: center;">[FRETE_TIPO] &nbsp;-&nbsp;</span><span style="text-align: center;">[FRETE_VALOR]</span><span style="text-align: center;">&nbsp;</span><br></p>
                                    <p class="p2"><br></p>
                                    <p class="p3"><b>E – </b><span class="s1"><b>PREÇO</b>:</span></p>
                                    <p class="p4"><span class="s1"></span><br></p>
                                    <p class="p9"><b>E.1.</b> As quantidades e os preços de cada material são aqueles
                                        delimitados no item “D.1” acima, sendo que as quantidades deverão ser solicitadas por escrito pela CONTRATANTE
                                        através da solicitação de material,que integrar-se-á ao presente instrumento,&nbsp; conforme a necessidade desta,
                                        garantindo a CONTRATADA o preço firmado até o final do presente CONTRATO.<span class="s3">&nbsp;</span></p>
                                    <p class="p10"><br></p>
                                    <p class="p11"><span class="s1"><b><i>F - FORMA E LOCAL DE PAGAMENTO:</i></b></span></p>
                                    <p class="p12"><span class="s1"><b><i></i></b></span><br></p>
                                    <p class="p5"><b>F.1.</b> A CONTRATANTE realizará o
                                        pagamento em&nbsp;<span style="text-align: center;">[QUANTIDADE_DE_DIAS_PAGAMENTO]</span>&nbsp;(<span style="text-align: center;">[QTD_DIAS_PAGAMENTO_POR_EXTENSO]</span>) dias após a emissão da nota fiscal, boleto e entrega do Material;&nbsp;</p>
                                    <p class="p5">
                                        <b>F.2. </b>A Nota Fiscal deve ser entregue em até 03 (três) dias da data de sua emissão. O recebimento após este
                                        prazo implicará automaticamente na prorrogação do prazo de vencimento do citado documento.</p>
                                    <p class="p5">
                                        <b>F.3.</b> A CONTRATANTE deve entregar a Nota Fiscal e boleto no mesmo endereço de entrega do material,
                                        especificado na solicitação de material. Caso o boleto não seja entregue junto com a nota fiscal e o material, a
                                        CONTRATADA deverá prorrogar o vencimento do mesmo, mantendo o prazo de pagamento conforme o item F.1.</p>
                                    <p class="p5"><b>F.4. </b>A não emissão da nota fiscal ou o não cumprimento de TODAS as obrigações assumidas,
                                        inclusive falta de material ou material com defeitos, darão ensejo à retenção do pagamento até o cumprimento das
                                        obrigações, sem que seja configurada a mora da CONTRATANTE.</p>
                                    <p class="p5"><b>F.5</b>. Os itens discriminados na
                                        nota fiscal deverão obedecer os mesmos termos constantes neste contrato, salva disposições em contrário, afim de
                                        agilizar o recebimento.</p>
                                    <p class="p2"><br></p>
                                    <p class="p2"><br></p>
                                    <p class="p5"><span class="s4"><b>G –CONDIÇÕES E </b></span><span class="s1"><b>PRAZO DE ENTREGA DO MATERIAL</b></span><b>:</b></p>
                                    <p class="p2"><b></b><br></p>
                                    <p class="p5">
                                        <b>G.1. </b>O prazo de entrega será de XXXX dias após a solicitação do material feito pela obra, que integrar-se-á
                                        ao presente instrumento.</p>
                                    <p class="p2"><br></p>
                                    <p class="p5"><b>G.2. </b>Os materiais constantes neste contrato
                                        deverão ser entregues na data descrita na solicitação do material, sob pena de imediato cancelamento do mesmo pela
                                        CONTRATANTE, no caso de atraso. Caso ocorra o cancelamento por atraso na entrega, nenhum valor será devido pela
                                        CONTRATANTE, devendo a CONTRATADA cancelar de imediato eventuais notas fiscais emitidas.</p>
                                    <p class="p2"><br></p>
                                    <p class="p9"><b>G.3. </b>Todo(s) o(s) item(ns) fornecido(s) estará(ão) sujeito(s) a exame e aceitação no ato do
                                        recebimento. A compradora, verificando a não conformidade dos produtos a serem entregues, poderá recusar total ou
                                        parcialmente os produtos. Caso ocorra a recusa total nenhuma valor será devido pela COMPRADORA em razão da ORDEM DE
                                        COMPRA. Havendo a recusa parcial, a VENDEDORA deverá cancelar a nota fiscal emitida e emitir nova nota fiscal em que
                                        conste somente os valores dos produtos aceitos.</p>
                                    <p class="p9"><b>G.4. </b>Em caso de entrega pela CONTRATADA de
                                        materiais com vícios, defeitos ou qualquer avaria, a CONTRATANTE poderá suspender o pagamento da nota fiscal até que
                                        haja a substituição do material com defeito, vício ou avaria, sem que seja considerada em mora.</p>
                                    <p class="p9"><b>G.5. </b>A(s)
                                        reposição(ões) do(s) item(ns) recusado(s) ou devolvido(s) serão consideradas como novas entregas, alterando o prazo
                                        de pagamento.</p>
                                    <p class="p9"><b>G.6. </b>Considerar-se-á rescindindo de pleno direito esta ORDEM DE COMPRA, a
                                        critério da COMPRADORA, independentemente de interpelação judicial ou extrajudicial, e sem que caiba à VENDEDORA
                                        qualquer indenização, nos casos previstos em lei e nos casos de a VENDEDORA:&nbsp;</p>
                                    <p class="p9">a) deixar de
                                        entregar os materiais na quantidade, qualidade e prazos estipulados neste contrato e na solicitação;&nbsp;</p>
                                    <p class="p9">b) deixar de repor material avariado, com vício ou defeito, nos termos e prazo estipulados neste
                                        contrato;</p>
                                    <p class="p9">&nbsp;c) emitir contra a COMPRADORA quaisquer títulos de crédito, sob pena de
                                        responsabilização, civil e criminal.</p>
                                    <p class="p13"><br></p>
                                    <p class="p14"><b>G.7. </b>- O atraso injustificado
                                        por mais de 5 (cinco) dias, na entrega do material nos prazos estabelecidos neste contrato, sujeitará a CONTRATADA a
                                        multa moratória de 0,33% (zero vírgula trinta e três por cento) por dia de atraso, do valor do presente contrato
                                        para cada item atrasado, até o limite máximo de 10% (dez por cento) do valor total do item contratado, ressalvadas
                                        as hipóteses de alterações dos prazos de execução expressamente pactuados pelas partes e, sem prejuízo da apuração
                                        de eventuais cobranças de perdas e danos e do direito da CONTRATANTE rescindir o presente contrato por
                                        descumprimento contratual por parte da CONTRATADA.</p>
                                    <p class="p2"><br></p>
                                    <p class="p15"><br></p>
                                    <p class="p16">
                                        <span class="s1"><b>H – VIGÊNCIA DO CONTRATO:&nbsp;</b></span></p>
                                    <p class="p15"><span class="s1"><b></b></span><br>
                                    </p>
                                    <p class="p5"><b>H.1. </b>O presente CONTRATO tem validade por XX (xxxxxxxxxxxxxxx) meses, contados da assinatura do
                                        presente CONTRATO.</p>
                                    <p class="p15"><br></p>
                                    <p class="p5"><span class="s1"><b>I - SOLICITAÇÃO DO MATERIAL:</b></span></p>
                                    <p class="p2"><span class="s1"><b></b></span><br>
                                    </p>
                                    <p class="p5"><b>I.1.</b> A programação de entrega do material descrito neste CONTRATO será enviada para a
                                        CONTRATADA pela obra através de um documento, eletrônico e/ou físico, de solicitação do material, no qual efetivará
                                        os termos e condições específicos da solicitação, tais como as quantidades, o prazo, local para entrega, bem como as
                                        demais condições comerciais e técnicas acordadas pelas PARTES que envolvam a entrega do material.</p>
                                    <p class="p2">
                                        <br></p>
                                    <p class="p5"><b>I.2</b>. Os Materiais objetos deste CONTRATO deverão ser fornecidos de acordo com as
                                        especificações fornecidas pela CONTRATANTE, inclusive as especificações de plantas e desenhos relacionados no
                                        CONTRATO, assim como as normas correspondentes no ANEXO 1.</p>
                                    <p class="p2"><br></p>
                                    <p class="p9"><b>I.3.</b><span class="s5"> </span>A CONTRATADA deve responder o e-mail confirmando o recebimento do
                                        pedido em até 24 horas
                                        após ter recebido o mesmo.</p>
                                    <p class="p9"><b>I.4.</b><span class="s5"> </span>Materiais entregues na obra sem o
                                        prévio agendamento com o responsável poderá ser devolvido sem ônus para a compradora.</p>
                                    <p class="p2"><br></p>
                                    <p class="p5"><span class="s1"><b>J – GARANTIA</b></span><b>: </b>Acordam as PARTES que o prazo de garantia para os
                                        Materiais adquiridos é de: [xx anos] a contar da data da emissão da nota fiscal do respectivo material.</p>
                                    <p class="p15"><br></p>
                                    <p class="p17"><span class="s4"><b>L - CONFIDENCIALIDADE</b></span><span class="s2">: </span>As partes se comprometem a
                                        manter em sigilo e confidencialidade, e a não divulgar tais
                                        Informações a terceiros sem o prévio consentimento por escrito.</p>
                                    <p class="p18"><br></p>
                                    <p class="p5"><span class="s1"><b>M – FORO:</b></span><b> </b>Fica eleito o Foro da comarca de Ribeirão Preto – SP, para
                                        demandas e procedimentos judiciais oriundos deste CONTRATO, com renúncia expressa das partes quanto a qualquer
                                        outro, por mais privilegiado que seja ou que se torne.</p>
                                    <p class="p15"><br></p>
                                    <p class="p2"><br></p>
                                    <p class="p5">Ribeirão Preto/SP, 19 de abril de 2017.</p>
                                    <p class="p2"><br></p>
                                    <p class="p2"><br></p>
                                    <p class="p2">
                                        <br></p>
                                    <p class="p5"><b>________________________</b></p>
                                    <p class="p5"><b>CONTRATANTE</b></p>
                                    <p class="p2">
                                        <b></b><br></p>
                                    <p class="p5"><b>________________________</b></p>
                                    <p class="p5"><b>CONTRATADA</b></p>
                                    <p class="p2">
                                        <b></b><br></p>
                                    <p class="p5"><b>________________________</b></p>
                                    <p class="p5"><b>VENDEDOR DA CONTRATADA</b></p>
                                    <p class="p2"><b></b><br></p>
                                    <p class="p2"><b></b><br></p>
                                    <p class="p5"><b>Testemunhas:&nbsp;</b></p>
                                    <p class="p2">
                                        <b></b><br></p>
                                    <p class="p5">_______________________</p>
                                    <p class="p5">Nome:</p>
                                    <p class="p5">RG:</p>
                                    <p class="p5">
                                        CPF:</p>
                                    <p class="p2"><br></p>
                                    <p class="p5">_______________________</p>
                                    <p class="p5">Nome:</p>
                                    <p class="p5">RG:</p>
                                    <p class="p5">CPF</p>
                                    <p class="p2"><br></p>
                                    <p class="p2"><br></p>
                                    <p class="p2"><br></p>
                                    <p class="p2"><br></p>
                                    <p class="p2"><br></p>
                                    <p class="p2"><br></p>
                                    <p class="p2"><br></p>
                                    <p class="p2"><br></p>
                                    <p class="p2"><br></p>
                                    <p class="p2"><br></p>
                                    <p class="p2"><br></p>
                                    <p class="p2"><br></p>
                                    <p class="p2"><br></p>
                                    <p class="p2"><br></p>
                                    <p class="p3"><b>ANEXO 1– </b><span class="s1"><b>TABELA DE NORMAS DOS MATERIAS CONTROLADOS</b></span></p>
                                    <p class="p4"><span class="s1"><b></b></span><br></p>
                                    <p class="p4"><span class="s1"><b></b></span><br></p>
                                    <p class="p19"><span class="s1"><b></b></span><br></p>
                                    <table cellspacing="0" cellpadding="0" class="t1">
                                        <tbody>
                                        <tr>
                                            <td valign="middle" class="td1">
                                                <p class="p20"><b>MATERIAL</b></p>
                                            </td>
                                            <td valign="middle" class="td1">
                                                <p class="p21"><b>NORMAS NBR’S</b></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="middle" class="td1">
                                                <p class="p22">Blocos de Concreto (estrutural e vedação)</p>
                                            </td>
                                            <td valign="middle" class="td1">
                                                <p class="p23">- NBR 6136 – Blocos vazados de concreto simples para alvenaria.</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="middle" class="td1">
                                                <p class="p22">Cimento, Argamassa, Cal, Gesso em Pó, Rejunte, etc.</p>
                                            </td>
                                            <td valign="middle" class="td1">
                                                <p class="p24">- NBR 5732 para o CP-I; NBR 11578 para o CP-II; NBR 5735 para o CP-III; NBR 5736 para o
                                                    CP-IV; NBR 5733 para o CP-V.</p>
                                                <p class="p24">- NBR 13281 Argamassa utilizada em assentamento e revestimento de paredes e tetos.</p>
                                                <p class="p24">- NBR 7175 - Cal hidratada para argamassas.&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="middle" class="td1">
                                                <p class="p22">Barras, Telas e Fios de Aço</p>
                                            </td>
                                            <td valign="middle" class="td1">
                                                <p class="p24">- NBR 7480 Aço destinado a armaduras para estruturas de concreto armado.</p>
                                                <p class="p24">- NBR 7481 Tela de aço soldada - Armadura para concreto&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="middle" class="td1">
                                                <p class="p22">Areia e Brita</p>
                                            </td>
                                            <td valign="middle" class="td1">
                                                <p class="p24">- NBR 7211 Agregados para concreto</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="middle" class="td1">
                                                <p class="p22">Chapas de Madeira Compensada</p>
                                            </td>
                                            <td valign="middle" class="td1">
                                                <p class="p24">- NBR ISO 1096- madeira compensada;</p>
                                                <p class="p24">- NBR ISO 12466-1-2- madeira compensada – qualidade de colagem;</p>
                                                <p class="p24">- NBR (ISO 2426-1-3- madeira compensada – classificação pela aparência superficial);</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="middle" class="td1">
                                                <p class="p22">Cedrinho, Peroba, Canela, etc.</p>
                                            </td>
                                            <td valign="middle" class="td1">
                                                <p class="p24">-NBR 9487- classificação de madeira serrada de folhosas;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="middle" class="td1">
                                                <p class="p22">Madeiras do tipo Pinus, Eucalipto, etc.</p>
                                            </td>
                                            <td valign="middle" class="td1">
                                                <p class="p24">-NBR 11700- madeira serrada de coníferas provenientes de reflorestamento para uso
                                                    geral-classificação;</p>
                                                <p class="p24">-NBR 12498- madeira serrada de coníferas provenientes de reflorestamento para uso geral;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="middle" class="td1">
                                                <p class="p22">Cubas de Inox</p>
                                            </td>
                                            <td valign="middle" class="td1">
                                                <p class="p24">NBR 6666:1990 - Produtos planos de aço inoxidável - Propriedades mecânicas</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="middle" class="td1">
                                                <p class="p22">Portas e Janelas Metálicas</p>
                                            </td>
                                            <td valign="middle" class="td1">
                                                <p class="p24">NBR 10821:2011 - Esquadrias externas para edificações</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="middle" class="td1">
                                                <p class="p22">Fechaduras e Dobradiças</p>
                                            </td>
                                            <td valign="middle" class="td1">
                                                <p class="p24">NBR 14913:2011 - Fechadura de embutir – Requisitos, classificação e métodos de ensaio</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="middle" class="td1">
                                                <p class="p22">Kits Porta Pronta e Folhas de Porta de Madeira</p>
                                            </td>
                                            <td valign="middle" class="td1">
                                                <p class="p24">- NBR 8542- Desempenho de porta de madeira de edificação</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="middle" class="td1">
                                                <p class="p22">Manta Asfáltica</p>
                                            </td>
                                            <td valign="middle" class="td1">
                                                <p class="p24">- NBR 9952- Manta asfáltica para impermeabilização</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="middle" class="td1">
                                                <p class="p22">Interruptores e Tomadas</p>
                                            </td>
                                            <td valign="middle" class="td1">
                                                <p class="p24">- NBR 60669 2-(1), (2) e (3) Interruptores para instalações fixas domésticas fixas domésticas
                                                    e análogas.</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="middle" class="td1">
                                                <p class="p22">Fios e Cabos</p>
                                            </td>
                                            <td valign="middle" class="td1">
                                                <p class="p24">- NBR 6813 Fios e cabos elétricos - Ensaio de resistência de isolamento - Método de</p>
                                                <p class="p24">ensaio</p>
                                                <p class="p24">- NBR 6814 Fios e cabos elétricos - Ensaio de resistência elétrica - Método do ensaio</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="middle" class="td1">
                                                <p class="p22">Vasos, Caixas Acopladas, Lavatórios em Louça</p>
                                            </td>
                                            <td valign="middle" class="td1">
                                                <p class="p24">- NBR 15097- Aparelhos sanitários de material cerâmico&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="middle" class="td1">
                                                <p class="p22">Bancadas (Granito, Sintética ou similar)</p>
                                            </td>
                                            <td valign="middle" class="td1">
                                                <p class="p24">- NBR 15845-1:2015 - Rochas para revestimento&nbsp;<br>
                                                    Parte 1: Análise petrográfica</p>
                                                <p class="p24">- NBR 15844:2015 - Rochas para revestimento - Requisitos para granitos</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="middle" class="td1">
                                                <p class="p22">Torneiras, ralos e sifões metálicos</p>
                                            </td>
                                            <td valign="middle" class="td1">
                                                <p class="p24">- NBR 10281- torneira de pressão – requisitos e métodos de ensaio;</p>
                                                <p class="p24">- NBR 15705- instalações hidráulicas prediais -registro de gaveta;</p>
                                                <p class="p24">- NBR 12904- válvula de descarga;</p>
                                                <p class="p24">- NBR 11815- Misturador para pia de cozinha tipo parede;</p>
                                                <p class="p24">- NBR 11535- Misturador para pia de cozinha tipo mesa;</p>
                                                <p class="p24">- NBR 15423-Válvulas escoamento - requisitos e métodos de ensaio;</p>
                                                <p class="p24">- NBR 14878- Ligações flexíveis para aparelhos hidráulicos sanitários - requisitos e métodos
                                                    de ensaio;</p>
                                                <p class="p24">- NBR 14390- Misturador para lavatório - requisitos e métodos de ensaio;</p>
                                                <p class="p24">- NBR 14162- Aparelhos sanitários – sifão - requisitos e métodos de ensaio.</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="middle" class="td1">
                                                <p class="p22">Piso e Azulejos Cerâmicos</p>
                                            </td>
                                            <td valign="middle" class="td1">
                                                <p class="p24">- NBR 13818- placas cerâmicas para revestimento, especificação e métodos de ensaio.</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="middle" class="td1">
                                                <p class="p22">Pedra para Piso</p>
                                            </td>
                                            <td valign="middle" class="td1">
                                                <p class="p24">- NBR 7205- placa de mármore natural para revestimentos superficiais verticais externos;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="middle" class="td1">
                                                <p class="p22">Placas de Gesso</p>
                                            </td>
                                            <td valign="middle" class="td1">
                                                <p class="p24">- NBR 12775 - Placas lisas de gesso para forro – Determinação das dimensões e propriedades
                                                    físicas</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="middle" class="td1">
                                                <p class="p22">Porta Corta Fogo</p>
                                            </td>
                                            <td valign="middle" class="td1">
                                                <p class="p24">- NBR 11742:2003&nbsp;- Porta&nbsp;corta-fogo&nbsp;para saída de emergência</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="middle" class="td1">
                                                <p class="p22">Telhas Cerâmicas e de Fibrocimento</p>
                                            </td>
                                            <td valign="middle" class="td1">
                                                <p class="p24">- NBR 7196: 2014&nbsp;&nbsp;- Telhas&nbsp;de fibrocimento - Execução de coberturas e
                                                    fechamentos laterais – Procedimento</p>
                                                <p class="p24">- NBR 15310:2009&nbsp;- Componentes cerâmicos-Telhas&nbsp;- Terminologia, requisitos e
                                                    métodos de ensaio</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="middle" class="td1">
                                                <p class="p22">Tinta, Desmoldante, Impermeabilizante, etc</p>
                                            </td>
                                            <td valign="middle" class="td1">
                                                <p class="p24">- NBR 15079:2011 - Tintas para construção civil - Especificação dos requisitos mínimos de
                                                    desempenho de tintas para edificações não industriais - Tinta látex nas cores claras</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="middle" class="td1">
                                                <p class="p22">Vidros</p>
                                            </td>
                                            <td valign="middle" class="td1">
                                                <p class="p24">- NBR 14697:2001- Vidro&nbsp;laminado</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="middle" class="td1">
                                                <p class="p22">Batentes</p>
                                            </td>
                                            <td valign="middle" class="td1">
                                                <p class="p24">- NBR 15930-1:2011 - Portas de madeira para edificações&nbsp;<br>
                                                    Parte 1: Terminologia e simbologia</p>
                                                <p class="p24">&nbsp;- NBR 15930-2:2011- Portas de madeira para edificações&nbsp;<br>
                                                    Parte 2: Requisitos</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="middle" class="td1">
                                                <p class="p22">Intertravados</p>
                                            </td>
                                            <td valign="middle" class="td1">
                                                <p class="p24">- NBR 15953:2011 - Pavimento intertravado com peças de concreto — Execução</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="middle" class="td1">
                                                <p class="p22">Tubos e Conexões</p>
                                            </td>
                                            <td valign="middle" class="td1">
                                                <p class="p24">- NBR 15345:2013&nbsp;- Instalação predial de tubos e&nbsp;conexões&nbsp;de cobre e ligas de
                                                    cobre –Procedimento</p>
                                                <p class="p24">- NBR 5688:2010 - Tubos e&nbsp;conexões&nbsp;de PVC-U para sistemas prediais de água pluvial,
                                                    esgoto sanitário e ventilação – Requisitos</p>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <p>
                                    
                                    
                                        <style type="text/css">
                                            p.p1 {
                                                margin: 0.0px 0.0px 0.0px 0.0px;
                                                text-align: center;
                                                font: 12.0px Arial
                                            }
                                    
                                            p.p2 {
                                                margin: 0.0px 0.0px 0.0px 0.0px;
                                                text-align: justify;
                                                font: 12.0px Arial;
                                                min-height: 14.0px
                                            }
                                    
                                            p.p3 {
                                                margin: 0.0px 0.0px 0.0px 0.0px;
                                                text-align: justify;
                                                font: 12.0px Arial;
                                                color: #000000
                                            }
                                    
                                            p.p4 {
                                                margin: 0.0px 0.0px 0.0px 0.0px;
                                                text-align: justify;
                                                font: 12.0px Arial;
                                                color: #000000;
                                                min-height: 14.0px
                                            }
                                    
                                            p.p5 {
                                                margin: 0.0px 0.0px 0.0px 0.0px;
                                                text-align: justify;
                                                font: 12.0px Arial
                                            }
                                    
                                            p.p6 {
                                                margin: 0.0px 0.0px 0.0px 0.0px;
                                                text-align: right;
                                                font: 12.0px Arial
                                            }
                                    
                                            p.p7 {
                                                margin: 0.0px 0.0px 0.0px 0.0px;
                                                text-align: right;
                                                font: 12.0px Times
                                            }
                                    
                                            p.p8 {
                                                margin: 0.0px 0.0px 0.0px 0.0px;
                                                text-align: justify;
                                                font: 12.0px Times;
                                                min-height: 14.0px
                                            }
                                    
                                            p.p9 {
                                                margin: 0.0px 0.0px 0.0px 0.0px;
                                                font: 12.0px Arial
                                            }
                                    
                                            p.p10 {
                                                margin: 0.0px 0.0px 0.0px 0.0px;
                                                font: 12.0px Times;
                                                min-height: 14.0px
                                            }
                                    
                                            p.p11 {
                                                margin: 0.0px 0.0px 0.0px 0.0px;
                                                text-align: justify;
                                                font: 12.0px Arial;
                                                color: #00000a
                                            }
                                    
                                            p.p12 {
                                                margin: 0.0px 0.0px 0.0px 0.0px;
                                                text-align: justify;
                                                font: 12.0px Arial;
                                                color: #00000a;
                                                min-height: 14.0px
                                            }
                                    
                                            p.p13 {
                                                margin: 0.0px 0.0px 0.0px 0.0px;
                                                font: 12.0px Arial;
                                                min-height: 14.0px
                                            }
                                    
                                            p.p14 {
                                                margin: 4.0px 0.0px 0.0px 0.0px;
                                                text-align: justify;
                                                font: 12.0px Arial
                                            }
                                    
                                            p.p15 {
                                                margin: 0.0px 0.0px 0.0px 14.2px;
                                                text-align: justify;
                                                font: 12.0px Arial;
                                                min-height: 14.0px
                                            }
                                    
                                            p.p16 {
                                                margin: 0.0px 0.0px 0.0px 14.2px;
                                                text-align: justify;
                                                font: 12.0px Arial
                                            }
                                    
                                            p.p17 {
                                                margin: 0.0px 0.0px 0.0px 0.0px;
                                                font: 12.0px Arial;
                                                color: #000000
                                            }
                                    
                                            p.p18 {
                                                margin: 0.0px 0.0px 0.0px 14.2px;
                                                text-align: justify;
                                                font: 12.0px Arial;
                                                color: #000000;
                                                min-height: 14.0px
                                            }
                                    
                                            p.p19 {
                                                margin: 0.0px 0.0px 0.0px 0.0px;
                                                font: 12.0px Arial;
                                                color: #000000;
                                                min-height: 14.0px
                                            }
                                    
                                            p.p20 {
                                                margin: 0.0px 0.0px 0.0px 0.0px;
                                                text-align: center;
                                                font: 9.0px Times
                                            }
                                    
                                            p.p21 {
                                                margin: 0.0px 0.0px 0.0px 1.0px;
                                                text-align: center;
                                                font: 9.0px Times
                                            }
                                    
                                            p.p22 {
                                                margin: 2.0px 0.0px 2.0px 0.0px;
                                                text-align: center;
                                                font: 8.0px Times
                                            }
                                    
                                            p.p23 {
                                                margin: 2.0px 0.0px 2.0px 1.0px;
                                                font: 8.0px Times;
                                                color: #424242
                                            }
                                    
                                            p.p24 {
                                                margin: 2.0px 0.0px 2.0px 1.0px;
                                                font: 8.0px Times
                                            }
                                    
                                            p.p25 {
                                                margin: 0.0px 0.0px 0.0px 0.0px;
                                                text-align: justify;
                                                font: 8.0px Times;
                                                min-height: 10.0px
                                            }
                                    
                                            span.s1 {
                                                text-decoration: underline
                                            }
                                    
                                            span.s2 {
                                                color: #000000
                                            }
                                    
                                            span.s3 {
                                                font: 12.0px Times
                                            }
                                    
                                            span.s4 {
                                                text-decoration: underline;
                                                color: #000000
                                            }
                                    
                                            span.s5 {
                                                font: 8.0px Times
                                            }
                                    
                                            table.t1 {
                                                border-collapse: collapse
                                            }
                                    
                                            td.td1 {
                                                border-style: solid;
                                                border-width: 1.0px 1.0px 1.0px 1.0px;
                                                border-color: #cbcbcb #cbcbcb #cbcbcb #cbcbcb;
                                                padding: 0.0px 5.0px 0.0px 5.0px
                                            }
                                        </style>
                                    
                                    
                                    </p>
                                    <p class="p25"><br></p>
                                </div>',
                'campos_extras'=> '{"1":{"tag":"[QUANTIDADE_DE_DIAS_PAGAMENTO]","nome":"Quantidade de Dias pagamento","tipo":"numero"},"2":{"tag":"[QTD_DIAS_PAGAMENTO_POR_EXTENSO]","nome":"Qtd dias pagamento por extenso","tipo":"texto"}}'
            ]
        ];

        DB::table('contrato_templates')->insert($items);
        Schema::enableForeignKeyConstraints();

    }
}
