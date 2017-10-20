@extends('layouts.front')

@section('content')
    <section class="content-header ">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>

            Memória de cálculo
        </h1>
   </section>
   <div class="content memoria">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($memoriaCalculo, ['route' => ['memoriaCalculos.update', $memoriaCalculo->id], 'method' => 'patch']) !!}

                        @include('memoria_calculos.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection