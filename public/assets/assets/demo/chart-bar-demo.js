// Set new default font family and font color to mimic Bootstrap's default styling
(Chart.defaults.global.defaultFontFamily = "Metropolis"),
'-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = "#858796";

function number_format(number, decimals, dec_point, thousands_sep) {
    number = (number + "").replace(",", "").replace(" ", "");
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = typeof thousands_sep === "undefined" ? "," : thousands_sep,
        dec = typeof dec_point === "undefined" ? "." : dec_point,
        s = "",
        toFixedFix = function(n, prec) {
            var k = Math.pow(10, prec);
            return "" + Math.round(n * k) / k;
        };
    s = (prec ? toFixedFix(n, prec) : "" + Math.round(n)).split(".");
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || "").length < prec) {
        s[1] = s[1] || "";
        s[1] += new Array(prec - s[1].length + 1).join("0");
    }
    return s.join(dec);
}

// Build data for the TimeOff bar chart using your number_format function
document.addEventListener('DOMContentLoaded', function() {
    var initTimeOffChart = function() {
        var labels = [];
        var data = [];

        try {
            if (window.TimeOffBar && Array.isArray(window.TimeOffBar.labels) && Array.isArray(window.TimeOffBar.data)) {
                // Use server-provided arrays; ensure they are copies and numeric
                labels = window.TimeOffBar.labels.slice();
                data = window.TimeOffBar.data.map(function(v) { return Number(v) || 0; }).slice();

                // If labels length doesn't match data length, normalize to 12 months
                if (labels.length !== data.length) {
                    console.warn('TimeOffBar labels/data length mismatch — normalizing to 12 months.');
                }
            } else {
                console.warn('TimeOffBar data not found or invalid — falling back to zeroed 12 months.');
            }
        } catch (e) {
            console.error('Error reading TimeOffBar data', e);
        }

        // Ensure we have 12 month labels (abbreviated like Carbon::format('M')) and corresponding data
        var defaultMonths = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        if (!Array.isArray(labels) || labels.length !== 12) {
            labels = defaultMonths.slice();
        }
        if (!Array.isArray(data) || data.length !== 12) {
            // If server provided partial data (e.g., current year months only), try to map by label
            if (window.TimeOffBar && Array.isArray(window.TimeOffBar.labels) && Array.isArray(window.TimeOffBar.data)) {
                var map = {};
                for (var i = 0; i < window.TimeOffBar.labels.length; i++) {
                    var k = String(window.TimeOffBar.labels[i]).trim();
                    map[k] = Number(window.TimeOffBar.data[i]) || 0;
                }
                data = defaultMonths.map(function(m) { return map[m] || 0; });
            } else {
                data = (new Array(12)).fill(0);
            }
        }

        var ctx = document.getElementById("timeOffChart");
        if (!ctx) return;

        if (ctx._chartInstance) {
            try { ctx._chartInstance.destroy(); } catch (ignored) {}
        }

        var maxVal = Math.max.apply(null, data);
        var suggestedMax = (maxVal && maxVal > 0) ? Math.ceil(maxVal * 1.2) : 5;

        var myBarChart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: labels,
                datasets: [{
                    label: "TimeOff Count",
                    backgroundColor: "rgba(0, 97, 242, 1)",
                    hoverBackgroundColor: "rgba(0, 97, 242, 0.9)",
                    borderColor: "#4e73df",
                    data: data,
                    maxBarThickness: 25
                }]
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: { left: 10, right: 25, top: 25, bottom: 0 }
                },
                scales: {
                    xAxes: [{
                        time: { unit: "month" },
                        gridLines: { display: false, drawBorder: false },
                        ticks: { maxTicksLimit: 12 }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            min: 0,
                            max: suggestedMax,
                            maxTicksLimit: 5,
                            padding: 10,
                            callback: function(value) { return number_format(value, 0, '.', ','); }
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }]
                },
                legend: { display: false },
                tooltips: {
                    titleMarginBottom: 10,
                    titleFontColor: "#6e707e",
                    titleFontSize: 14,
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: "#dddfeb",
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || "";
                            return datasetLabel + ": " + number_format(tooltipItem.yLabel, 0, '.', ',');
                        }
                    }
                }
            }
        });

        ctx._chartInstance = myBarChart;
    };

    // Initialize chart (DOM is ready and Blade-injected globals have executed)
    initTimeOffChart();
});
