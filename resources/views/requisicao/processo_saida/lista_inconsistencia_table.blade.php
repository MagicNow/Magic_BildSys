{!! $dataTable->table(['width' => '100%','class'=>'table table-striped table-hover'],true) !!}

@section('scripts')
    <script src="/vendor/datatables/buttons.server-side.js"></script>
    {!! $dataTable->scripts() !!}

    <script>
        function excluirLeitura(requisicao_id) {

            swal({
                title: "{{ ucfirst( trans('common.are-you-sure') ) }}?",
                text: "{{ ucfirst( trans('common.you-will-not-be-able-to-recover-this-registry') ) }}!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "{{ ucfirst( trans('common.yes') ) }}, {{ ucfirst( trans('common.delete') ) }}!",
                cancelButtonText: "{{ ucfirst( trans('common.cancel') ) }}"
            }, function () {
                $.ajax('{{ route('requisicao.excluirLeitura') }}', {
                    data: {
                        requisicao_id: requisicao_id
                    }
                }).done(function () {
                    window.LaravelDataTables["dataTableBuilder"].draw();
                });
            });
        }
    </script>
@endsection