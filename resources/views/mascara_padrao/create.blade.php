@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Máscara Padrão
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
				
				<div class="row">
					@include('partials.grupos-de-orcamento')				
				</div>
				
				<div class="row">
                    {!! Form::open(['route' => 'mascara_padrao.store', 'files' => true]) !!}
                        @include('mascara_padrao.fields')
                    {!! Form::close() !!}
                </div>				
                
            </div>
        </div>
    </div>
@endsection
