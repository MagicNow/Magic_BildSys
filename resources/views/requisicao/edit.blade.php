@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Requisição
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($requisicao, ['route' => ['requisicao.update', $requisicao->id], 'name' => 'form_insumos' , 'method' => 'patch']) !!}

                       <div class="form-group col-sm-3">
                           {!! Form::label('requisicao', 'Requisição Nro:') !!}
                           <p class="form-control">{!! $requisicao->id !!}</p>
                       </div>

                        <div class="form-group col-sm-3">
                            {!! Form::label('created_at', 'Data Requisição:') !!}
                            <p class="form-control">{{ with(new\Carbon\Carbon($requisicao->created_at))->format('d/m/Y') }}</p>
                        </div>

                        <div class="form-group col-sm-3">
                            {!! Form::label('user_id', 'Solicitante:') !!}
                            <p class="form-control">{!! $requisicao->usuario !!}</p>
                        </div>

                        <div class="form-group col-sm-3">
                           {!! Form::label('status_id', 'Status:') !!}
                            {!! Form::select('status_id',$status, null, ['class' => 'form-control select2']) !!}
                        </div>

                        <div class="form-group col-sm-4">
                            {!! Form::label('obra', 'Obra:') !!}
                           <p class="form-control">{!! $requisicao->obra !!}</p>
                        </div>

                        <div class="form-group col-sm-4">
                           {!! Form::label('local', 'Local:') !!}
                           <p class="form-control">{!! $requisicao->local !!}</p>
                        </div>

                        <div class="form-group col-sm-4">
                           {!! Form::label('torre', 'Torre:') !!}
                           <p class="form-control">{!! $requisicao->torre !!}</p>
                        </div>

                        <div class="form-group col-sm-4">
                           {!! Form::label('pavimento', 'Pavimento:') !!}
                           <p class="form-control">{!! $requisicao->pavimento !!}</p>
                        </div>

                        <div class="form-group col-sm-4">
                           {!! Form::label('trecho', 'Trecho:') !!}
                           <p class="form-control">{!! $requisicao->trecho !!}</p>
                        </div>

                        <div class="form-group col-sm-4">
                           {!! Form::label('andar', 'Andar:') !!}
                           <p class="form-control">{!! $requisicao->andar !!}</p>
                        </div>

                        <input type="hidden" name="obra_id" id="obra_id" value="{!! $requisicao->obra_id !!}">
                        <input type="hidden" name="torre" id="torre" value="{!! $requisicao->torre !!}">
                        <input type="hidden" name="pavimento" id="pavimento" value="{!! $requisicao->pavimento !!}">
                        <input type="hidden" name="andar" id="andar" value="{!! $requisicao->andar !!}">
                        <input type="hidden" name="requisicao_id" id="requisicao_id" value="{!! $requisicao->id !!}">

                       <div class="form-group col-sm-12">

                           <table id="insumos-table" class="table table-striped table-responsive">
                               <thead>
                               <tr align="left">
                                   <th width="15%">Insumo</th>
                                   <th width="12%">Un de Medida</th>
                                   <th width="10%">Previsto</th>
                                   <th width="10%">Disponível</th>
                                   <th width="10%">Em Estoque</th>
                                   <th width="8%">Qtde</th>
                                   <th width="12%">Status</th>
                                   <th width="5%">Detalhes</th>
                                   <th width="7%">QR Code</th>
                               </tr>
                               </thead>

                               <tbody id="body-insumos-table">
                                    {!! $table !!}
                               </tbody>
                           </table>

                           {!! $itens_comodo !!}

                       </div>

                       <!-- Submit Field -->
                       <div class="form-group col-sm-12">
                           {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit', 'id' => 'btn-update-requisicao']) !!}
                           <a href="{!! route('requisicao.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
                       </div>

                   {!! Form::close() !!}


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


                   <!-- Modal -->
                   <div class="modal fade" id="modal-impressao-qrcode-insumos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                       <div class="modal-dialog" role="document">
                           <div class="modal-content">
                               <div class="modal-header">
                                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                   <h4 class="modal-title" id="myModalLabel">Impresão QR Code</h4>
                               </div>
                               <div class="modal-body">

                                   <input type="hidden" id="insumo-comodo-modal" value="">

                               </div>
                               <div class="modal-footer">
                                   <button type="button" class="btn" data-dismiss="modal">Fechar</button>
                               </div>
                           </div>
                       </div>
                   </div>


               </div>
           </div>
       </div>
   </div>
@endsection

@section('scripts')

    <script src="{{ asset('js/requisicao.js') }}"></script>

    <script type="text/javascript">
        $(function () {


        });
    </script>
@endsection