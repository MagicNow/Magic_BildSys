<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ env('APP_TITLE') }}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="stylesheet" href="/css/admin.css">
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">
    <link rel="icon" href="{{ asset('img/favicon.png') }}" />
</head>

<body class="skin-yellow sidebar-mini">
@if (!Auth::guest())
    <div class="wrapper">
        <!-- Main Header -->
        <header class="main-header">

            <a href="{{ url('') }}" class="logo">
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
                        <!-- Messages: style can be found in dropdown.less-->
                        {{--<li class="dropdown messages-menu">--}}
                            {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">--}}
                                {{--<i class="fa fa-envelope-o"></i>--}}
                                {{--<span class="label label-success">4</span>--}}
                            {{--</a>--}}
                            {{--<ul class="dropdown-menu">--}}
                                {{--<li class="header">You have 4 messages</li>--}}
                                {{--<li>--}}
                                    {{--<!-- inner menu: contains the actual data -->--}}
                                    {{--<div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 200px;"><ul class="menu" style="overflow: hidden; width: 100%; height: 200px;">--}}
                                            {{--<li><!-- start message -->--}}
                                                {{--<a href="#">--}}
                                                    {{--<div class="pull-left">--}}
                                                        {{--<img src="http://placehold.it/160x160" class="img-circle" alt="User Image">--}}
                                                    {{--</div>--}}
                                                    {{--<h4>--}}
                                                        {{--Support Team--}}
                                                        {{--<small><i class="fa fa-clock-o"></i> 5 mins</small>--}}
                                                    {{--</h4>--}}
                                                    {{--<p>Why not buy a new awesome theme?</p>--}}
                                                {{--</a>--}}
                                            {{--</li>--}}
                                            {{--<!-- end message -->--}}
                                            {{--<li>--}}
                                                {{--<a href="#">--}}
                                                    {{--<div class="pull-left">--}}
                                                        {{--<img src="http://placehold.it/128x128" class="img-circle" alt="User Image">--}}
                                                    {{--</div>--}}
                                                    {{--<h4>--}}
                                                        {{--AdminLTE Design Team--}}
                                                        {{--<small><i class="fa fa-clock-o"></i> 2 hours</small>--}}
                                                    {{--</h4>--}}
                                                    {{--<p>Why not buy a new awesome theme?</p>--}}
                                                {{--</a>--}}
                                            {{--</li>--}}
                                            {{--<li>--}}
                                                {{--<a href="#">--}}
                                                    {{--<div class="pull-left">--}}
                                                        {{--<img src="http://placehold.it/128x128" class="img-circle" alt="User Image">--}}
                                                    {{--</div>--}}
                                                    {{--<h4>--}}
                                                        {{--Developers--}}
                                                        {{--<small><i class="fa fa-clock-o"></i> Today</small>--}}
                                                    {{--</h4>--}}
                                                    {{--<p>Why not buy a new awesome theme?</p>--}}
                                                {{--</a>--}}
                                            {{--</li>--}}
                                            {{--<li>--}}
                                                {{--<a href="#">--}}
                                                    {{--<div class="pull-left">--}}
                                                        {{--<img src="http://placehold.it/128x128" class="img-circle" alt="User Image">--}}
                                                    {{--</div>--}}
                                                    {{--<h4>--}}
                                                        {{--Sales Department--}}
                                                        {{--<small><i class="fa fa-clock-o"></i> Yesterday</small>--}}
                                                    {{--</h4>--}}
                                                    {{--<p>Why not buy a new awesome theme?</p>--}}
                                                {{--</a>--}}
                                            {{--</li>--}}
                                            {{--<li>--}}
                                                {{--<a href="#">--}}
                                                    {{--<div class="pull-left">--}}
                                                        {{--<img src="http://placehold.it/128x128" class="img-circle" alt="User Image">--}}
                                                    {{--</div>--}}
                                                    {{--<h4>--}}
                                                        {{--Reviewers--}}
                                                        {{--<small><i class="fa fa-clock-o"></i> 2 days</small>--}}
                                                    {{--</h4>--}}
                                                    {{--<p>Why not buy a new awesome theme?</p>--}}
                                                {{--</a>--}}
                                            {{--</li>--}}
                                        {{--</ul><div class="slimScrollBar" style="background: rgb(0, 0, 0); width: 3px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 131.148px;"></div><div class="slimScrollRail" style="width: 3px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div></div>--}}
                                {{--</li>--}}
                                {{--<li class="footer"><a href="#">See All Messages</a></li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}

                        {{--< ? php $notifications = \App\Http\Controllers\Admin\HomeController::verifyNotifications();--}}
                        {{--?>--}}

                        <!-- Notifications: style can be found in dropdown.less -->
                        <li class="dropdown notifications-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-bell-o"></i>
                                {{--<span class="label label-warning">{{count($notifications)}}</span>--}}
                                <span class="label label-success" id="count_notifications"></span>
                            </a>
                            <ul class="dropdown-menu">
                                {{--<li class="header">Você tem {{count($notifications)}} notificações</li>--}}
                                <li class="header">Você tem <span id="count_notification"></span> notificações</li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 200px;"><ul class="menu" style="overflow: hidden; width: 100%; height: 200px;">
                                            {{--@if(isset($notifications))--}}
                                                {{--@foreach($notifications as $notification)--}}
                                                    {{--@if($notification->success)--}}
                                                    {{--<li>--}}
                                                        {{--<a href="#" data-toggle="modal" data-target="#myModalsuccess" onclick="visualizedNotification('{{$notification->id}}')">--}}
                                                            {{--<i class="fa fa-check text-green"></i> Importação com sucesso--}}
                                                        {{--</a>--}}
                                                    {{--</li>--}}
                                                    {{--@elseif($notification->error)--}}
                                                    {{--<li>--}}
                                                        {{--<a href="#" data-toggle="modal" data-target="#myModalerror" onclick="visualizedNotification('{{$notification->id}}')">--}}
                                                            {{--<i class="fa fa-warning text-yellow"></i> Erro na importação--}}
                                                        {{--</a>--}}
                                                    {{--</li>--}}
                                                    {{--@endif--}}
                                                {{--@endforeach--}}
                                            {{--@endif--}}
                                            <li id="new_notifications"></li>
                                        </ul><div class="slimScrollBar" style="background: rgb(0, 0, 0); width: 3px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px;"></div><div class="slimScrollRail" style="width: 3px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div></div>
                                </li>
                                {{--<li class="footer"><a href="#">Todas notificações</a></li>--}}
                            </ul>
                        </li>

                        <!-- User Account Menu -->
                        <li class="dropdown user user-menu">
                            <!-- Menu Toggle Button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <!-- The user image in the navbar-->
                                <img src="{{ Gravatar::fallback(asset('img/user2-160x160.jpg'))->get(Auth::user()->email) }}" class="user-image" alt="User Image">
                                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                <span class="hidden-xs">{!! Auth::user()->name !!}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- The user image in the menu -->
                                <li class="user-header">
                                    <img src="{{ Gravatar::fallback(asset('img/user2-160x160.jpg'))->get(Auth::user()->email) }}" class="img-circle" alt="User Image">
                                    <p>
                                        {!! Auth::user()->name !!}
                                        <small>Cadastrado desde {!! Auth::user()->created_at->format('d/m/Y') !!}</small>
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="/admin/users/{{ Auth::id() }}/edit" class="btn btn-primary btn-flat">Pefil</a>
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

        <!-- Modal SUCCESS-->
        <div class="modal fade" id="myModalsuccess" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Importação com sucesso</h4>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover table-condensed">
                                <tbody>
                                    <tr>
                                        <td>Dados importados com sucesso.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal ERROR-->
        <div class="modal fade" id="myModalerror" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Erros Importação</h4>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover table-condensed">
                                <tbody id="error_import">
                                {{--@foreach($notification->error as $error)--}}
                                    {{--<tr>--}}
                                        {{--<td>{{ $error }}</td>--}}
                                    {{--</tr>--}}
                                {{--@endforeach--}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Left side column. contains the logo and sidebar -->
        @include('layouts.sidebar')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>

        <!-- Main Footer -->
        <footer class="main-footer" style="max-height: 100px;text-align: center">
            <strong>{{ env('APP_TITLE') }} © {{ date('Y') }} </strong>
        </footer>

    </div>
@else
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{!! url('/') !!}">
                    {{ env('APP_TITLE') }}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li><a href="{!! url('/home') !!}">Home</a></li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    <li><a href="{!! url('/login') !!}">Acesso</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    @endif

    <script src="/js/admin.js"></script>
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

        function visualizedNotification($id){
            $.ajax({
                url: "/admin/updateNotification/" + $id
            });
        }

        $(document).ready(function()
        {
            pullData();
        });

        function pullData()
        {
            verifyNotification();
            setTimeout(pullData,10000);
        }
        var token = '{{csrf_token()}}';
        function verifyNotification()
        {
            $.post('/admin/verifyNotification',{_token: token}, function(data)
            {
                $('#count_notifications').html(data.length);
                $('#count_notification').html(data.length);
                $('#new_notifications').html('');
//                $('#error_import').html('');
                $.each(data, function(index, val) {
                    if(val.success){
                        $('#new_notifications').append('<a href="#" data-toggle="modal" data-target="#myModalsuccess" onclick="visualizedNotification(\''+val.id+'\');"><i class="fa fa-check text-green"></i> Importação com sucesso</a>');
                    }else{
                        if(val.error) {
                            $.each(val.error, function (index, val) {
                                $('#error_import').append('<tr><td>' + val + '</td></tr>');
                            });
                        }
                        $('#new_notifications').append('<a href="#" data-toggle="modal" data-target="#myModalerror" onclick="visualizedNotification(\''+val.id+'\');"><i class="fa fa-warning text-yellow"></i> Erro na importação</a>');
                    }
                });
            });
        }
    </script>
</body>
</html>