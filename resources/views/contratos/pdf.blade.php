@extends('layouts.printable')

@section('content')
    <style>
        /*thead, tfoot { display: table-row-group !important; }*/
        /*html{*/
            /*zoom: 0.9;*/
        /*}*/
    </style>
    <section>
        <div class="row">
            <div class="col-xs-2 form-group">
                {!! Form::label('id', 'Número do Contrato') !!}
                <p class="form-control input-lg highlight text-center">{!! $contrato->id !!}</p>
            </div>

            <div class="col-xs-4 form-group">
                {!! Form::label('obra', 'Obra') !!}
                <p class="form-control input-lg">{!! $contrato->obra->nome !!}</p>
            </div>
            <div class="col-xs-2 form-group">
                {!! Form::label('created_at', 'Data de Criação') !!}
                <p class="form-control input-lg">{!! $contrato->created_at->format('d/m/Y') !!}</p>
            </div>
            <div class="col-xs-4 form-group">
                {!! Form::label('user_id', 'Responsável') !!}
                <p class="form-control input-lg">
                    {!!
                        $contrato->quadroDeConcorrencia->user_id
                            ? $contrato->quadroDeConcorrencia->user->name
                            : 'Contrato Automático'
                        !!}
                </p>
            </div>
        </div>
    </section>
    <section>
        <div class="row">
            <div class="col-xs-4 form-group">
                <label>Nome</label>
                <p class="form-control input-lg text-limit highlight text-center"
                   title="{!! $contrato->fornecedor->nome !!}">
                    {!! $contrato->fornecedor->nome !!}
                </p>
            </div>
            <div class="col-xs-4 form-group">
                <label>CNPJ</label>
                <p class="form-control input-lg">
                    {!! $contrato->fornecedor->cnpj  !!}
                </p>
            </div>
            <div class="col-xs-4 form-group">
                <label>Telefone</label>
                <p class="form-control input-lg">
                    {!! $contrato->fornecedor->telefone ?: '<span class="text-danger">Sem telefone</span>'  !!}
                </p>
            </div>
            {{--<div class="col-xs-3 form-group">--}}
                {{--<label>Email</label>--}}
                {{--<p class="form-control input-lg text-limit"--}}
                   {{--title="{{ $contrato->fornecedor->email ?: 'Sem email'  }}">--}}
                    {{--{!! $contrato->fornecedor->email ?: '<span class="text-danger">Sem email</span>' !!}--}}
                {{--</p>--}}
            {{--</div>--}}
        </div>
    </section>
    @include('contratos.table')
@stop