@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Insumos que não tem Máscara Padrão associada
		</h1>        
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.mascara_padrao_insumos.table')
            </div>
        </div>
    </div>
@endsection

