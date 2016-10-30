function makeBandwidthChart(id, timeSpan, timeValue, username, nasID) {
    var url = '/api/bandwidthUsage?timeSpan=' + timeSpan + "&timeValue=" + timeValue;

    if (username != '') {
        url = url + '&username=' + username
    }

    if (nasID != '') {
        url = url + '&nasID=' + nasID
    }

    $.ajax({
        url: url
    }).done(function(data) {
        drawLineChart(id, data['headers'], data.usage.download, data.usage.upload);
    });
}

function makeConnectionChart(id, timeSpan, timeValue, username, nasID) {
    var url = '/api/connectionCount?timeSpan=' + timeSpan + "&timeValue=" + timeValue;

    if (username != '') {
        url = url + '&username=' + username
    }

    if (nasID != '') {
        url = url + '&nasID=' + nasID
    }

    $.ajax({
        url: url
    }).done(function(data) {
        drawBarChart(id, data['headers'], data['dataSet'], 'Connections');
    });
}

var chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    scales : {
        xAxes : [{
            gridLines : {
                display : false
            }
        }],
        yAxes : [{
            gridLines : {
                display : false
            }
        }]
    },
    legend: {
        display: false
    },
    hover: {
        mode: 'x-axis'
    },
    tooltips: {
        mode: 'x-axis'
    }
};

function drawLineChart(id, labels, dataset1, dataset2) {
    var chartObject = $("#" + id).get(0).getContext("2d");

    var chartData = {
        labels: labels,

        datasets: [
            {
                label: "Download",
                backgroundColor: "rgba(0, 153, 51, 0.5)",
                fill: true,

                pointBorderWidth: 0,
                pointHoverBorderWidth: 0,
                pointRadius: 0,
                pointHitRadius: 3,

                data: dataset1
            },
            {
                label: "Upload",
                backgroundColor: "rgba(60,141,188,0.5)",
                fill: true,

                pointBorderWidth: 0,
                pointHoverBorderWidth: 0,
                pointRadius: 0,
                pointHitRadius: 3,

                data: dataset2
            }
        ]
    };

    new Chart(chartObject, {
        type: 'line',
        data: chartData,
        options: chartOptions
    });
}

function drawBarChart(id, labels, dataset, dataSetLabel) {
    var chartObject = $("#" + id).get(0).getContext("2d");

    var chartData = {
        labels: labels,

        datasets: [
            {
                label: dataSetLabel,
                backgroundColor: "rgba(60,141,188,1)",
                fill: true,
                data: dataset
            }
        ]
    };

    new Chart(chartObject, {
        type: 'bar',
        data: chartData,
        options: chartOptions
    });
}
