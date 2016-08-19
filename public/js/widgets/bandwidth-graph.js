function makeBandwidthChart(id, timeSpan, timeValue, username, nasIP) {
    var url = '/api/bandwidthUsage?timeSpan=' + timeSpan + "&timeValue=" + timeValue;

    if (username != '') {
        url = url + '&username=' + username
    }

    if (nasIP != '') {
        url = url + '&nasIP=' + nasIP
    }

    $.ajax({
        url: url
    }).done(function(data) {
        drawChart(id, data['headers'], data.usage.download, data.usage.upload);
    });
}

function drawChart(id, labels, dataset1, dataset2) {
    var chartObject = $("#" + id).get(0).getContext("2d");
    var chart = new Chart(chartObject);

    var chartData = {
        labels: labels,

        datasets: [
            {
                label: "Download",
                fillColor: "rgb(210, 214, 222)",
                strokeColor: "rgb(210, 214, 222)",
                pointColor: "rgb(210, 214, 222)",
                pointStrokeColor: "#c1c7d1",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgb(220,220,220)",
                data: dataset1
            },
            {
                label: "Upload",
                fillColor: "rgba(60,141,188,0.9)",
                strokeColor: "rgba(60,141,188,0.8)",
                pointColor: "#3b8bba",
                pointStrokeColor: "rgba(60,141,188,1)",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(60,141,188,1)",
                data: dataset2
            }
        ]
    };

    var chartOptions = {
        showScale: true,
        scaleShowGridLines: false,
        scaleGridLineColor: "rgba(0,0,0,.05)",
        scaleGridLineWidth: 1,
        scaleShowHorizontalLines: true,
        scaleShowVerticalLines: true,
        bezierCurve: true,
        bezierCurveTension: 0.3,
        pointDot: false,
        pointDotRadius: 4,
        pointDotStrokeWidth: 1,
        pointHitDetectionRadius: 20,
        datasetStroke: true,
        datasetStrokeWidth: 2,
        datasetFill: true,
        maintainAspectRatio: true,
        responsive: true
    };

    chart.Line(chartData, chartOptions);
}
