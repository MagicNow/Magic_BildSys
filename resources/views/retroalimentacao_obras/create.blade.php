@extends('layouts.front')

@section('content')
    {!! Form::open(['route' => 'retroalimentacaoObras.store']) !!}
    <section class="content-header">
        <div class="modal-header">
            <div class="col-md-12">
                <div class="col-md-9">
               <span class="pull-left title">
                   <button type="button" onclick="history.go(-1);">
                       <i class="fa fa-arrow-left" aria-hidden="true"></i>
                   </button>

                   <span>Cadastrar Retroalimentação</span>
               </span>
                </div>

                <div class="col-md-3">
                    <button type="button" onclick="history.go(-1);" class="btn btn-default btn-lg btn-flat" data-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-success btn-lg btn-flat" data-dismiss="modal">
                        Concluir
                    </button>
                </div>
            </div>
        </div>
    </section>

    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="row">

            <div class="col-md-8 col-xs-12 col-lg-7">

                @include('retroalimentacao_obras.fields')

            </div>
        </div>

    </div>
    {!! Form::close() !!}
@endsection
