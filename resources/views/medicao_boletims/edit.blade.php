@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Medicao Boletim
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($medicaoBoletim, ['route' => ['boletim-medicao.update', $medicaoBoletim->id], 'method' => 'patch']) !!}

                        @include('medicao_boletims.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection