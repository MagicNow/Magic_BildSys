@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Nota fiscal
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'notafiscals.store', 'files' => true]) !!}

                        @include('notafiscals.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
{{--@section('scripts')--}}
    {{--<script type="text/javascript">--}}
        {{--var qtditens = {{ isset($tipoEqualizacaoTecnica)?$qtdItens:'0' }};--}}

        {{--function addNfItens(){--}}
            {{--nfItens = 'TESTE';--}}
            {{--$('#teste').html(nfItens);--}}
        {{--}--}}
        {{----}}
        {{--function readURL(input) {--}}
            {{--startLoading();--}}
            {{--if (input.files && input.files[0]) {--}}
                {{--var view = new FileReader();--}}
                {{--view.onload = function (e) {--}}
{{--//                    window.open(e.target.result);--}}
                    {{--$('#arquivoNfe')--}}
                            {{--.attr('src', e.target.result)--}}
                            {{--.width(620)--}}
                            {{--.height(700);--}}
                {{--};--}}
                {{--view.readAsDataURL(input.files[0]);--}}
            {{--}--}}
            {{--stopLoading();--}}
            {{--$('#arquivo_nfe').val($('#arquivoNfe').val());--}}
        {{--}--}}
    {{--</script>--}}
{{--@stop--}}
