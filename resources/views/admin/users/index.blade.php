@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Usuários</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('users.create') !!}">
               {{ ucfirst( trans('common.new') )}}
           </a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    <!--Data inicial -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('initial_date', 'Data inicial') !!}
                        {!! Form::date('initial_date', date('Y-m-d'), ['class' => 'form-control']) !!}
                    </div>

                    <!--Data final -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('final_date', 'Data final') !!}
                        {!! Form::date('final_date', date('Y-m-d', strtotime('+1 week', strtotime(date('Y-m-d')))), ['class' => 'form-control']) !!}
                    </div>
                    @include('admin.users.table')
            </div>
        </div>
    </div>
@endsection