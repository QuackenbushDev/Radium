<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Radium | @yield('pageTitle')</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ asset("css/bootstrap.min.css") }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{{ asset("css/AdminLTE.min.css") }}">
    <link rel="stylesheet" href="{{ asset("css/skins/_all-skins.min.css") }}">
    <link rel="stylesheet" href="{{ asset("css/app.css") }}">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    @stack('css')
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <header class="main-header">
        <a href="/" class="logo">
            <span class="logo-mini"><b>Radium</b></span>
            <span class="logo-lg"><b>Radium</b> Administrator</span>
        </a>
        <nav class="navbar navbar-static-top">
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">
                        <a href="#">
                            <span class="hidden-xs">Samuel Faunt</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <aside class="main-sidebar">
        <section class="sidebar">
            <ul class="sidebar-menu">
                <li class="header">MAIN NAVIGATION</li>
                <li class="treeview">
                    <a href="{{ url('/') }}">
                        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="header">Management</li>
                <li class="treeview">
                    <a href="{{ route('user::index') }}">
                        <i class="fa fa-user"></i> <span>Users</span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="{{ route('nas::index') }}">
                        <i class="fa fa-server"></i> <span>NAS</span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-group"></i> <span>Groups</span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-lock"></i> <span>Proxies</span>
                    </a>
                </li>
                <li class="header">Reports</li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>Online Users</span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>Connection Attempts</span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="{{ route('report::bandwidth') }}">
                        <i class="fa fa-dashboard"></i> <span>Bandwidth</span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="{{ route('report::accounting') }}">
                        <i class="fa fa-table"></i> <span>Accounting</span>
                    </a>
                </li>
                <li class="header">Graphs</li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>User</span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>Statistics</span>
                    </a>
                </li>
            </ul>
        </section>
    </aside>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                @yield('pageTitle')
                <small>@yield('pageDescription')</small>
            </h1>
            <ol class="breadcrumb">
                @stack('breadcrumbs')
            </ol>
        </section>

        <div class="content">
            @include('partials.flash-message')

            @yield("content")
        </div>
    </div>

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 0.0.1
        </div>
        <strong>Copyright &copy; 2016 <a href="#">Radium</a>.</strong> All rights reserved.
    </footer>

    <div class="control-sidebar-bg"></div>
</div>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script type="text/javascript" src="{{ asset('/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/plugins/fastclick/fastclick.js') }}"></script>
<script type="text/javascript" src="{{ asset('/plugins/chartjs/Chart.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/widgets/graphs.js') }}"></script>
<script type="text/javascript" src="/js/app.min.js"></script>
@stack('scripts')

<script type="text/javascript">
    $(document).ready(function() {
        @stack('documentReady')
    });
</script>

</body>
</html>
