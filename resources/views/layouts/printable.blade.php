<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/print.css') }}" media="all" rel="stylesheet" type="text/css"/>
        <meta charset=utf-8/>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="robots" content="noindex">
        <title>Impressão</title>
        <style type="text/css">
            @media print {
                .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
                    float: left;
                }
                .col-sm-12 {
                    width: 100%;
                }
                .col-sm-11 {
                    width: 91.66666667%;
                }
                .col-sm-10 {
                    width: 83.33333333%;
                }
                .col-sm-9 {
                    width: 75%;
                }
                .col-sm-8 {
                    width: 66.66666667%;
                }
                .col-sm-7 {
                    width: 58.33333333%;
                }
                .col-sm-6 {
                    width: 50%;
                }
                .col-sm-5 {
                    width: 41.66666667%;
                }
                .col-sm-4 {
                    width: 33.33333333%;
                }
                .col-sm-3 {
                    width: 25%;
                }
                .col-sm-2 {
                    width: 16.66666667%;
                }
                .col-sm-1 {
                    width: 8.33333333%;
                }
                .col-sm-pull-12 {
                    right: 100%;
                }
                .col-sm-pull-11 {
                    right: 91.66666667%;
                }
                .col-sm-pull-10 {
                    right: 83.33333333%;
                }
                .col-sm-pull-9 {
                    right: 75%;
                }
                .col-sm-pull-8 {
                    right: 66.66666667%;
                }
                .col-sm-pull-7 {
                    right: 58.33333333%;
                }
                .col-sm-pull-6 {
                    right: 50%;
                }
                .col-sm-pull-5 {
                    right: 41.66666667%;
                }
                .col-sm-pull-4 {
                    right: 33.33333333%;
                }
                .col-sm-pull-3 {
                    right: 25%;
                }
                .col-sm-pull-2 {
                    right: 16.66666667%;
                }
                .col-sm-pull-1 {
                    right: 8.33333333%;
                }
                .col-sm-pull-0 {
                    right: auto;
                }
                .col-sm-push-12 {
                    left: 100%;
                }
                .col-sm-push-11 {
                    left: 91.66666667%;
                }
                .col-sm-push-10 {
                    left: 83.33333333%;
                }
                .col-sm-push-9 {
                    left: 75%;
                }
                .col-sm-push-8 {
                    left: 66.66666667%;
                }
                .col-sm-push-7 {
                    left: 58.33333333%;
                }
                .col-sm-push-6 {
                    left: 50%;
                }
                .col-sm-push-5 {
                    left: 41.66666667%;
                }
                .col-sm-push-4 {
                    left: 33.33333333%;
                }
                .col-sm-push-3 {
                    left: 25%;
                }
                .col-sm-push-2 {
                    left: 16.66666667%;
                }
                .col-sm-push-1 {
                    left: 8.33333333%;
                }
                .col-sm-push-0 {
                    left: auto;
                }
                .col-sm-offset-12 {
                    margin-left: 100%;
                }
                .col-sm-offset-11 {
                    margin-left: 91.66666667%;
                }
                .col-sm-offset-10 {
                    margin-left: 83.33333333%;
                }
                .col-sm-offset-9 {
                    margin-left: 75%;
                }
                .col-sm-offset-8 {
                    margin-left: 66.66666667%;
                }
                .col-sm-offset-7 {
                    margin-left: 58.33333333%;
                }
                .col-sm-offset-6 {
                    margin-left: 50%;
                }
                .col-sm-offset-5 {
                    margin-left: 41.66666667%;
                }
                .col-sm-offset-4 {
                    margin-left: 33.33333333%;
                }
                .col-sm-offset-3 {
                    margin-left: 25%;
                }
                .col-sm-offset-2 {
                    margin-left: 16.66666667%;
                }
                .col-sm-offset-1 {
                    margin-left: 8.33333333%;
                }
                .col-sm-offset-0 {
                    margin-left: 0%;
                }
                .visible-xs {
                    display: none !important;
                }
                .hidden-xs {
                    display: block !important;
                }
                table.hidden-xs {
                    display: table;
                }
                tr.hidden-xs {
                    display: table-row !important;
                }
                th.hidden-xs,
                td.hidden-xs {
                    display: table-cell !important;
                }
                .hidden-xs.hidden-print {
                    display: none !important;
                }
                .hidden-sm {
                    display: none !important;
                }
                .visible-sm {
                    display: block !important;
                }
                table.visible-sm {
                    display: table;
                }
                tr.visible-sm {
                    display: table-row !important;
                }
                th.visible-sm,
                td.visible-sm {
                    display: table-cell !important;
                }
            }


            .container {
                padding: 1%;
            }

            h1,
            h2,
            h3,
            h4,
            h5,
            h6,
            .h1,
            .h2,
            .h3,
            .h4,
            .h5,
            .h6,
            p,
            ul,
            ol,
            form,
            table,
            address {
                margin-top: 0;
                margin-bottom: 20px;
                margin-bottom: 2rem;
            }

            @media print {

                .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
                    float: left;
                }

                .col-sm-12 {
                    width: 100%;
                }

                .col-sm-11 {
                    width: 91.66666666666666%;
                }

                .col-sm-10 {
                    width: 83.33333333333334%;
                }

                .col-sm-9 {
                    width: 75%;
                }

                .col-sm-8 {
                    width: 66.66666666666666%;
                }

                .col-sm-7 {
                    width: 58.333333333333336%;
                }

                .col-sm-6 {
                    width: 50%;
                }

                .col-sm-5 {
                    width: 41.66666666666667%;
                }

                .col-sm-4 {
                    width: 33.33333333333333%;
                }

                .col-sm-3 {
                    width: 25%;
                }

                .col-sm-2 {
                    width: 16.666666666666664%;
                }

                .col-sm-1 {
                    width: 8.333333333333332%;
                }

            }
        </style>
    </head>
    <body>
        <div>
            <div class="row text-left">
                <span class="logo-lg">
                    <img src="{{ asset('img/logo_bild.png') }}" style="max-height: 50px;">
                </span>
            </div>


            @yield('content')

        </div>
    </body>
</html>