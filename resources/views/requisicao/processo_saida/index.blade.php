@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Processo de saída
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    <div class="form-group col-md-6">
                        {!! Form::label('requisicao', 'Requisição:') !!}
                        <p class="form-control">{!! $requisicao->id !!}</p>
                    </div>

                    <div class="form-group col-md-6">
                        {!! Form::label('solicitante', 'Solicitante:') !!}
                        <p class="form-control">{!! $requisicao->user->name !!}</p>
                    </div>

                    <div class="col-md-4"></div>
                    <div class="btn-group-vertical col-md-4" style="display: block;">
                        <a href="/requisicao/processo-saida/{{$requisicao->id}}/ler-insumo-saida" class="btn btn-success" style="margin-bottom: 10px;">
                            <i class="fa fa-qrcode" aria-hidden="true"></i>
                            Ler QrCodes Saída
                        </a>

                        <a href="#" class="btn btn-info" style="margin-bottom: 10px;">
                            <i class="fa fa-list" aria-hidden="true"></i>
                            Lista de Inconsistências
                        </a>

                        <a onclick="localRequisicaoModal();" class="btn btn-primary" style="margin-bottom: 10px;">
                            <i class="fa fa-location-arrow" aria-hidden="true"></i>
                            Local da Requisição
                        </a>

                        <a href="#" class="btn btn-warning" style="margin-bottom: 10px;">
                            <i class="fa fa-sign-out" aria-hidden="true"></i>
                            Finalizar Saída
                        </a>
                    </div>
                    <div class="col-md-4"></div>

                    <div class="col-md-12">
                        <a href="{!! route('requisicao.index') !!}" class="btn btn-default">
                            <i class="fa fa-arrow-left"></i>  {{ ucfirst( trans('common.back') )}}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function localRequisicaoModal() {
            swal({
                title: 'Local da Requisição',
                text: '<div style="text-align: left;" class="col-md-12">'+
                    '<p><b>Local:</b> {{$requisicao->local}}</p>'+
                    '<p><b>Torre:</b> {{$requisicao->torre}}</p>'+
                    '<p><b>Pavimento:</b> {{$requisicao->pavimento}}</p>'+
                    '<p><b>Trecho:</b> {{$requisicao->trecho}}</p>'+
                    '<p><b>Andar:</b> {{$requisicao->andar}}</p></div>',
                html: true,
                confirmButtonColor: "#DD6B55"
            });
        }
    </script>
@endsection