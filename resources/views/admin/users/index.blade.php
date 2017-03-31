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
                <div class="form-group col-md-5">
                    {!! Form::label('initial_date', 'Data inicial') !!}
                    {!! Form::date('initial_date', session()->get('initial_date') ? session()->get('initial_date') : date('Y-m-d'), ['class' => 'form-control', 'onchange' => 'putSession()']) !!}
                </div>

                <!--Data final -->
                <div class="form-group col-md-5">
                    {!! Form::label('final_date', 'Data final') !!}
                    {!! Form::date('final_date', session()->get('final_date')? session()->get('final_date') : date('Y-m-d', strtotime('+1 week', strtotime(date('Y-m-d')))), ['class' => 'form-control', 'onchange' => 'putSession()']) !!}
                </div>

                <!--Data final -->
                <div class="form-group col-md-2" style="margin-top: 25px;">
                    <button type="button" class="btn btn-info pull-right" data-toggle="modal" data-target="#myModal">
                        Adicionar filtros
                    </button>
                </div>

                <div id="block_fields"></div>

                <!-- Modal -->
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Adicionar filtros</h4>
                            </div>
                            <div class="modal-body">
                                @foreach($filters as $field => $filter)
                                    <p>
                                        <input class="cb_filter" type="checkbox" value="{{$field}}" checked/>
                                        <label style="cursor: pointer;" class="cb_filter_label">
                                            {{$filter}}
                                        </label>
                                    </p>
                                @endforeach
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="addFilters()">Adicionar</button>
                            </div>
                        </div>
                    </div>
                </div>
                @include('admin.users.table')
            </div>
        </div>
    </div>
@endsection