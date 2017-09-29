<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>QR Code Scanner</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="QR Code Scanner is the fastest and most user-friendly web application.">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="QR Scanner">
    <meta name="apple-mobile-web-app-status-bar-style" content="#e4e4e4">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="application-name" content="QR Scanner">
    <meta name="msapplication-TileColor" content="#e4e4e4">
    <meta name="msapplication-TileImage" content="/images/touch/mstile-150x150.png">
    <meta name="theme-color" content="#fff">
    <link rel="apple-touch-icon" href="/qrcode/qrcodereader/images/touch/apple-touch-icon.jpg">
    <link rel="icon" type="image/png" href="/qrcode/qrcodereader//images/touch/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/qrcode/qrcodereader//images/touch/favicon-16x16.png" sizes="16x16">
    <link rel="shortcut icon" href="/qrcode/qrcodereader//images/touch/favicon.ico">
    <link rel="manifest" href="/qrcode/qrcodereader//manifest.json">
    <link href="/qrcode/qrcodereader/bundle.css" rel="stylesheet">

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
    <div class="app__help-text">Point your camera at a QR Code</div>
    <div class="app__select-photos">Select from photos</div>
</div>
<script>
    var nome_funcao_executar = 'lerQrCodeRequisicao';

    function lerQrCodeRequisicao(dados_qr_code) {
        var dados = dados_qr_code.split('Dados QR Code:')[dados_qr_code.split('Dados QR Code:').length -1];
        if(dados) {
            window.location = '{!! route('requisicao.create') !!}' + dados;
        }
    }
</script>

<script type="text/javascript" src="/qrcode/qrcodereader/bundle.js"></script>

</body>
</html>