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
            var cb_filter = $('.cb_filter');
            var cb_filter_label = $('.cb_filter_label');
            var filters = [];
            for( i=0; i < cb_filter.length; i++ ) {
                if(cb_filter[i].value.split(':')[1] == 'string'){
                    $('#block_fields').append('\
                        <div class="row form-group col-md-12">\
                            <div class="col-md-6">\
                                <label>'+cb_filter_label[i].innerHTML+'</label>\
                                <input type="text" name="filters['+cb_filter[i].value.split(':')[0]+']" class="form-control">\
                            </div>\
                            <div class="col-md-6" style="margin-top: 25px;">\
                                <select class="form-control">\
                                    <option value="between">Entre</option>\
                                    <option value="start">Começa com</option>\
                                    <option value="end">Termina com</option>\
                                </select>\
                            </div>\
                        </div>\
                    ');
                }
            }
        }
    </script>
@endsection