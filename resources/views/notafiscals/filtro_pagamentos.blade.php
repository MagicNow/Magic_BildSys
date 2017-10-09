@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Conciliação de Pagamentos da Nota fiscal
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <h3>
                            <div style="width: 150px;float:left;">Nota N.o: </div>
                            <label class="label label-info">{{ $nota->codigo }}</label>
                        </h3>
                        <h3>
                            <div style="width: 150px;float:left;">Fornecedor: </div>
                            <label class="label label-info">{{ $nota->contrato->fornecedor->nome }} [Cnpj: {{ $nota->contrato->fornecedor->cnpj }}]</label>
                        </h3>
                        <h3>
                            <div style="width: 150px;float:left;">Contrato: </div>
                            <a href="{{ url(route("contratos.show",[$contrato->id])) }}">
                                <label class="label label-info">{{ $nota->contrato_id }}</label>
                            </a>
                        </h3>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="pull-right">
                            <a href="{{ url(route("pagamentos.create")) }}?contrato_id={{ $contrato->id }}&nota_id={{ $nota->id }}" class="btn btn-info">
                                <i class="fa fa-plus"></i> Adicionar Pagamento
                            </a>
                        </div>
                    </div>
                </div>

                @if(count($pagamentos) > 0)
                <div class="row">
                    <div class="col-sm-12">
                        <br/>
                        <table class="table table-striped table-condensed">
                            <tr>
                                <th>
                                    <input type="checkbox" id="selecionar_todos">
                                    <label for="selecionar_todos">Selecionar todos</label>
                                </th>
                                <th>ID</th>
                                <th>Data Emissão</th>
                                <th>Valor</th>
                            </tr>
                            @foreach($pagamentos as $pagamento)
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            @endforeach
                        </table>

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="pull-right">
                            <a href="#" class="btn btn-success">
                                <i class="fa fa-plus"></i> Vincular Pagamentos Selecionados
                            </a>
                        </div>
                    </div>
                </div>
                @else
                    <div class="row">
                        <div class="col-sm-12">
                            Não foram encontrados pagamentos antecipados para esta Nota.
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>

    </script>
@endsection
