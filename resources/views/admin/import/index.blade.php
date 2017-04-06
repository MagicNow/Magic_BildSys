@extends('layouts.app')

@section('content')
    @if(!isset($retorno))
    <section class="content-header">
        <h1 class="pull-left">Importação de planilhas</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        {!! Form::open(['route' => 'admin.import.importar', 'files'=>true]) !!}

        <fieldset style="margin-top:10px;">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-md-10">
                        <div class="form-group">
                            {!! Form::label('obra_id', 'Obra:') !!}
                            {!! Form::select('obra_id', [''=>'Escolha' ]+$obras, null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('modulo_id', 'Módulo:') !!}
                            {!! Form::select('modulo_id', ['' => 'Escolha', 1=>'Orçamentos'], null, ['class' => 'form-control', 'onchange' => 'selectType(this.value)']) !!}
                        </div>
                        <div id="tipo_orcamento" class="form-group" style="display:none">
                            {!! Form::label('orcamento_tipo_id', 'Tipo Orçamento:') !!}
                            {!! Form::select('orcamento_tipo_id', ['' => 'Escolha']+$orcamento_tipos, null, ['class' => 'form-control']) !!}
                        </div>
                        <label><h5 style="color:#ff9800">Apenas planilhas no formato <strong class="label label-info"> .csv </strong></h5></label>
                        <div class="row" style="margin-bottom: 20px">
                            <div class="col-sm-6 {{ $errors->has('file') ? 'has-error' : '' }}">
                                {!! Form::file('file', array('id' => 'file', 'class' => 'form-control', 'required'=>'required')) !!}
                                <span class="help-block">{{ $errors->first('file', ':message') }}</span>
                            </div>
                            <div class="form-group col-sm-12 text-right">
                                <a href="{{URL::to('/')}}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
                                {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-warning', 'type'=>'submit']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>

        {!! Form::close() !!}
    </div>
    @else
        @if($retorno['error'] > 0)
        <div class="panel panel-danger">
            <div class="panel-heading"><h3 class="panel-title">Erros de importação</h3></div>
            <div class="panel-body"></div>
            <div class="">
                <table class="table table-bordered table-striped table-hover table-condensed">
                    <tbody>
                    @foreach($retorno['error'] as $row)
                        <tr>
                            <td>{{ $row }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <!-- fazer download de planilha com erro -->
                <div class="row">
                    <a href="/admin/import" class="btn btn-sm  btn-primary btn-block">
                        <span class="glyphicon glyphicon-save"></span>
                        Voltar e tentar importar novamente
                    </a>
                </div>
            </div>
        </div>
        @else
            <div>
                <p class="bg-success">Dados importados com sucesso!</p>
            </div>
        @endif
    @endif
@endsection
@section('scripts')
    <script type="text/javascript">
        function selectType($valor){
            if($valor == 1){
                $('#tipo_orcamento').css('display', '');
            }else {
                $('#tipo_orcamento').css('display', 'none');
            }
        }
    </script>
@endsection