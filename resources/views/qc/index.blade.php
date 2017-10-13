@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Lista de Q.C.</h1>
        <h1 class="pull-right">
            <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('qc.create') !!}">
                {{ ucfirst( trans('common.new') )}}
            </a>
        </h1>
    </section>
    <div class="clearfix"></div>
    <div class="col-sm-12">
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3 form-group">
                        {!! Form::label('obra_id', 'Obras') !!}
                        {!!
                            Form::select(
                                'obra_id',
                                $obras,
                                null,
                                [
                                    'class' => 'js-filter select2',
                                ]
                            )
                        !!}
                    </div>
                    <div class="col-sm-3 form-group">
                        {!! Form::label('carteira_id', 'Carteiras') !!}
                        {!!
                            Form::select(
                                'carteira_id',
                                [],
                                null,
                                [
                                    'class' => 'js-filter select2'
                                ]
                            )
                        !!}
                    </div>
                    <div class="col-sm-3 form-group">
                        {!! Form::label('carteira_id', 'Responsável pela negociação') !!}
                        {!!
                            Form::select(
                                'carteira_id',
                                [],
                                null,
                                [
                                    'class' => 'js-filter select2'
                                ]
                            )
                        !!}
                    </div>
                    <div class="col-sm-3 form-group">
                        {!! Form::label('fornecedor_id', 'Fornecedor') !!}
                        {!!
                            Form::select(
                                'fornecedor_id',
                                [],
                                null,
                                [
                                    'class' => 'js-filter select2'
                                ]
                            )
                        !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3 form-group">
                        {!! Form::label('acompanhamento', 'Acompanhamento') !!}
                        {!!
                            Form::select(
                                'acompanhamento',
                                [
                                    '' => 'Fitrar por acompanhamento...',
                                    0 => 'No prazo',
                                    2 => 'Atrasado',
                                    3 => 'Fechado no prazo',
                                    4 => 'Fechado atrasado',
                                ],
                                null,
                                [
                                    'class' => 'js-filter select2'
                                ]
                            )
                        !!}
                    </div>
                    <div class="col-sm-3 form-group">
                        {!! Form::label('etapa', 'Etapa') !!}
                        {!!
                            Form::select(
                                'etapa',
                                [],
                                null,
                                [
                                    'class' => 'js-filter select2'
                                ]
                            )
                        !!}
                    </div>
                    <div class="col-sm-3 form-group">
                        {!! Form::label('qc_status_id', 'Status') !!}
                        {!!
                            Form::select(
                                'qc_status_id',
                                $status,
                                $defaultStatus,
                                [
                                    'class' => 'js-filter select2'
                                ]
                            )
                        !!}
                    </div>
                    <div class="col-sm-3 form-group">
                        {!! Form::label('tipologia_id', 'Tipologia') !!}
                        {!!
                            Form::select(
                                'tipologia_id',
                                $tipologias,
                                null,
                                [
                                    'class' => 'js-filter select2'
                                ]
                            )
                        !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @include('qc.table')
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/general-filters.js') }}"></script>
    <script>
        $(function() {
            select2('.js-filter[name=fornecedor_id]', {
                url: '/buscar/fornecedores',
                placeholder: 'Filtrar por fornecedor...'
            })
        });
    </script>
@append
