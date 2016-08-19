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
        drawLineChart(id, data['headers'], data.usage.download, data.usage.upload);
    });
}

function makeConnectionChart(id, timeSpan, timeValue, username, nasIP) {
    var url = '/api/connectionCount?timeSpan=' + timeSpan + "&timeValue=" + timeValue;

    if (username != '') {
        url = url + '&username=' + username
    }

    if (nasIP != '') {
        url = url + '&nasIP=' + nasIP
    }

    $.ajax({
        url: url
    }).done(function(data) {
        drawBarChart(id, data['headers'], data['dataSet'], 'Connections');
    });
}

function drawLineChart(id, labels, dataset1, dataset2) {
    var chartObject = $("#" + id).get(0).getContext("2d");
    var chart = new Chart(chartObject);

    var chartData = {
        labels: labels,

        datasets: [
            {
                label: "Download",
                fillColor: "rgba(0, 153, 51, 0)",
                strokeColor: "rgba(0, 153, 51, 1)",
                pointColor: "rgba(0, 153, 51, 1)",
                pointStrokeColor: "#c1c7d1",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgb(220,220,220)",
                data: dataset1
            },
            {
                label: "Upload",
                fillColor: "rgba(60,141,188,0)",
                strokeColor: "rgba(60,141,188,1)",
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

function drawBarChart(id, labels, dataset, dataSetLabel) {
    var chartObject = $("#" + id).get(0).getContext("2d");
    var chart = new Chart(chartObject);

    var chartData = {
        labels: labels,

        datasets: [
            {
                label: dataSetLabel,
                fillColor: "rgba(60,141,188,1)",
                strokeColor: "rgba(60,141,188,1)",
                pointColor: "rgba(60,141,188,1)",
                pointStrokeColor: "#c1c7d1",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgb(220,220,220)",
                data: dataset
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

    chart.Bar(chartData, chartOptions);
}