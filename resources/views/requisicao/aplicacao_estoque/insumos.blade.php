<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ env('APP_TITLE') }}</title>
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">
    <link rel="icon" href="{{ asset('img/favicon.png') }}" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="QR Code Scanner is the fastest and most user-friendly web application.">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="{{ env('APP_TITLE') }}">
    <meta name="apple-mobile-web-app-status-bar-style" content="#e4e4e4">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="application-name" content="{{ env('APP_TITLE') }}">
    <meta name="msapplication-TileColor" content="#e4e4e4">
    <meta name="msapplication-TileImage" content="/images/touch/mstile-150x150.png">
    <meta name="theme-color" content="#fff">
    <link rel="apple-touch-icon" href="/qrcode/qrcodereader/images/touch/apple-touch-icon.jpg">
    <link rel="icon" type="image/png" href="/qrcode/qrcodereader//images/touch/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/qrcode/qrcodereader//images/touch/favicon-16x16.png" sizes="16x16">
    <link rel="shortcut icon" href="/qrcode/qrcodereader//images/touch/favicon.ico">
    <link rel="manifest" href="/qrcode/qrcodereader//manifest.json">
    <link href="/qrcode/qrcodereader/bundle.css" rel="stylesheet">

    <link rel="stylesheet" href="/css/admin.css">
    <script type="text/javascript" src="/js/admin.js"></script>
</head>
<body>

<div class="app__layout"><!-- Header -->
    <main class="app__layout-content">
        <video autoplay></video><!-- Dialog  -->
        <div class="app__dialog app__dialog--hide" style="display: none;">
            <div class="app__dialog-content"><h5>QR Code</h5><input type="text" id="result"></div>
            <div class="app__dialog-actions">
                <button type="button" class="app__dialog-open">Open</button>
                <button type="button" class="app__dialog-close">Close</button>
            </div>
        </div>
        <div class="app__dialog-overlay app__dialog--hide" style="display: none;"></div><!-- Snackbar -->
        <div class="app__snackbar"></div>
    </main>
</div>
<div class="app__overlay">
    <div class="app__overlay-frame"></div><!-- Scanner animation -->
    <div class="custom-scanner"></div>
    <div class="app__help-text">Aponte sua câmera para um QR Code</div>
    <div class="app__select-photos">Select from photos</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalDadoQrCode" tabindex="-1" role="dialog" aria-labelledby="modalDadoQrCodeLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDadoQrCodeLabel">
                    <p style="text-align: center;">
                        <b>
                            Leitor QRCode<br>
                            Aplicação de Insumos da Requisição
                        </b>
                    </p>
                </h5>
            </div>
            <div class="modal-body">
                <span id="dados_qr_code">

                </span>
            </div>
            <div class="modal-footer">
                <div class="btn-group btn-group-justified">
                    <a href="/requisicao/aplicacao-estoque/local" class="btn btn-danger">Cancelar</a>
                    <a href="#" class="btn btn-success" onclick="salvarLeitura();">Confirmar</a>
                </div>

                <div class="btn-group btn-group-justified" style="margin-top: 10px;">
                    <a href="#" class="btn btn-primary" onclick="salvarLeitura();" data-dismiss="modal">Confirmar e ler o próximo</a>
                </div>

                <div class="btn-group btn-group-justified" style="margin-top: 10px;">
                    <a href="#" class="btn btn-warning" onclick="" data-dismiss="modal">Finalizar leitura da aplicação</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var nome_funcao_executar = 'lerLocal';

    function lerLocal(dados_qr_code) {
        $('#dados_qr_code').html(dados_qr_code);
        $('#modalDadoQrCode').modal('show');
        $('.app__dialog-close').click();
    }

    function salvarLeitura() {
        var dados_qr_code = $('#dados_qr_code').text();
        var existe_parametro = dados_qr_code.indexOf('Dados QR Code: ');

        if (existe_parametro > -1) {
            var dados = dados_qr_code.split('Dados QR Code: ')[dados_qr_code.split('Dados QR Code:').length -1];
            if(dados) {
                $.ajax('{{ route('requisicao.salvarLeituraAplicacaoInsumo') }}', {
                    data: {
                        dados: dados
                    }
                }).done(function(retorno) {
                    if(retorno.sucesso) {
                        swal({
                            title: 'QR Code lido com sucesso',
                            text: "",
                            type: "success",
                            confirmButtonColor: "#DD6B55"
                        });
                    } else {
                        swal({
                            title: 'QR Code Inválido',
                            text: retorno.erros,
                            type: "info",
                            html: true,
                            confirmButtonColor: "#DD6B55"
                        });
                    }
                }).fail(function() {
                    swal({
                        title: 'QR Code Inválido',
                        text: "Este QR Code não possuí os dados necessários.",
                        type: "info",
                        confirmButtonColor: "#DD6B55"
                    });
                });
            }
        } else {
            swal({
                title: 'QR Code Inválido',
                text: "Não existe parâmetro para leituta do QR Code.",
                type: "info",
                confirmButtonColor: "#DD6B55"
            });
        }
    }
</script>

<script type="text/javascript" src="/qrcode/qrcodereader/bundle.js"></script>

</body>
</html>