<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">{{ $title }}</h3>
    </div>
    <div class="box-body">
        <table class="table table-responsive table-striped table-bordered">
            <tbody>
                <tr>
                    <td>Connections</td>
                    <td>{{ $data['connections'] }}</td>
                </tr>
                <tr>
                    <td>IN</td>
                    <td>{{ $data['in'] }}</td>
                </tr>
                <tr>
                    <td>OUT</td>
                    <td>{{ $data['out'] }}</td>
                </tr>
                <tr>
                    <td><strong>Total</strong></td>
                    <td><strong>{{ $data['total'] }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>