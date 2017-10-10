@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Gestão de estoque
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <table id="fixTable" class="table table-bordered table-striped table-condensed table-nowrap">
                    <thead>
                    <tr align="center">
                        <th colspan="8" style="background-color: white;"></th>
                        <th colspan="3" style="text-align: center;">Em adamento</th>
                        <th colspan="2" style="background-color: white;"></th>
                        <th colspan="4" style="text-align: center;">Perda</th>
                        <th colspan="1" style="background-color: white;"></th>
                        <th colspan="5" style="text-align: center;">Contrato</th>
                    </tr>
                    <tr align="left">
                        <th>Obra</th>
                        <th>Local</th>
                        <th>Cód</th>
                        <th>Insumo</th>
                        <th>Un de medida</th>
                        <th>Em estoque</th>
                        <th>Previsto</th>
                        <th>Aplicado</th>

                        <th nowrap>Solicitado</th>
                        <th nowrap>Em preparo</th>
                        <th nowrap>Em Trânsito</th>

                        <th nowrap>Tranferência</th>
                        <th nowrap>Empréstimo</th>

                        <th nowrap>Prevista</th>
                        <th nowrap>Real / Projeção</th>
                        <th nowrap>Concluído</th>
                        <th nowrap>Farol</th>

                        <th nowrap>Evolução Física</th>

                        <th nowrap>Contratado</th>
                        <th nowrap>Realizado</th>
                        <th nowrap>Saldo</th>
                        <th nowrap>Projeção de término</th>
                        <th nowrap>Ajustes</th>
                    </tr>
                    </thead>

                    <tbody>
                        <tr align="left">
                            <td>Obra</td>
                            <td>Local</td>
                            <td>Cód</td>
                            <td>Insumo</td>
                            <td>Un de medida</td>
                            <td>Em estoque</td>
                            <td>Previsto</td>
                            <td>Aplicado</td>

                            <td nowrap>Solicitado</td>
                            <td nowrap>Em preparo</td>
                            <td nowrap>Em Trânsito</td>

                            <td nowrap>Tranferência</td>
                            <td nowrap>Empréstimo</td>

                            <td nowrap>Prevista</td>
                            <td nowrap>Real / Projeção</td>
                            <td nowrap>Concluído</td>
                            <td nowrap>Farol</td>

                            <td nowrap>Evolução Física</td>

                            <td nowrap>Contratado</td>
                            <td nowrap>Realizado</td>
                            <td nowrap>Saldo</td>
                            <td nowrap>Projeção de término</td>
                            <td nowrap>Ajustes</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            $("#fixTable").tableHeadFixer({'left' : 8, 'head' : true});
        });
    </script>
@endsection