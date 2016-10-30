<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">{{ $title }}</h3>
    </div>
    <div class="box-body">
        <table class="table table-responsive table-striped table-bordered">
            <tbody>
                <tr>
                    <td>Download</td>
                    <td>{{ $data['download'] }}</td>
                </tr>
                <tr>
                    <td>Upload</td>
                    <td>{{ $data['upload'] }}</td>
                </tr>
                <tr>
                    <td><strong>Total</strong></td>
                    <td><strong>{{ $data['total'] }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>