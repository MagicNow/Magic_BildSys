{!! $dataTable->table(['width' => '100%','class'=>'table table-striped table-hover'], true) !!}

@section('scripts')
    {!! $dataTable->scripts() !!}
    <script type="text/javascript">
        var oTable = null;

        $('#initial_date').bind('change', function () {
            $("#dataTableBuilder").DataTable().draw();
        });

        $('#final_date').bind('change', function () {
            $("#dataTableBuilder").DataTable().draw();
        });
    </script>
@endsection