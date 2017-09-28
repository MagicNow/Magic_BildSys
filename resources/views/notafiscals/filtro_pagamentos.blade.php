@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Conciliação de Pagamentos da Nota fiscal: {{ $nota->codigo }}<br/>
            Fornecedor: {{ $nota->contrato->fornecedor->nome }}<br/>
            Contrato: {{ $nota->contrato_id }}<br/>
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="pull-right">
                            <a href="{{ url(route("pagamentos.create")) }}?contrato={{ $contrato->id }}&nota={{ $nota->id }}" class="btn btn-info">
                                <i class="fa fa-plus"></i> Adicionar Pagamento
                            </a>

                        </div>
                    </div>
                </div>

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


            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>

    </script>
@endsection