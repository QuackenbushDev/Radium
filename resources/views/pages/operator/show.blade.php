@extends("master")

@section('pageTitle', $operator->name)

@push('breadcrumbs')
    <li><a href="{{ route('operator::index') }}"><i class="fa fa-user"></i> Operator</a></li>
    <li><a href="{{ route('operator::show', $operator->id) }}">{{ $operator->name }}</a></li>
@endpush

@section('content')
    <div class="box">
        <div class="box-header">
            <h3>Operator Information <a href="{{ route("operator::edit", ['id' => $operator->id]) }}">(edit)</a></h3>
        </div>
        <div class="box-body">
            <table class="table table-responsive table-bordered">
                <tbody>
                    <tr>
                        <td>ID</td>
                        <td>{{ $operator->id }}</td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td>{{ $operator->name }}</td>
                    </tr>
                    <tr>
                        <td>E-Mail</td>
                        <td>{{ $operator->email }}</td>
                    </tr>
                    <tr>
                        <td>Created at</td>
                        <td>{{ $operator->created_at }}</td>
                    </tr>
                    <tr>
                        <td>Updated at</td>
                        <td>{{ $operator->updated_at }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
