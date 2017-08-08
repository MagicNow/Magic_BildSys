{!! $dataTable->table(['width' => '100%','class'=>'table table-striped table-hover'],true) !!}

@section('scripts')
    <script src="/vendor/datatables/buttons.server-side.js"></script>
    {!! $dataTable->scripts() !!}

    <script type="application/javascript">
        function incluirPrazo(lembrete_tipo_id, insumo_grupo_id, dias_prazo_minimo, nome) {
            $.ajax({
                url: "/admin/lembretes/data-minima",
                data: {
                    'lembrete_tipo_id' : lembrete_tipo_id,
                    'insumo_grupo_id' : insumo_grupo_id,
                    'dias_prazo_minimo' : dias_prazo_minimo,
                    'nome' : nome
                }
            }).done(function(data) {

            });
        }
    </script>
@endsection