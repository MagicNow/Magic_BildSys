@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Solicitação de insumo
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($solicitacaoInsumo, ['route' => ['admin.solicitacaoInsumos.update', $solicitacaoInsumo->id], 'method' => 'patch']) !!}

                        @include('admin.solicitacao_insumos.fields')
                        <!-- Submit Field -->
                       <div class="form-group col-sm-12">
                           {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
                           <a href="{!! route('admin.solicitacaoInsumos.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
                       </div>
                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection