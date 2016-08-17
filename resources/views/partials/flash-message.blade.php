@if (Session::has('message'))
    <div class="alert {{ Session::get('alert-class', '') }}">
        @if (Session::get('alert-class', '') === 'alert-success')
            <h4>Success!</h4>
        @elseif(Session::get('alert-class', '') === 'alert-error')
            <h4>Error!</h4>
        @else
            <h4>Alert!</h4>
        @endif

        {{ Session::get('message', '') }}
    </div>
@endif