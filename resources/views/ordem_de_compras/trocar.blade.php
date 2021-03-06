@extends('layouts.front')

@section('content')
  <section class="content-header">
    <div class="modal-header">
      <div class="col-md-12">
          <span class="pull-left title">
             <h3>
                 <button type="button" class="btn btn-link" onclick="history.go(-1);">
                      <i class="fa fa-arrow-left" aria-hidden="true"></i>
                 </button>
                 <span>Trocar insumo</span>
             </h3>
          </span>
      </div>
    </div>
  </section>
  <div class="content">
    <div class="box box-muted">
      <div class="box-header with-border">
        {{ $orcamento->insumo->nome }}
      </div>
      <div class="box-body">
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              {!! Form::label('insumo_id', 'Insumo') !!}
              {!!
                Form::select(
                  'insumo_id',
                  [],
                  null,
                  [
                    'class'    => 'form-control',
                    'id'       => 'insumo_id',
                    'required' => 'required'
                  ]
                )
              !!}
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              {!! Form::label('qtd_total', 'Quantidade') !!}
              {!!
                Form::text(
                  'qtd_total',
                  null,
                  [
                    'class'    => 'form-control money',
                    'required' => 'required',
                    'id' => 'qtd_total'
                  ]
                )
              !!}
            </div>
          </div>
        </div>
        <div class="form-group">
          <button class="btn btn-info btn-flat" id="add-to-list" disabled>
            Adicionar na Lista de Troca
          </button>
        </div>
        <div id="lista-de-troca" class="hidden">
          {!! Form::open(['route' => ['compras.trocar', $orcamento->id], 'id' => 'form-troca']) !!}
            <h4>Lista de Troca</h4>
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Código</th>
                  <th>Nome</th>
                  <th>Unidade</th>
                  <th>Quantidade</th>
                  <th>#</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
            <input type="hidden" name="back" value="{{ request('back', url()->previous()) }}">
            <div class="pull-right">
              <a href="{{ request('back', url()->previous()) }}" class="btn btn-danger" id="cancel">
                Cancelar
              </a>
              <button class="btn btn-success" id="save">
                Realizar Troca
              </button>
            </div>
          <input type="hidden" id="insumos_trocados">
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script>
    var array_insumos = [];

    $(function() {
      var $insumoSelect          = $('#insumo_id');
      var $quantidadeInput       = $('#qtd_total');
      var $addToListBtn          = $('#add-to-list');
      var $listaDeTrocaContainer = $('#lista-de-troca');
      var $listTableBody         = $listaDeTrocaContainer.find('tbody').first();
      var $saveBtn               = $('#save');
      var $cancelBtn             = $('#cancel');
      var $formTroca             = $('#form-troca');

      $listaDeTrocaContainer.on('click', '.js-remove-row', function(event) {
        event.preventDefault();
        event.stopPropagation();
        $(event.currentTarget).parents('tr').remove();

        if(!$listTableBody.children().length) {
          $listaDeTrocaContainer.addClass('hidden');
        }
      });

      $insumoSelect.select2({
        allowClear: true,
        placeholder: "Escolha...",
        language: "pt-BR",
        theme: 'bootstrap',
        ajax: {
          url: "{{ route('catalogo_contratos.busca_insumos') }}",
          dataType: 'json',
          delay: 250,

          data: function (params) {
            return {
              q: params.term,
              page: params.page00,
              insumos_trocados: $('#insumos_trocados').val()
            };
          },

          processResults: function (result, params) {
            params.page = params.page || 1;

            return {
              results: result.data,
              pagination: {
                more: (params.page * result.per_page) < result.total
              }
            };
          },
          cache: true
        },
        escapeMarkup: function (markup) {
          return markup;
        },
        minimumInputLength: 1,
        templateResult: formatResult,
        templateSelection: formatResultSelection
      });

      function formatResultSelection (obj) {
        if(obj.nome) {
          return obj.nome;
        }

        return obj.text;
      }

      function formatResult (obj) {
        if (obj.loading) return obj.text;

        var markup_insumo =    "<div class='select2-result-obj clearfix'>" +
          "   <div class='select2-result-obj__meta'>" +
          "       <div class='select2-result-obj__title'>" + obj.nome + "</div>"+
          "   </div>"+
          "</div>";

        return markup_insumo;
      }

      $insumoSelect.on('change', function() {
        $addToListBtn.prop('disabled', !canAddInsumo());
      });

      $quantidadeInput.on('change input keypress', function(event) {
        $addToListBtn.prop('disabled', !canAddInsumo());
        if(event.which === 13) {
          $addToListBtn.click();
        }
      });


      var clicks = 0;
      $addToListBtn.on('click', function(event) {
        startLoading();
        var insumo_id = $insumoSelect.val();
        var qtd_total = moneyToFloat($quantidadeInput.val());

        // Adiciona os insumos trocados no array
        array_insumos.push(insumo_id);

        $('#insumos_trocados').val(array_insumos);

        $.get('/insumos/' + insumo_id + '/json')
          .done(function(insumo) {
            $listaDeTrocaContainer.removeClass('hidden');
            $listTableBody.append(
              '<tr>' +
                '<td>' + insumo.codigo + '</td>' +
                '<td>' + insumo.nome + '</td>' +
                '<td>' + insumo.unidade_sigla + '</td>' +
                '<td>' + $quantidadeInput.val() + '</td>' +
                '<td>' +
                  '<input type="hidden" name="data[' + clicks + '][insumo_id]" value="' + insumo_id + '">' +
                  '<input type="hidden" name="data[' + clicks + '][qtd_total]" value="' + qtd_total + '">' +
                  '<button class="js-remove-row btn btn-sm btn-flat btn-danger" onclick="removeInsumoArray(' + insumo_id + ')">' +
                    '<i class="fa fa-trash"></i> Remover' +
                  '</button>' +
                '</td>' +
              '</tr>'
            );
            clicks++;
            clearInputs();
          })
          .fail(function() {
            swal('Ops!', 'Ocorreu um erro ao adicionar insumo na lista.', "error");
          })
          .always(stopLoading())
      });

      $saveBtn.on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        swal({
          type: 'warning',
          title: 'Realizar troca?',
          text: 'Ao confirmar a troca não será possível voltar atrás',
          showCancelButton: true,
          confirmButtonText: 'Realizar Troca',
          confirmButtonColor: '#7ED32C',
          cancelButtonText: 'Não',
          showLoaderOnConfirm: true,
          closeOnConfirm: false
        }, function() {
          $formTroca.submit();
        });
      });

      $cancelBtn.on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        swal({
          type: 'warning',
          title: 'Cancelar?',
          text: 'Ao cancelar a troca você perderá os dados que selecionou',
          showCancelButton: true,
          confirmButtonText: 'Sim, cancelar!',
          confirmButtonColor: "#DD6B55",
          cancelButtonText: 'Não, voltar ao formulário',
          showLoaderOnConfirm: true,
          closeOnConfirm: false
        }, function() {
          location.href = event.currentTarget.href;
        });
      });

      var canAddInsumo = function() {
        return !!$insumoSelect.val() && !!$quantidadeInput.val().length
      };

      var clearInputs = function() {
        $insumoSelect.val('').trigger('change');
        $quantidadeInput.val('');
      };

    });

    // Percorre o array de insumos trocados procurando o insumo removido e exclui do array
    function removeInsumoArray(insumo_id)
    {
      for (var i = 0; i < array_insumos.length; i++) {
        if (array_insumos[i] == insumo_id) {
          array_insumos.splice(i, 1);
          break;
        }
      }

      $('#insumos_trocados').val(array_insumos);
    }
  </script>
@stop
