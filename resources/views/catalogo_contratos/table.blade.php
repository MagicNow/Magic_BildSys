{!! $dataTable->table(['width' => '100%','class'=>'table table-striped table-hover'],true) !!}

@section('scripts')
    <script src="/vendor/datatables/buttons.server-side.js"></script>
    {!! $dataTable->scripts() !!}

    <script type="text/javascript">
        function ativarDesativarCatalogo(id) {
            $.ajax({
                type: 'GET',
                url: 'catalogo-acordos/acao/ativar-desativar',
                data: {
                    id: id
                }
            })
            .done(function() {
                LaravelDataTables.dataTableBuilder.draw();
            })
            .fail(function (retorno) {
                LaravelDataTables.dataTableBuilder.draw();
                swal({
                        title: "Atenção",
                        text: retorno.responseJSON.erro,
                        type: "error",
                        showCancelButton: false,
                        confirmButtonText: "Ok",
                        closeOnConfirm: false
                    },
                    function(){
                        document.location="{{ url('/catalogo-acordos') }}/"+id;
                    }
                );

            });
        }
    </script>
@endsection