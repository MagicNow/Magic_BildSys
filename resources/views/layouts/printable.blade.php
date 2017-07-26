<!DOCTYPE html>
<html>
    <head>
        <script src="http://code.jquery.com/jquery.min.js"></script>
        <link href="http://getbootstrap.com/dist/css/bootstrap.css" rel="stylesheet" type="text/css"/>
        <script src="http://getbootstrap.com/dist/js/bootstrap.js"></script>
        <meta charset=utf-8/>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="robots" content="noindex">
        <title>Impressão</title>

        <style>


            .container {
                padding: 5%;
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
        <div class="container">
            <div class="row text-right">
                <span class="logo-lg">
                <img src="{{ asset('img/logo_bild.png') }}" style="max-height: 50px;">
            </span>
            </div>


            @yield('content')

        </div>
    </body>
</html>