// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Metropolis, -apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
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

// WatsonsWorkforceAreaChart - fetch actual data from backend and render (simplified)
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Chart === 'undefined') {
        console.error('Chart.js not available — WatsonsWorkforce area chart skipped');
        return;
    }

    var canvas = document.getElementById('WatsonsWorkforceAreaChart');
    if (!canvas) return; // not on this page

    // simple helper to draw the chart given labels and data (arrays)
    function drawChart(labels, dataValues) {
        try {
            var ctx = canvas.getContext('2d');
            if (canvas._chartInstance) {
                try { canvas._chartInstance.destroy(); } catch (e) {}
            }

            canvas._chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Requests',
                        lineTension: 0.3,
                        backgroundColor: 'rgba(0, 184, 148, 0.05)',
                        borderColor: 'rgba(0, 184, 148, 1)',
                        pointRadius: 3,
                        pointBackgroundColor: 'rgba(0, 184, 148, 1)',
                        pointBorderColor: 'rgba(0, 184, 148, 1)',
                        pointHoverRadius: 3,
                        pointHoverBackgroundColor: 'rgba(0, 184, 148, 1)',
                        pointHoverBorderColor: 'rgba(0, 184, 148, 1)',
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        data: dataValues
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    layout: { padding: { left: 10, right: 25, top: 25, bottom: 0 } },
                    scales: {
                        xAxes: [{ time: { unit: 'month' }, gridLines: { display: false, drawBorder: false }, ticks: { maxTicksLimit: 12 } }],
                        yAxes: [{ ticks: { maxTicksLimit: 6, padding: 10, callback: function(value){ return number_format(value) + ' req'; } }, gridLines: { color: 'rgb(234, 236, 244)', zeroLineColor: 'rgb(234, 236, 244)', drawBorder: false, borderDash: [2], zeroLineBorderDash: [2] } }]
                    },
                    legend: { display: false },
                    tooltips: { backgroundColor: 'rgb(255,255,255)', bodyFontColor: '#858796', titleMarginBottom: 10, titleFontColor: '#6e707e', titleFontSize: 14, borderColor: '#dddfeb', borderWidth: 1, xPadding: 15, yPadding: 15, displayColors: false, intersect: false, mode: 'index', caretPadding: 10, callbacks: { label: function(tooltipItem, chart) { var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || ''; return datasetLabel + ': ' + number_format(tooltipItem.yLabel) + ' requests'; } } }
                }
            });
        } catch (err) {
            console.error('Error initializing WatsonsWorkforceAreaChart', err);
        }
    }

    // default 12-month labels
    var DEFAULT_LABELS = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];

    // fetch backend data (expects {labels:[], data:[]}) and render chart
    (async function loadAndRender() {
        try {
            var DEFAULT_LABELS_COPY = DEFAULT_LABELS.slice();

            // Use injected server-side data if available for instant render
            var injected = (typeof window !== 'undefined' && window.WatsonsWorkforceArea);
            if (injected && Array.isArray(injected.labels) && Array.isArray(injected.data)) {
                var labels = injected.labels.slice();
                var dataValues = injected.data.slice();

                // coerce to integer, non-negative
                dataValues = dataValues.map(function(v){
                    var n = parseInt(v, 10);
                    if (isNaN(n)) n = 0;
                    return n < 0 ? 0 : n;
                });

                // normalize to 12 months
                if (labels.length !== 12 || dataValues.length !== 12) {
                    labels = DEFAULT_LABELS_COPY.slice(0, Math.max(12, labels.length));
                    dataValues = (dataValues.concat(Array(12).fill(0))).slice(0,12);
                }

                drawChart(labels, dataValues);
                return;
            }

            // If no injected data, fetch from backend endpoint
            var resp = await fetch('/superadmin/watsons-workforce-data', { credentials: 'same-origin' });
            if (!resp.ok) {
                console.error('WatsonsWorkforce data fetch failed', resp.status);
                drawChart(DEFAULT_LABELS_COPY, Array(12).fill(0));
                return;
            }

            var json = await resp.json();
            var labels = Array.isArray(json.labels) ? json.labels.slice() : DEFAULT_LABELS_COPY.slice();
            var dataValues = Array.isArray(json.data) ? json.data.slice() : Array(12).fill(0);

            // coerce to integer, non-negative
            dataValues = dataValues.map(function(v){
                var n = parseInt(v, 10);
                if (isNaN(n)) n = 0;
                return n < 0 ? 0 : n;
            });

            // normalize to 12 months: if shorter pad with zeros, if longer truncate
            if (labels.length !== 12 || dataValues.length !== 12) {
                labels = DEFAULT_LABELS_COPY.slice(0, Math.max(12, labels.length));
                dataValues = (dataValues.concat(Array(12).fill(0))).slice(0,12);
            }

            drawChart(labels, dataValues);
        } catch (err) {
            console.error('WatsonsWorkforce loadAndRender error', err);
            drawChart(DEFAULT_LABELS.slice(), Array(12).fill(0));
        }
    })();
});
