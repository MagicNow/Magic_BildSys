@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Pagamento {{ $pagamento->id . ' - Contrato '.$pagamento->contrato->id }}
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body forp">
                <div class="row" style="padding-left: 20px">
                    @include('pagamentos.show_fields')
                    <button type="button" onclick="history.go(-1);" class="btn btn-warning">
                       <i class="fa fa-arrow-left"></i>  {{ ucfirst( trans('common.back') )}}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
