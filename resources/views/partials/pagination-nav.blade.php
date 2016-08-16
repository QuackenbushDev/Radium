<div class="row">
    <div class="col-sm-5">
        Showing {{ ($data->currentPage() - 1) * $data->perPage() }} to {{ $data->currentPage() * $data->perPage() }} of {{ $data->total() }}
    </div>

    <div class="col-sm-7">
        <div class="dataTables_paginate paging_simple_numbers">
            {{ $data->links() }}
        </div>
    </div>
</div>
