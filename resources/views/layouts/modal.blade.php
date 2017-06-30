<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ env('APP_TITLE') }}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    <link rel="stylesheet" href="/css/admin.css">
</head>

<body class="skin-blue-light sidebar-mini loaded">

@include('flash::message')
@yield('content')

<script type="text/javascript">
    window.root = '{{ url("/") }}';
    window.jwt_token = '{{ auth()->check() ? auth()->user()->jwt_token : '' }}';
    var pessoa_atual_id = null;
</script>
<script src="/js/admin.js"></script>

@yield('scripts')
@if(request()->get('bind_form', true))
<script type="text/javascript">
    $(function () {
        $('form').submit(function (event) {
            event.preventDefault();
            $('.box.box-primary').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
            var form = $(this);

            if (form.attr('id') == '' || form.attr('id') != 'fupload') {
                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: form.serialize()
                }).done(function (retorno) {
                    parent.novoObjeto = retorno;
                    setTimeout(function () {
                        eval('parent.'+parent.funcaoPosCreate);
                        parent.$.colorbox.close();
                    }, 10);
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    // Optionally alert the user of an error here...
                    var textResponse = jqXHR.responseText;
                    var alertText = "Confira as mensagens abaixo:\n\n";
                    var jsonResponse = jQuery.parseJSON(textResponse);

                    $.each(jsonResponse, function (n, elem) {
                        alertText = alertText + elem + "\n";
                    });
                    $('.overlay').remove();
                    swal({title: "", text: alertText, type: 'error'});
                });
            }
            else {
                var formData = new FormData(this);
                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: formData,
                    mimeType: "multipart/form-data",
                    contentType: false,
                    cache: false,
                    processData: false
                }).done(function (retorno) {
                    parent.novoObjeto = retorno;
                    setTimeout(function () {
                        eval('parent.'+parent.funcaoPosCreate);
                        parent.$.colorbox.close();
                    }, 10);

                }).fail(function (jqXHR, textStatus, errorThrown) {
                    // Optionally alert the user of an error here...
                    var textResponse = jqXHR.responseText;
                    var alertText = "Confira as mensagens abaixo:\n\n";
                    var jsonResponse = jQuery.parseJSON(textResponse);

                    $.each(jsonResponse, function (n, elem) {
                        alertText = alertText + elem + "\n";
                    });
                    $('.overlay').remove();
                    swal({title: "", text: alertText, type: 'error'});
                });
            }
            ;
        });

        $('.close_popup').click(function () {
            parent.$.colorbox.close();
        });
    });
</script>
@endif
@yield('last-scripts')

</body>
</html>
