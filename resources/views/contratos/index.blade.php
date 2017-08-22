@extends((!isset($isModal) || !$isModal)? 'layouts.front' : 'layouts.modal')

@section('scripts')
  @parent
  <script src="{{ asset('js/general-filters.js') }}"></script>
@stop

@section('content')
  <section class="content-header">
    <h1>
      Contratos
      <a href="{{ url('/contratos/atualizar-valor') }}" class="btn btn-lg btn-flat btn-info pull-right"> <i class="fa fa-refresh"></i>  Atualizar valores</a>
    </h1>
  </section>
  <div class="content" style="clear: both">
    @if(!isset($isModal) || !$isModal)
    <div class="box box-muted">
      <div class="box-body">
        @include('partials.grupos-de-orcamento')
        <div class="row">
          <div class="col-sm-3">
            <h4>Data</h4>
            @include('partials.filter-date')
          </div>
          <div class="col-sm-5">
            <h4>Fornecedor</h4>
            {!!
              Form::select(
                'fornecedor_id',
                $fornecedores,
                null,
                ['class' => 'form-control select2 js-filter']
              )
            !!}
          </div>
          <div class="col-sm-4">
            <h4>Status</h4>
            {!!
              Form::select(
                'contrato_status_id',
                $status,
                null,
                ['class' => 'form-control select2 js-filter']
              )
            !!}
          </div>
        </div>
      </div>
    </div>
    @endif
    <div class="box box-muted">
      <div class="box-body">
        {!! $dataTable->table(['width' => '100%','class'=>'table table-striped table-hover'],true) !!}
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  @parent
    <script src="/vendor/datatables/buttons.server-side.js"></script>
    <script>
      function selectgrupo(id, change, tipo){
        var rota = "{{url('ordens-de-compra/grupos')}}/";
        if(tipo == 'servicos'){
          rota = "{{url('ordens-de-compra/servicos')}}/";
        }
        if(id){
          $('.box.box-primary').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
          $.ajax({
            url: rota + id,
            data: {
              obra_id: $('#obra_id').val(),
              campo_join: change
            }
          }).done(function(retorno) {
            options = '';
            options = '<option value="">Selecione</option>';
            $('#'+change).html(options);
            $.each(retorno,function(index, value){
              options += '<option value="'+index+'">'+value+'</option>';
            });
            $('#'+change).html(options);
            $('#'+change).attr('disabled',false);

            $('#cadastrar_'+change).css('display', '');
          });
        }else{
          if(change == 'subgrupo1_id'){
            $('#subgrupo1_id').val(null).trigger('change');
            $('#subgrupo2_id').val(null).trigger('change');
            $('#subgrupo3_id').val(null).trigger('change');
            $('#servico_id').val(null).trigger('change');

            $('#subgrupo1_id').attr('disabled',true);
            $('#subgrupo2_id').attr('disabled',true);
            $('#subgrupo3_id').attr('disabled',true);
            $('#servico_id').attr('disabled',true);
          }else if(change == 'subgrupo2_id'){
            $('#subgrupo2_id').val(null).trigger('change');
            $('#subgrupo3_id').val(null).trigger('change');
            $('#servico_id').val(null).trigger('change');

            $('#subgrupo2_id').attr('disabled',true);
            $('#subgrupo3_id').attr('disabled',true);
            $('#servico_id').attr('disabled',true);
          }else if(change == 'subgrupo3_id'){
            $('#subgrupo3_id').val(null).trigger('change');
            $('#servico_id').val(null).trigger('change');

            $('#subgrupo3_id').attr('disabled',true);
            $('#servico_id').attr('disabled',true);
          }else if(change == 'servico_id'){
            $('#servico_id').attr('disabled',true);
          }
        }
      }
      $(function () {
        selectgrupo( $('#grupo_id').val() , 'subgrupo1_id', 'grupos');
      });
    </script>
    {!! $dataTable->scripts() !!}
@endsection

