<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script type="text/javascript" src="{{ asset('/js/contrib/Chart.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/contrib/Chart.bundle.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/widgets/graphs.js') }}"></script>
<script type="text/javascript" src="/js/app.min.js"></script>
@stack('scripts')

<script type="text/javascript">
    $(document).ready(function() {
        @stack('documentReady')
    });
</script>