@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Requisições
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'requisicao.store']) !!}

                        @include('requisicao.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(function () {

            $('#responsive-example-table,#responsive-example-tablee').stacktable();

            $(document).on('change','#local', function(e) {

                e.preventDefault();

                var obra = $('#obra_id');
                var local = $('#local');
                var torre = $('#torre');

                torre.empty();

                if (obra.val() != '' && local.val() == 'torre') {

                    $.ajax({

                        url: '/obras/torre/'+obra.val(),
                        dataType: 'JSON',
                        cache: false,
                        type: "GET"

                    }).done(function (response) {

                        if (response.success) {

                            $.each(response.torres , function(i, val) {

                                torre.append('<option value="'+response.torres[i]['id']+'">'+response.torres[i]['nome']+'</option>');
                            });
                        }
                    })

                }
            })


        })
    </script>
@endsection
