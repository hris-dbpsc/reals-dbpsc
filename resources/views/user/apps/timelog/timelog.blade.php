<!DOCTYPE html>
<html lang="en">
@include('user.partials.user_header')

<body class="nav-fixed">
    @include('user.partials.user_topnav')
    <div id="layoutSidenav">
        @include('user.partials.user_sidenav')
        <div id="layoutSidenav_content">
            <main>
                <header class="page-header bg-gray-200 pb-10">
                    <div class="container-fluid px-4">
                        <div class="page-header-content pt-4">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-auto mt-2">
                                    <h2 class="page-header-title text-body d-flex align-items-center" style="font-size:1.5rem;">
                                        <span class="page-header-icon me-2 text-body d-flex justify-content-center align-items-center" style="width:28px; height:28px;">
                                            <i data-feather="clock" style="width:30px; height:30px;"></i>
                                        </span>
                                        TimeLog
                                    </h2>
                                    <div class="page-header-subtitle text-body mt-2">Attendance Monitoring System</div>
                                </div>
                                <div class="col-auto mt-4">
                                    <a href="{{ route('user_apps') }}" class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width:50px; height:50px; padding:0;" aria-label="Back to Apps">
                                        <i data-feather="arrow-left-circle" class="text-primary" style="width:40px; height:40px;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <div class="container-fluid px-4 mt-n10">
                    <div class="row">
                        @if (session('success'))
                        <div class="alert alert-success alert-sm py-1 px-2" role="status">
                            {{ session('success') }}
                        </div>
                        @endif
                        <div class="col-xl-12 mb-2">
                            <a class="card lift lift-sm h-100">
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="me-3 w-100 d-flex justify-content-center align-items-center">
                                            <div class="d-flex justify-content-center align-items-center flex-column w-100" style="height:100%;">
                                                <div id="manila-clock" style="font-size:4rem; color:#0d6efd; margin-top:10px; text-align:center; letter-spacing:2px; width:100%;"></div>
                                            </div>
                                            <script>
                                                function updateManilaClock() {
                                                    const options = {
                                                        timeZone: 'Asia/Manila',
                                                        hour: '2-digit',
                                                        minute: '2-digit',
                                                        second: '2-digit',
                                                        hour12: true
                                                    };
                                                    const now = new Date();
                                                    const timeString = now.toLocaleTimeString('en-US', options);
                                                    const el = document.getElementById('manila-clock');
                                                    if (el) el.textContent = timeString;
                                                }
                                                setInterval(updateManilaClock, 1000);
                                                updateManilaClock();
                                                // Add day below the clock
                                                function updateManilaDay() {
                                                    const options = {
                                                        timeZone: 'Asia/Manila',
                                                        weekday: 'long'
                                                    };
                                                    const now = new Date();
                                                    const dayString = now.toLocaleDateString('en-US', options);
                                                    let dayEl = document.getElementById('manila-day');
                                                    if (!dayEl) {
                                                        dayEl = document.createElement('div');
                                                        dayEl.id = 'manila-day';
                                                        dayEl.style.fontSize = '1.5rem';
                                                        dayEl.style.color = '#0d6efd';
                                                        dayEl.style.textAlign = 'center';
                                                        dayEl.style.marginTop = '10px';
                                                        const clockEl = document.getElementById('manila-clock');
                                                        if (clockEl && clockEl.parentNode) {
                                                            clockEl.parentNode.appendChild(dayEl);
                                                        }
                                                    }
                                                    dayEl.textContent = dayString;
                                                }
                                                updateManilaDay();
                                                setInterval(updateManilaDay, 1000);
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 col-xl-6 mb-2">
                            <form id="clockin-form" method="POST" action="{{ route('user_clock_in') }}">
                                @csrf
                                <input type="hidden" name="latitude" id="clockin-latitude" />
                                <input type="hidden" name="longitude" id="clockin-longitude" />
                                <input type="hidden" name="accuracy" id="clockin-accuracy" />
                                <input type="hidden" name="location" id="clockin-location" />

                                <button type="submit" class="card lift lift-sm h-100 w-100"
                                    @if(empty($canClockIn)) disabled aria-disabled="true" title="Clock-in disabled for today" @else title="Clock-In" @endif>
                                    <div class="card-body d-flex justify-content-center align-items-center flex-column h-100">
                                        <div class="d-flex flex-column align-items-center justify-content-center w-100">
                                            <i class="feather text-success mb-1" data-feather="log-in" style="width: 100px; height: 100px;"></i>
                                            <h3 class="fw-bold text-body mt-2">Clock-In</h3>
                                        </div>
                                    </div>
                                </button>
                            </form>
                        </div>

                        <div class="col-6 col-xl-6 mb-2">
                            <form id="clockout-form" method="POST" action="{{ route('user_clock_out') }}">
                                @csrf
                                <button type="submit" class="card lift lift-sm h-100 w-100"
                                    @if(empty($canClockOut)) disabled aria-disabled="true" title="Clock-out disabled for today" @else title="Clock-Out" @endif>
                                    <div class="card-body d-flex justify-content-center align-items-center flex-column h-100">
                                        <div class="d-flex flex-column align-items-center justify-content-center w-100">
                                            <i class="feather text-danger mb-1" data-feather="log-out" style="width: 100px; height: 100px;"></i>
                                            <h3 class="fw-bold text-body mt-2">Clock-Out</h3>
                                        </div>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Timelog filter: placed after clock-in/out buttons -->
                    <div class="row mb-2">
                        <div class="col-12">
                            <form id="timelog-filter-form" method="GET" action="{{ url()->current() }}" class="card">
                                <div class="card-body d-flex flex-column flex-md-row align-items-start align-items-md-center gap-2">
                                    <div class="d-flex align-items-center gap-2 flex-wrap w-100">
                                        <label for="range" class="mb-0 me-2 fw-semibold">Show:</label>

                                        @php $selectedRange = request()->get('range', 'all'); @endphp

                                        <input type="hidden" name="range" id="range-input" value="{{ $selectedRange }}">

                                        <div class="btn-group btn-group-sm" role="tablist" aria-label="Timelog range quick filters">
                                            <button type="button" class="btn @if($selectedRange==='daily') btn-primary @else btn-outline-secondary @endif" data-range="daily">Daily</button>
                                            <button type="button" class="btn @if($selectedRange==='weekly') btn-primary @else btn-outline-secondary @endif" data-range="weekly">Weekly</button>
                                            <button type="button" class="btn @if($selectedRange==='monthly') btn-primary @else btn-outline-secondary @endif" data-range="monthly">Monthly</button>
                                            <button type="button" class="btn @if($selectedRange==='yearly') btn-primary @else btn-outline-secondary @endif" data-range="yearly">Yearly</button>
                                            <button type="button" class="btn @if($selectedRange==='all') btn-primary @else btn-outline-secondary @endif" data-range="all">All</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <script>
                        (function() {
                            const form = document.getElementById('timelog-filter-form');
                            const input = document.getElementById('range-input');
                            const buttons = form.querySelectorAll('button[data-range]');
                            const select = document.getElementById('range-select');

                            // Click on quick buttons
                            buttons.forEach(btn => {
                                btn.addEventListener('click', function(e) {
                                    const val = this.getAttribute('data-range');
                                    input.value = val;
                                    // update select to keep UI in sync
                                    if (select) select.value = val;
                                    // submit form (use GET)
                                    form.submit();
                                });
                            });

                            // Select change
                            if (select) {
                                select.addEventListener('change', function() {
                                    input.value = this.value;
                                    form.submit();
                                });
                            }

                            // Accessibility: allow keyboard activation for buttons
                            buttons.forEach(btn => btn.setAttribute('tabindex', '0'));
                        })();
                    </script>

                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-2">
                                <div class="card-header text-body d-flex justify-content-between align-items-center">
                                    <span>ATTENDANCE</span>
                                </div>
                                <div class="card-body" id="allattendance">
                                    <div class="table-responsive">
                                        <table id="datatablesSimple" class="table table-sm table-striped align-middle">
                                            @php
                                            $tableHeader = '<tr>
                                                <th>DATE</th>
                                                <th>IN</th>
                                                <th>OUT</th>
                                                <th>LOCATION</th>
                                            </tr>';
                                            @endphp
                                            <thead>{!! $tableHeader !!}</thead>
                                            <tfoot>{!! $tableHeader !!}</tfoot>
                                            <tbody>
                                                @if(isset($timelogDays) && $timelogDays->count() > 0)
                                                @php
                                                // Group entries by date (use parsed recorded_at_dt if available)
                                                $groups = $timelogDays->groupBy(function($item) {
                                                if (isset($item->recorded_at_dt) && $item->recorded_at_dt) {
                                                return $item->recorded_at_dt->toDateString();
                                                }
                                                return \Carbon\Carbon::parse($item->recorded_at)->toDateString();
                                                });
                                                @endphp

                                                @foreach($groups as $date => $entries)
                                                @php
                                                // Ensure entries sorted ascending by time
                                                $sorted = collect($entries)->sortBy(function($e) {
                                                return isset($e->recorded_at_dt) && $e->recorded_at_dt ? $e->recorded_at_dt->timestamp : strtotime($e->recorded_at);
                                                });

                                                // first clock-in (earliest)
                                                $firstIn = $sorted->firstWhere('action', 'clock_in');
                                                // last clock-out (latest)
                                                $lastOut = $sorted->where('action', 'clock_out')->last();

                                                // readable values
                                                $inTime = $firstIn && isset($firstIn->recorded_at_dt) && $firstIn->recorded_at_dt ? $firstIn->recorded_at_dt->format('h:i A') : ($firstIn && isset($firstIn->recorded_at) ? \Carbon\Carbon::parse($firstIn->recorded_at)->format('h:i A') : '-');
                                                $outTime = $lastOut && isset($lastOut->recorded_at_dt) && $lastOut->recorded_at_dt ? $lastOut->recorded_at_dt->format('h:i A') : ($lastOut && isset($lastOut->recorded_at) ? \Carbon\Carbon::parse($lastOut->recorded_at)->format('h:i A') : '-');

                                                // Prefer lat/lng links if available, else use location string
                                                $inLoc = null;
                                                if ($firstIn) {
                                                if (!empty($firstIn->lat) && !empty($firstIn->lng)) {
                                                $inLoc = $firstIn->lat . ',' . $firstIn->lng;
                                                } elseif (!empty($firstIn->location)) {
                                                $inLoc = $firstIn->location;
                                                }
                                                }

                                                $outLoc = null;
                                                if ($lastOut) {
                                                if (!empty($lastOut->lat) && !empty($lastOut->lng)) {
                                                $outLoc = $lastOut->lat . ',' . $lastOut->lng;
                                                } elseif (!empty($lastOut->location)) {
                                                $outLoc = $lastOut->location;
                                                }
                                                }
                                                @endphp

                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($date)->format('Y-m-d') }}</td>
                                                    <td>{{ $inTime }}</td>
                                                    <td>{{ $outTime }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            @if($inLoc)
                                                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($inLoc) }}" target="_blank" rel="noopener noreferrer" class="text-success" title="Clock-in: {{ $inLoc }}">
                                                                <i data-feather="log-in" style="width:20px; height:20px;"></i>
                                                            </a>
                                                            @else
                                                            <span class="text-muted" title="No clock-in location">-</span>
                                                            @endif

                                                            @if($outLoc)
                                                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($outLoc) }}" target="_blank" rel="noopener noreferrer" class="text-danger" title="Clock-out: {{ $outLoc }}">
                                                                <i data-feather="log-out" style="width:20px; height:20px;"></i>
                                                            </a>
                                                            @else
                                                            <span class="text-muted" title="No clock-out location">-</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @else
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">No timelog records found.</td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </main>
            @include('user.partials.user_footer')
            @include('user.apps.timelog.geolocation')

</body>


</html>