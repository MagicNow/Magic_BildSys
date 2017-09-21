@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Pagamento
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($pagamento, ['route' => ['pagamentos.update', $pagamento->id], 'method' => 'patch']) !!}

                        @include('pagamentos.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection