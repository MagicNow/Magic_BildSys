@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Importar planejamentos</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary">
            @include('flash::message')
            {!! Form::open(['route' => 'admin.planejamento.importar', 'files'=>true]) !!}
            <fieldset style="margin-top:10px; min-width: 1px;">
                <div class="panel-body">
                    <div class="col-md-10">
                        <div class="form-group">
                            {!! Form::label('obra_id', 'Obra:') !!}
                            {!! Form::select('obra_id', [''=>'Escolha' ]+$obras, null, ['class' => 'form-control', 'required'=>'required']) !!}
                        </div>
                        <label><h5 style="color:#ff9800">Apenas planilhas no formato <strong class="label label-info"> .xlsx </strong></h5></label>
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
            </fieldset>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
    </script>
@endsection