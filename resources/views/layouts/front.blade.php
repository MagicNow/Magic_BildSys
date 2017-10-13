<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ env('APP_TITLE') }}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="stylesheet" href="{{ asset('css/google-web-fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">
    <link rel="icon" href="{{ asset('img/favicon.png') }}" />
    @yield('styles')
    <style type="text/css">
        body {
            zoom: 75%; /* Webkit browsers */
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="skin-yellow sidebar-mini sidebar-collapse" baseurl="{{ url('/') }}">

    <div class="wrapper">
        <!-- Main Header -->
        <header class="main-header">

            <a href="{{ url('') }}" class="logo hidden-xs">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini">
                    <img src="{{ asset('img/logo_bild_sys.png') }}" style="max-height: 50px;">
                </span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg">
                    <img src="{{ asset('img/logo_bild_sys.png') }}" style="max-height: 50px;">
                </span>
            </a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">


                        {{--<!-- Messages: style can be found in dropdown.less-->
                        <li class="dropdown messages-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-envelope-o"></i>
                                <span class="label label-success">4</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">You have 4 messages</li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 200px;"><ul class="menu" style="overflow: hidden; width: 100%; height: 200px;">
                                            <li><!-- start message -->
                                                <a href="#">
                                                    <div class="pull-left">
                                                        <img src="http://placehold.it/160x160" class="img-circle" alt="User Image">
                                                    </div>
                                                    <h4>
                                                        Support Team
                                                        <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                                    </h4>
                                                    <p>Why not buy a new awesome theme?</p>
                                                </a>
                                            </li>
                                            <!-- end message -->
                                            <li>
                                                <a href="#">
                                                    <div class="pull-left">
                                                        <img src="http://placehold.it/128x128" class="img-circle" alt="User Image">
                                                    </div>
                                                    <h4>
                                                        AdminLTE Design Team
                                                        <small><i class="fa fa-clock-o"></i> 2 hours</small>
                                                    </h4>
                                                    <p>Why not buy a new awesome theme?</p>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <div class="pull-left">
                                                        <img src="http://placehold.it/128x128" class="img-circle" alt="User Image">
                                                    </div>
                                                    <h4>
                                                        Developers
                                                        <small><i class="fa fa-clock-o"></i> Today</small>
                                                    </h4>
                                                    <p>Why not buy a new awesome theme?</p>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <div class="pull-left">
                                                        <img src="http://placehold.it/128x128" class="img-circle" alt="User Image">
                                                    </div>
                                                    <h4>
                                                        Sales Department
                                                        <small><i class="fa fa-clock-o"></i> Yesterday</small>
                                                    </h4>
                                                    <p>Why not buy a new awesome theme?</p>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <div class="pull-left">
                                                        <img src="http://placehold.it/128x128" class="img-circle" alt="User Image">
                                                    </div>
                                                    <h4>
                                                        Reviewers
                                                        <small><i class="fa fa-clock-o"></i> 2 days</small>
                                                    </h4>
                                                    <p>Why not buy a new awesome theme?</p>
                                                </a>
                                            </li>
                                        </ul><div class="slimScrollBar" style="background: rgb(0, 0, 0); width: 3px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 131.148px;"></div><div class="slimScrollRail" style="width: 3px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div></div>
                                </li>
                                <li class="footer"><a href="#">See All Messages</a></li>
                            </ul>
                        </li>--}}

                        <!-- Notifications: style can be found in dropdown.less -->
                        <li class="dropdown notifications-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-bell-o"></i>
                                <span class="label label-success notification-counter"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">
                                    Você tem <span class="notification-counter"></span>
                                    notificações
                                </li>
                                <li>
                                    <ul class="menu">
                                        <li id="new_notifications"></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <!-- User Account Menu -->
                        <li class="dropdown user user-menu">
                            <!-- Menu Toggle Button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <!-- The user image in the navbar-->
                                <span class="hidden-xs">{!! Auth::user()->name !!}</span>
                                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                {{--<img src="/img/bildzito.jpg" class="user-image" alt="User Image">--}}
                            </a>
                            <ul class="dropdown-menu">
                                <!-- The user image in the menu -->
                                <li class="user-header">
                                    <img src="{{ asset('img/bildzito.jpg') }}" class="img-circle" alt="User Image">
                                    <p>
                                        {!! Auth::user()->name !!}
                                        {{--<small>Cadastrado desde {!! Auth::user()->created_at->format('d/m/Y') !!}</small>--}}

                                        <a class="btn btn-xs btn-block btn-flat btn-default" title="Missão, Visão e Valores"
                                           href="#" data-toggle="modal" data-target="#modalMVV">
                                            Missão, Visão e Valores
                                        </a>
                                    </p>


                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    @shield('dashboard.access')
                                        <div class="pull-left">
                                            <a href="/admin" class="btn btn-warning btn-flat">Administrativo</a>
                                            &nbsp;
                                        </div>
                                    @endshield
                                    <div class="pull-left">
                                        <a href="/perfil" class="btn btn-primary btn-flat">Perfil</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="{!! url('/logout') !!}" class="btn btn-danger btn-flat"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            Sair
                                        </a>
                                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </div>
            </nav>
        </header>

        <!-- Left side column. contains the logo and sidebar -->
        @include('layouts.front_sidebar')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @include( 'flash::message' )
            @yield('content')
        </div>



    </div>
    <!-- Modal -->
    <div class="modal fade" id="modalMVV" tabindex="-1" role="dialog" aria-labelledby="modalMissaoVisaoValores">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modalMissaoVisaoValores">Missão, Visão e Valores</h4>
                </div>
                <div class="modal-body">
                    <img src="{{ asset('img/missao-visao-valores.png') }}" style="margin: auto" class="img-responsive">
                </div>
            </div>
        </div>
    </div>
    @include('partials.modals-importacao')
    <script src="{{ asset('js/admin.js') }}"></script>
    <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>

    @yield('scripts')
    <script type="text/javascript">
        function confirmDelete(frm) {
            var formulary = $('#' + frm);
            swal({
                title: "{{ ucfirst( trans('common.are-you-sure') ) }}?",
                text: "{{ ucfirst( trans('common.you-will-not-be-able-to-recover-this-registry') ) }}!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "{{ ucfirst( trans('common.yes') ) }}, {{ ucfirst( trans('common.delete') ) }}!",
                cancelButtonText: "{{ ucfirst( trans('common.cancel') ) }}",
                closeOnConfirm: false
            }, function () {
                formulary.submit();
            });
        }

        function dispensarInsumos(url, msg) {
            swal({
                title: msg,
                text: "",
                type: "success",
                showCancelButton: true,
                confirmButtonColor: "success",
                confirmButtonText: "{{ ucfirst( trans('common.yes') ) }}",
                cancelButtonText: "{{ ucfirst( trans('common.cancel') ) }}",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }, function () {
                $.get(url, function(){
                    LaravelDataTables.dataTableBuilder.draw();
                    atualizaCalendario();
                    swal.close();
                });
            });
        }
    </script>
</body>
</html>
