<div class="row">
    <div class="col-md-12 bg-">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{ $title }}</h3>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="chart">
                            <canvas id="{{ $id }}" height="{{ $height }}"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('documentReady')
    makeBandwidthChart('{{ $id }}', '{{ $timeSpan }}', '{{ $timeValue }}', '{{ $username }}', '{{ $nasID }}');
@endpush