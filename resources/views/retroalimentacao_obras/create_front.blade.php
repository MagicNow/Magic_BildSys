@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Retroalimentacao Obra
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'retroalimentacaoObras.store']) !!}

                    <!-- Obra Id Field -->
                        {!! Form::hidden('origem', url()->previous() ) !!}
                        <div class="form-group col-sm-6">
                            {!! Form::label('obra_id', 'Obra:') !!}
                            {!! Form::select('obra_id',[''=>'Escolha...']+$obras, null, ['class' => 'form-control', 'required'=>'required']) !!}
                        </div>
                    <!-- Categoria Field -->
                        <div class="form-group col-sm-6">
                            {!! Form::label('categoria', 'Categoria:') !!}
                            {!! Form::select(
                                'categoria',
                                array(
                                    'Escolha'=>'Escolha...',
                                    'Quantidade'=>'Quantidade',
                                    'Escopo'=>'Escopo',
                                    'Consumo'=>'Consumo',
                                    'Máscara'=>'Máscara',
                                    'Projeto'=>'Projeto',
                                    'Orçamento'=>'Orçamento',
                                    'Procedimento'=>'Procedimento'
                                ),
                                'Escolha',
                                ['class' => 'form-control input-md']
                            ) !!}
                        </div>

                        <!-- Origem Field -->
                        <div class="form-group col-sm-6">
                            {!! Form::label('situacao_atual', 'Situação Atual:') !!}
                            {!! Form::textarea('situacao_atual', null, ['class' => 'form-control', 'rows' => '3']) !!}
                        </div>

                        <!-- Origem Field -->
                        <div class="form-group col-sm-6">
                            {!! Form::label('situacao_proposta', 'Situação Proposta:') !!}
                            {!! Form::textarea('situacao_proposta', null, ['class' => 'form-control', 'rows' => '3']) !!}
                        </div>

                        <!-- Submit Field -->
                        <div class="form-group col-sm-12">
                            {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right flat', 'type'=>'submit']) !!}
                            <a href="javascript:history.go(-1);" class="btn btn-default flat"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
                        </div>

                        {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
