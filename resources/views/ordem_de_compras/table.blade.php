{!! $dataTable->table(['width' => '100%','class'=>'table table-striped table-hover'],true) !!}

@section('scripts')
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    {!! $dataTable->scripts() !!}

    <script type="text/javascript">
        function reabrir(oc, obra) {
            $.ajax({
                url: "/ordens-de-compra/reabrir-ordem-de-compra/verificar/" + oc + "/" + obra,
                type: "GET"
            }).done(function(retorno) {
                if(retorno.success){
                    swal({
                        title: "Você já possuí uma Ordem de Compra aberta para esta obra! <br> OC: " + retorno.oc_aberta,
                        text: "Deseja unificar as Ordens de compra?",
                        type: "warning",
                        html: true,
                        showCancelButton: true,
                        cancelButtonText: "Não",
                        confirmButtonText: "Sim, unificar as OC",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function(isConfirm){
                        if (isConfirm) {
                            $.ajax({
                                url: "/ordens-de-compra/unificar-ordem-de-compra/" + retorno.oc_aberta + "/" + oc,
                                type: "GET"
                            }).done(function (retorno) {
                                if (retorno.success) {
                                    startLoading();
                                    window.location = "/ordens-de-compra/carrinho?id=" + oc;
                                }
                            });
                        } else {
                            swal("Para reabrir esta OC, é necessário concluir a OC "+retorno.oc_aberta, "", "warning");
                        }
                    });
                }else{
                    location.reload();
                }
            });
        }
    </script>
@endsection
