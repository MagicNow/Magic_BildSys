@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Q.C.
        </h1>
    </section>
    <div class="content">
        <div class="box">
            <div class="box-body">
                <div class="row">
                    @include('qc.show_fields')
                </div>
                <a href="{!! route('qc.index') !!}" class="btn btn-default">
                   <i class="fa fa-arrow-left"></i>  {{ ucfirst( trans('common.back') )}}
                </a>
            </div>
        </div>
    </div>
@endsection
