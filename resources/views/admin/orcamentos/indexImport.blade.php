@extends('layouts.app')

@section('content')
    @include( 'flash::message' )

    <section class="content-header">
        <h1 class="pull-left">Importar orçamentos</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary">

            {!! Form::open(['route' => 'admin.orcamentos.importar', 'files'=>true]) !!}
            <fieldset style="margin-top:10px; min-width: 1px;">
                <div class="panel-body">
                    <div class="col-md-10">
                        <div class="form-group">
                            {!! Form::label('obra_id', 'Obra:') !!}
                            {!! Form::select('obra_id', [''=>'Escolha...' ]+$obras, null, ['class' => 'form-control', 'required'=>'required']) !!}
                        </div>
                        <div id="tipo_orcamento" class="form-group">
                            {!! Form::label('orcamento_tipo_id', 'Tipo Orçamento:') !!}
                            {!! Form::select('orcamento_tipo_id', ['' => 'Escolha...']+$orcamento_tipos, null, ['class' => 'form-control', 'required'=>'required']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('template_id', 'Template:') !!}
                            {!! Form::select('template_id', ['' => 'Escolha...']+$templates, null, ['class' => 'form-control']) !!}
                        </div>
                        <h4>Apenas planilhas no formato <strong class="label label-warning"> .csv </strong></h4></br>
                        <h5 style="color:#000000">Planilha modelo: <strong class="label label-warning"><a href="/orcamentos.csv" style="color:white" target="_blank">clique aqui</a> </strong></h5>
                        <div class="row" style="margin-bottom: 20px">
                            <div class="col-sm-6 {{ $errors->has('file') ? 'has-error' : '' }}">
                                {!! Form::file('file', array('id' => 'file', 'class' => 'form-control', 'required'=>'required')) !!}
                                <span class="help-block">{{ $errors->first('file', ':message') }}</span>
                            </div>
                            <div class="form-group col-sm-12">
                                {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
                                <a href="{!! url('/admin') !!}" class="btn btn-danger"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
//        function selectType($valor){
//            if($valor == 1){
//                $('#tipo_orcamento').css('display', '');
//            }else {
//                $('#tipo_orcamento').css('display', 'none');
//            }
//        }
    </script>
@endsection