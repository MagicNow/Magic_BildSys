@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Máscara Padrão - {{(isset($mascaraPadrao) ? $mascaraPadrao->nome : $mascaraPadraoEstrutura->nome)}}
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        {!! Form::open(['route' => 'admin.mascara_padrao_insumos.store', 'files' => true]) !!}
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    <div class="col-md-12">
                        {!! Form::label('mascara_padrao_estrutura_id', 'Máscara Padrão Estrutura:') !!}
                        {!! Form::select('mascara_padrao_estrutura_id', ['' => 'Escolha...']+$selectMascaraPadraoEstruturas, (isset($mascaraPadraoEstrutura) ? $mascaraPadraoEstrutura->id : null),
                            ['class' => 'form-control select2'])
                        !!}
                    </div>
                </div>
                <div class="content" style="padding-left: 20px">
                    <div class="col-md-12">
                        @include('admin.mascara_padrao_estruturas.table')
                    </div>

                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 15px">
            <div class="col-md-12 text-right">
                <a type="button" href="#"
                   class="btn btn-default btn-flat btn-lg"><i class="fa fa-times"></i>
                    Cancelar
                </a>
                <button type="submit" class="btn btn-flat btn-lg btn-success">Salvar</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">

    </script>
@endsection
