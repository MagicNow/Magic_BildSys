@extends('layouts.front')

@section('content')
<div class="content-header">
    <h1 class="content-header-title">
        Solicitação de Entrega
    </h1>
</div>
<div class="content">
    <div class="row">
    <div class="col-sm-6">
        <div class="box box-muted">
            <div class="box-body">
                <table class="table table-condensed table-striped">
                    <tr>
                        <th>Contrato Selecionado</th>
                        <td>#{{ $contrato->id }}</td>
                    </tr>
                    <tr>
                        <th>Fornecedor</th>
                        <td>{{ $contrato->fornecedor->nome }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection
