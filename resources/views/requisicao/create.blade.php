@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Requisições
            <a class="btn btn-success"  href="{{ url('requisicao/ler-qr-cod') }}">
                <i class="fa fa-qrcode" aria-hidden="true"></i> Ler QR Code
            </a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'requisicao.store', 'name' => 'form_insumos']) !!}

                    <!-- Obra Id Field -->
                        <div class="form-group col-sm-6">
                            {!! Form::label('obra_id', 'Obra:') !!}
                            {!! Form::select('obra_id',[''=>'Selecione...'] + $obras, null, ['class' => 'form-control select2']) !!}

                        </div>

                        <div class="form-group col-sm-6">
                            {!! Form::label('local', 'Local:') !!}
                            {!! Form::select('local',[''=>'Selecione','torre' => 'Torre', 'canteiro' => 'Canteiro', 'escritorio' => 'Escritório'], null, ['class' => 'form-control select2']) !!}
                        </div>

                        <div class="form-group col-sm-6">
                            {!! Form::label('torre', 'Torre:') !!}
                            {!! Form::select('torre', ['Selecione uma Obra'], null, ['class' => 'form-control select2']) !!}
                        </div>

                        <div class="form-group col-sm-6">
                            {!! Form::label('pavimento', 'Pavimento:') !!}
                            {!! Form::select('pavimento', ['Selecione uma Torre'], null, ['class' => 'form-control select2']) !!}
                        </div>

                        <div class="form-group col-sm-6">
                            {!! Form::label('trecho', 'Trecho:') !!}
                            {!! Form::select('trecho', ['Selecione um Pavimento'], null, ['class' => 'form-control select2']) !!}
                        </div>

                        <div class="form-group col-sm-6">
                            {!! Form::label('andar', 'Andar:') !!}
                            {!! Form::select('andar', ['Selecione um Pavimento'], null, ['class' => 'form-control select2']) !!}
                        </div>

                        <div class="form-group col-sm-12 text-center">
                            <button type="button" class="btn btn-primary hide" id="js-btn-buscar-insumos">
                                Buscar Insumos
                            </button>
                        </div>

                        <div id="hidden-fields"></div>

                        <div class="form-group col-sm-12">

                            <table id="insumos-table" class="table table-striped table-responsive hide">
                                <thead>
                                <tr align="left">
                                    <th width="30%">Insumo</th>
                                    <th width="12%">Un de Medida</th>
                                    <th width="12%">Previsto</th>
                                    <th width="12%">Disponível</th>
                                    <th width="12%">Em Estoque</th>
                                    <th width="18%">Qtde</th>
                                    <th>Detalhes</th>
                                </tr>
                                </thead>

                                <tbody id="body-insumos-table">

                                </tbody>
                            </table>

                        </div>


                        <!-- Modal -->
                        <div class="modal fade" id="modal-insumos-comodo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Insumos por Cômodo</h4>
                                    </div>
                                    <div class="modal-body">

                                        <input type="hidden" id="insumo-comodo-modal" value="">

                                        <table id="insumos-comodo-table" class="table table-striped table-responsive">
                                            <thead>
                                            <tr align="left">
                                                <th width="30%">Apartamento</th>
                                                <th width="12%">Cômodo</th>
                                                <th width="12%">Disponível</th>
                                                <th width="18%">Qtde</th>
                                            </tr>
                                            </thead>

                                            <tbody id="body-insumos-comodo-table">

                                            </tbody>
                                        </table>


                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Salvar</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Submit Field -->
                        <div class="form-group col-sm-12">
                            {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right hide', 'type'=>'submit', 'id' => 'btn-create-requisicao']) !!}
                            <a href="{!! route('requisicao.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
                        </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/requisicao.js') }}"></script>

    <script type="text/javascript">
        $(function () {

            @if(\Illuminate\Support\Facades\Input::get('local'))
                changeLocal();
            @endif


        function changeLocal() {
            setTimeout(function() {
                $('#local').trigger('change');

                @if(\Illuminate\Support\Facades\Input::get('torre'))
                    changeTorre();
                @endif
            }, 500);
        }

        function changeTorre() {
            setTimeout(function() {
                $('#torre').val('{{\Illuminate\Support\Facades\Input::get('torre')}}').trigger('change.select2').trigger('change');

                @if(\Illuminate\Support\Facades\Input::get('pavimento'))
                    changePavimento();
                @endif
            }, 500);
        }

        function changePavimento() {
            setTimeout(function() {
                $('#pavimento').val('{{\Illuminate\Support\Facades\Input::get('pavimento')}}').trigger('change.select2').trigger('change');

                @if(\Illuminate\Support\Facades\Input::get('trecho'))
                    changeTrecho();
                @elseif(\Illuminate\Support\Facades\Input::get('andar'))
                    changeAndar();
                @endif
            }, 500);
        }

        function changeTrecho() {
            setTimeout(function() {
                $('#trecho').val('{{\Illuminate\Support\Facades\Input::get('trecho')}}').trigger('change.select2').trigger('change');

                $('#js-btn-buscar-insumos').trigger("click");
            }, 500);
        }

        function changeAndar() {
            setTimeout(function() {
                $('#andar').val('{{\Illuminate\Support\Facades\Input::get('andar')}}').trigger('change.select2').trigger('change');
            }, 500);

            $('#js-btn-buscar-insumos').trigger("click");
        }
    });
    </script>
@endsection

