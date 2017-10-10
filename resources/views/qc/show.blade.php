@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Q.C.
            @include('qc.aprovacao')
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
    <div class="hidden">
        {!! Form::select('motivo', $motivos, null, ['id' => 'motivo']) !!}
    </div>
@endsection

@section('scripts')
    @parent
    <script> options_motivos = document.getElementById('motivo').innerHTML; </script>
@append
