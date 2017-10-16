<div class="form-group col-sm-3">
    {!! Form::label('requisicao', 'Requisição Nro:') !!}
    <p class="form-control">{!! $requisicao->id !!}</p>
</div>

<div class="form-group col-sm-3">
    {!! Form::label('created_at', 'Data Requisição:') !!}
    <p class="form-control">{{ with(new\Carbon\Carbon($requisicao->created_at))->format('d/m/Y') }}</p>
</div>

<div class="form-group col-sm-3">
    {!! Form::label('user_id', 'Solicitante:') !!}
    <p class="form-control">{!! $requisicao->usuario !!}</p>
</div>

<div class="form-group col-sm-3">
    {!! Form::label('status_id', 'Status:') !!}
    <p class="form-control">{!! $requisicao->status !!}</p>
</div>


<div class="form-group col-sm-4">
    {!! Form::label('obra', 'Obra:') !!}
    <p class="form-control">{!! $requisicao->obra !!}</p>
</div>

<div class="form-group col-sm-4">
    {!! Form::label('local', 'Local:') !!}
    <p class="form-control">{!! $requisicao->local !!}</p>
</div>

<div class="form-group col-sm-4">
    {!! Form::label('torre', 'Torre:') !!}
    <p class="form-control">{!! $requisicao->torre !!}</p>
</div>

<div class="form-group col-sm-4">
    {!! Form::label('pavimento', 'Pavimento:') !!}
    <p class="form-control">{!! $requisicao->pavimento !!}</p>
</div>

<div class="form-group col-sm-4">
    {!! Form::label('trecho', 'Trecho:') !!}
    <p class="form-control">{!! $requisicao->trecho !!}</p>
</div>

<div class="form-group col-sm-4">
    {!! Form::label('andar', 'Andar:') !!}
    <p class="form-control">{!! $requisicao->andar !!}</p>
</div>


<div class="col-sm-12">
    <table id="insumos-table" class="table table-striped table-responsive">
        <thead>
        <tr align="left">
            <th width="15%">Insumo</th>
            <th width="12%">Un de Medida</th>
            <th width="10%">Previsto</th>
            <th width="10%">Disponível</th>
            <th width="10%">Em Estoque</th>
            <th width="8%">Qtde</th>
            <th width="12%">Status</th>
        </tr>
        </thead>

        <tbody id="body-insumos-table">
        {!! $table !!}
        </tbody>
</table>
</div>


