@extends('layouts.front')

@section('content')
    <section class="content-header clearfix">
        <h1 class="pull-left">
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Lista de Carteiras por Obras
        </h1>

        <h1 class="pull-right">
           <a class="btn btn-primary" href="{!! route('qc.create') !!}">
               Criar Q.C.
           </a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    <div class="col-sm-4 form-group">
                        {!! Form::label('obra_id', 'Obras') !!}
                        {!!
                            Form::select(
                                'obra_id',
                                $obras,
                                null,
                                [
                                    'class' => 'js-filter select2',
                                    'placeholder' => 'Filtrar por obra...'
                                ]
                            )
                        !!}
                    </div>
                    <div class="col-sm-4 form-group">
                        {!! Form::label('carteira_id', 'Carteiras') !!}
                        {!!
                            Form::select(
                                'carteira_id',
                                $carteiras,
                                null,
                                [
                                    'class' => 'js-filter select2',
                                    'placeholder' => 'Filtrar por carteira...'
                                ]
                            )
                        !!}
                    </div>
                  <div class="col-sm-4 form-group">
                    {!! Form::label('', 'Período') !!}
                    @include('partials.filter-date', [ 'no_buttons' => true ])
                  </div>
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-body">
                @include('admin.qc_avulso_carteiras.table')
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/general-filters.js')  }}"></script>
@append
