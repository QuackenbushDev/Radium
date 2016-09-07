<!DOCTYPE html>
<html>
<head>
    @include('partials.layout.header')
</head>
<body class="hold-transition skin-blue sidebar-mini">
    @include('partials.flash-message');
    @yield('content');

    @include('partials.layout.scripts')
</body>
</html>
