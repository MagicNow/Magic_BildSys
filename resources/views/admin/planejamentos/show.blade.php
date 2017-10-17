@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h3>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            <span>{{$planejamento->tarefa}}</span>
        </h3>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('admin.planejamentos.show_fields')
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <a href="{!! request()->get('carteira_avulsa')? route('admin.planejamentos.atividade-carteiras') : route('admin.planejamentos.index') !!}"
                           class="btn btn-danger"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
