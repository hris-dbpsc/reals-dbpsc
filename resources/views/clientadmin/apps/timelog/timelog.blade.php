<!DOCTYPE html>
<html lang="en">
@include('clientadmin.partials.client_header')

<body class="nav-fixed">
    @include('clientadmin.partials.client_topnav')
    <div id="layoutSidenav">
        @include('clientadmin.partials.client_sidenav')
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
                                    <a href="{{ route('clientadmin_apps') }}" class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width:50px; height:50px; padding:0;" aria-label="Back to Apps">
                                        <i data-feather="arrow-left-circle" class="text-primary" style="width:40px; height:40px;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <div class="container-fluid px-4 mt-n10">

                    <!-- Timelog filter: placed after clock-in/out buttons -->
                    <div class="row mb-2">
                        <div class="col-12">
                            <!-- Mobile: show a compact toggle for filters; desktop shows filters expanded -->
                            <div class="d-flex d-md-none justify-content-end mb-2">
                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#timelog-filter-collapse" aria-expanded="false" aria-controls="timelog-filter-collapse">
                                    <i data-feather="filter" class="me-1"></i>
                                    Filters
                                </button>
                            </div>

                            <div class="collapse d-md-block" id="timelog-filter-collapse">
                                <form id="timelog-filter-form" method="GET" action="{{ url()->current() }}" class="card shadow-sm border-0">
                                    <div class="card-body d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3">
                                        <div class="d-flex align-items-center gap-3 flex-wrap w-100">
                                            <div class="d-flex align-items-center gap-2" style="min-width:0;">
                                                <label for="range" class="mb-0 fw-semibold small me-1">Show:</label>
                                                @php $selectedRange = request()->get('range', 'all'); @endphp
                                                <input type="hidden" name="range" id="range-input" value="{{ $selectedRange }}">

                                                <!-- allow horizontal scroll on very small screens so buttons don't wrap awkwardly -->
                                                <div class="btn-group btn-group-sm" role="tablist" aria-label="Timelog range quick filters" style="overflow:auto; white-space:nowrap;">
                                                    <button type="button" class="btn @if($selectedRange==='daily') btn-primary @else btn-outline-secondary @endif" data-range="daily" title="Show today's logs">Daily</button>
                                                    <button type="button" class="btn @if($selectedRange==='weekly') btn-primary @else btn-outline-secondary @endif" data-range="weekly" title="Show this week's logs">Weekly</button>
                                                    <button type="button" class="btn @if($selectedRange==='monthly') btn-primary @else btn-outline-secondary @endif" data-range="monthly" title="Show this month's logs">Monthly</button>
                                                    <button type="button" class="btn @if($selectedRange==='yearly') btn-primary @else btn-outline-secondary @endif" data-range="yearly" title="Show this year's logs">Yearly</button>
                                                    <button type="button" class="btn @if($selectedRange==='all') btn-primary @else btn-outline-secondary @endif" data-range="all" title="Show all logs">All</button>
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-center gap-2" style="min-width:0;">
                                                <label for="branch-select" class="mb-0 fw-semibold small me-1">Branch:</label>
                                                @php $selectedBranch = request()->get('branch', 'all'); @endphp
                                                <select name="branch" id="branch-select" class="form-select form-select-sm w-100 w-md-auto" aria-label="Select branch">
                                                    <option value="all" @if($selectedBranch==='all' ) selected @endif>All Branches</option>
                                                    @if(isset($branches) && $branches->count())
                                                        @foreach($branches as $b)
                                                            <option value="{{ $b }}" @if($selectedBranch===$b) selected @endif>{{ $b }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>

                                        <div class="ms-md-auto mt-2 mt-md-0 d-flex gap-2">
                                            <button type="submit" class="btn btn-sm btn-primary" title="Apply selected filters">
                                                <i data-feather="filter" class="me-1"></i> Apply
                                            </button>
                                            <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-secondary" title="Reset filters">
                                                <i data-feather="refresh-cw" class="me-1"></i> Reset
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <script>
                        (function() {
                            const form = document.getElementById('timelog-filter-form');
                            const input = document.getElementById('range-input');
                            const buttons = form.querySelectorAll('button[data-range]');
                            const branch = document.getElementById('branch-select');

                            // Click on quick buttons: set range and submit
                            buttons.forEach(btn => {
                                btn.addEventListener('click', function(e) {
                                    const val = this.getAttribute('data-range');
                                    input.value = val;
                                    form.submit();
                                });
                            });

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
                                                <th>EMPLOYEE</th>
                                                <th>EMP NO</th>
                                                <th>BRANCH</th>
                                                <th>DATE</th>
                                                <th>IN</th>
                                                <th>OUT</th>
                                                <th>LOCATION</th>
                                            </tr>';
                                            @endphp
                                            <thead>{!! $tableHeader !!}</thead>
                                            <tfoot>{!! $tableHeader !!}</tfoot>
                                            <tbody>
                                                @php
                                                // Controller provides a paginator ($timelogPaginated) and the mapped collection ($timelogCollection)
                                                $pageCollection = isset($timelogCollection) ? $timelogCollection : (isset($timelogDays) ? $timelogDays : collect());
                                                @endphp
                                                @if($pageCollection->count() > 0)
                                                @php
                                                // Group entries by date+employee so each row represents one employee's day
                                                $groups = $pageCollection->groupBy(function($item) {
                                                $date = null;
                                                if (isset($item->recorded_at_dt) && $item->recorded_at_dt) {
                                                $date = $item->recorded_at_dt->toDateString();
                                                } else {
                                                $date = \Carbon\Carbon::parse($item->recorded_at)->toDateString();
                                                }
                                                $emp = $item->employee_employeenumber ?? $item->employeenumber ?? 'unknown';
                                                return $date . '|' . $emp;
                                                });
                                                @endphp

                                                @foreach($groups as $key => $entries)
                                                @php
                                                // entries may belong to same employee on the same date
                                                $sorted = collect($entries)->sortBy(function($e) {
                                                return isset($e->recorded_at_dt) && $e->recorded_at_dt ? $e->recorded_at_dt->timestamp : strtotime($e->recorded_at);
                                                });

                                                $firstIn = $sorted->firstWhere('action', 'clock_in');
                                                $lastOut = $sorted->where('action', 'clock_out')->last();

                                                $inTime = $firstIn && isset($firstIn->recorded_at_dt) && $firstIn->recorded_at_dt ? $firstIn->recorded_at_dt->format('h:i A') : ($firstIn && isset($firstIn->recorded_at) ? \Carbon\Carbon::parse($firstIn->recorded_at)->format('h:i A') : '-');
                                                $outTime = $lastOut && isset($lastOut->recorded_at_dt) && $lastOut->recorded_at_dt ? $lastOut->recorded_at_dt->format('h:i A') : ($lastOut && isset($lastOut->recorded_at) ? \Carbon\Carbon::parse($lastOut->recorded_at)->format('h:i A') : '-');

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

                                                // derive display values from any entry
                                                $sample = $sorted->first();
                                                $displayName = $sample->employee_name ?? ($sample->firstname ?? ($sample->employeenumber ?? 'Unknown'));
                                                $displayEmpNo = $sample->employee_employeenumber ?? ($sample->employeenumber ?? null);
                                                $displayBranch = $sample->employee_branch ?? ($sample->branch_name ?? null);

                                                // extract date from group key
                                                $parts = explode('|', $key);
                                                $date = $parts[0] ?? null;
                                                @endphp

                                                <tr>
                                                    <td>{{ $displayName }}</td>
                                                    <td>{{ $displayEmpNo }}</td>
                                                    <td>{{ $displayBranch ?? '-' }}</td>
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
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    @if(isset($timelogPaginated) && method_exists($timelogPaginated, 'links'))
                                    <div class="mt-2">
                                        {{ $timelogPaginated->appends(request()->query())->links() }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </main>
            @include('clientadmin.partials.client_footer')

</body>


</html>