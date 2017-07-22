@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Medicao Boletim
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('medicao_boletims.show_fields')
                    <a href="{!! route('boletim-medicao.index') !!}" class="btn btn-default">
                       <i class="fa fa-arrow-left"></i>  {{ ucfirst( trans('common.back') )}}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
