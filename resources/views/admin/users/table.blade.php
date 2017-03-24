{!! $dataTable->table(['width' => '100%','class'=>'table table-striped table-hover'], true) !!}

@section('scripts')
    {!! $dataTable->scripts() !!}
    <script type="text/javascript">
        var oTable = null;

        $('#initial_date').bind('change', function () {
            putSession();
        });

        $('#final_date').bind('change', function () {
            putSession();
        });

        function putSession() {
            var initial_date = $('#initial_date').val();
            var final_date = $('#final_date').val();
            $.ajax({
                url: "/admin/putsession",
                data: {
                    initial_date: initial_date,
                    final_date: final_date
                }
            }).done(function (json) {
                if(json.success){
                    $("#dataTableBuilder").DataTable().draw();
                }
            });
        }

        function addFilters() {
            console.log('Filters');
        }
    </script>
@endsection