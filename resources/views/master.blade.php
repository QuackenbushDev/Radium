<!DOCTYPE html>
<html>
<head>
    @include('partials.layout.header')
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

    @include('partials.layout.menu')

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

@include('partials.layout.scripts')

</body>
</html>
