@extends('layouts.front')

@section('content')
    <section class="content-header">
        <div class="modal-header">
            <div class="col-md-12">
                <div class="col-md-9">
                    <span class="pull-left title">
                       <h3>
                           <button type="button" class="btn btn-link" onclick="history.go(-1);">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                           </button>
                           <span>QR Code</span>
                       </h3>
                    </span>
                </div>
            </div>
        </div>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        @include('adminlte-templates::common.errors')

        <div class="app__layout"><!-- Header -->
            <main class="app__layout-content">
                <video autoplay></video><!-- Dialog  -->
                <div class="app__dialog app__dialog--hide">
                    <div class="app__dialog-content"><h5>QR Code</h5><input type="text" id="result"></div>
                    <div class="app__dialog-actions">
                        <button type="button" class="app__dialog-open">Abrir</button>
                        <button type="button" class="app__dialog-close">Fechar</button>
                    </div>
                </div>
                <div class="app__dialog-overlay app__dialog--hide"></div><!-- Snackbar -->
                <div class="app__snackbar"></div>
            </main>
        </div>
        <div class="app__overlay">
            <div class="app__overlay-frame"></div><!-- Scanner animation -->
            <div class="custom-scanner"></div>
            <div class="app__help-text">Aponte sua câmera para um código QR Code</div>
            <div class="app__select-photos">Selecionar fotos</div>
        </div>
    </div>
@endsection

@section('scripts')

    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="QR Scanner">
    <meta name="apple-mobile-web-app-status-bar-style" content="#e4e4e4">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="application-name" content="QR Scanner">
    <meta name="msapplication-TileColor" content="#e4e4e4">
    <meta name="msapplication-TileImage" content="/images/touch/mstile-150x150.png">
    <meta name="theme-color" content="#fff">
    <link rel="apple-touch-icon" href="/qrcodelib/images/touch/apple-touch-icon.jpg">
    <link rel="icon" type="image/png" href="/qrcodelib/images/touch/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/qrcodelib/images/touch/favicon-16x16.png" sizes="16x16">
    <link rel="shortcut icon" href="/qrcodelib/images/touch/favicon.ico">
    <link rel="manifest" href="/qrcodelib/manifest.json">
    <link href="/qrcodelib/bundle.css" rel="stylesheet">


    <script type="text/javascript" src="/qrcodelib/bundle.js"></script>

@endsection