<!DOCTYPE html>
<html lang="en">
@include('superadmin.partials.header')
@include('superadmin.partials.topnav')


<div id="layoutSidenav">
    @include('superadmin.partials.sidenav')
    <div id="layoutSidenav_content">
        <main>
            <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
                <div class="container-xl px-4">
                    <div class="page-header-content pt-4">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto mt-4">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i data-feather="map"></i></div>
                                    TESTING QR SCANNER
                                </h1>
                                <div class="page-header-subtitle">A Geographic Information System Application</div>
                            </div>
                            <div class="col-12 col-xl-auto mb-3">
                                <a class="btn btn-sm btn-light text-primary" href="{{ route('superadmin_apps') }}">
                                    <i class="me-1" data-feather="arrow-left"></i>
                                    Back to Applications
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <div class="container-xl px-4 mt-n10">
                <div class="row">

                    <div class="col-xl-6 mb-2">
                        <div class="card">
                            <div class="card-header">Scan QR Code</div>
                            <div class="card-body text-center">
                                <!-- Responsive video for QR scanning -->
                                <div style="max-width: 100%; margin: 0 auto;">
                                    <video id="qr-video" style="width:100%; max-width:600px; height:auto; aspect-ratio: 4/3; border:2px solid #007bff; border-radius: 12px; background: #222;" autoplay playsinline></video>
                                </div>
                                <div id="qr-result" class="mt-3"></div>
                                <div id="qr-display" class="mt-3"></div>
                                <small class="text-muted d-block mt-2">Align the QR code within the frame to scan.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Responsive aspect ratio for older browsers -->
                    <style>
                        @media (max-width: 600px) {
                            #qr-video {
                                width: 100vw;
                                max-width: 100vw;
                                height: auto;
                                aspect-ratio: 4/3;
                                border: 2px solid #007bff;
                                border-radius: 12px;
                                background: #222;
                            }
                        }
                    </style>

                    <!-- Include qr-scanner library from CDN -->
                    <script type="module">
                        import QrScanner from "https://unpkg.com/qr-scanner@1.4.2/qr-scanner.min.js";
                        const video = document.getElementById('qr-video');
                        const resultDiv = document.getElementById('qr-result');
                        const displayDiv = document.getElementById('qr-display');

                        QrScanner.WORKER_PATH = 'https://unpkg.com/qr-scanner@1.4.2/qr-scanner-worker.min.js';

                        // Store scanned results
                        let scannedData = [];

                        // Function to update logs table
                        function updateLogsTable() {
                            const logsTableBody = document.getElementById('logs-table-body');
                            logsTableBody.innerHTML = '';
                            scannedData.slice().reverse().forEach((item, idx) => {
                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td>${scannedData.length - idx}</td>
                                    <td>${item.data}</td>
                                    <td>${item.time}</td>
                                `;
                                logsTableBody.appendChild(row);
                            });
                        }

                        let isScanning = true;

                        const scanner = new QrScanner(
                            video,
                            result => {
                                if (!isScanning) return;
                                isScanning = false; // Pause scanning

                                let displayText;
                                if (typeof result === 'object' && result !== null && 'data' in result) {
                                    displayText = result.data;
                                } else {
                                    displayText = result;
                                }
                                resultDiv.innerHTML = `<span class="badge bg-success">QR Code Detected:</span> <br> <strong>${displayText}</strong>`;
                                displayDiv.innerHTML = `<div class="alert alert-info">Scanned QR Code: <strong>${displayText}</strong></div>`;

                                // Add to scannedData and update logs
                                scannedData.push({
                                    data: displayText,
                                    time: new Date().toLocaleString()
                                });
                                updateLogsTable();

                                // Wait 2 seconds, then allow scanning again
                                setTimeout(() => {
                                    resultDiv.innerHTML = '';
                                    displayDiv.innerHTML = '';
                                    isScanning = true;
                                }, 2000);
                            }, {
                                onDecodeError: error => {
                                    // Optionally handle decode errors
                                },
                                highlightScanRegion: true,
                                highlightCodeOutline: true,
                            }
                        );

                        scanner.start().catch(err => {
                            resultDiv.innerText = 'Camera access denied: ' + err;
                        });

                        // Initialize logs table on page load
                        document.addEventListener('DOMContentLoaded', updateLogsTable);
                    </script>

                    <!-- DataTable for Logs -->
                    <div style="display:none"></div>
                    <script>
                        // Ensure logs table is present after DOM is loaded
                        document.addEventListener('DOMContentLoaded', function() {
                            const logsCardBody = document.querySelector('div.card[name="logs"] .card-body');
                            if (logsCardBody && !document.getElementById('logs-table')) {
                                logsCardBody.innerHTML = `
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped" id="logs-table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Scanned Data</th>
                                                    <th>Timestamp</th>
                                                </tr>
                                            </thead>
                                            <tbody id="logs-table-body">
                                            </tbody>
                                        </table>
                                    </div>
                                `;
                            }
                        });
                    </script>
                    <!-- Main page content-->
                    <div class="col-xl-6 mb-2">
                        <div class="card" name="logs">
                            <div class="card-header">LOGS</div>
                            <div class="card-body">
                            </div>
                        </div>
                    </div>

        </main>
        @include('superadmin.partials.footer')
        </body>

</html>