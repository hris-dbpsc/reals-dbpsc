<?php

use Carbon\Carbon;
?>

<!DOCTYPE html>
<html lang="en">
@include('superadmin.header')

<body class="nav-fixed">
    @include('superadmin.topnav')
    <div id="layoutSidenav">
        @include('superadmin.sidenav')
        <div id="layoutSidenav_content">
            <main>
                <header class="page-header bg-gray-200 pb-10">
                    <div class="container-fluid px-4">
                        <div class="page-header-content pt-4">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-auto mt-2">
                                    <h2 class="page-header-title text-body d-flex align-items-center" style="font-size:1.5rem;">
                                        <span class="page-header-icon me-2 text-body d-flex justify-content-center align-items-center" style="width:28px; height:28px;">
                                            <i data-feather="users" style="width:30px; height:30px;"></i>
                                        </span>
                                        WorkForce
                                    </h2>
                                    <div class="page-header-subtitle text-body mt-2">Work force Management System</div>

                                </div>
                                <div class="col-auto mt-4">

                                </div>
                                <div class="col-auto mt-4">
                                    <a href="{{ route('superadmin_apps') }}" class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width:50px; height:50px; padding:0;">
                                        <i data-feather="arrow-left-circle" class="text-primary" style="width:40px; height:40px;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <!-- Main page content-->

                <div class="container-fluid px-4 mt-n10">
                    <div class="row">
                        @if (session('success'))
                        <div class="alert alert-success alert-sm py-1 px-2">
                            {{ session('success') }}
                        </div>
                        @endif
                        <div class="col-xl-3 mb-2">
                            <!-- CARD 1-->
                            <a class="card lift lift-sm h-100" href="javascript:void(0);" onclick="showRequestContainer('createrequest');">
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="me-3">
                                            <i class="feather text-info mb-1" data-feather="plus-circle" style="width: 64px; height: 64px;"></i>
                                            <h3 class="fw-bold text-body">Create a Request</h3>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 mb-2">
                            <!-- CARD 2-->
                            <a class="card lift lift-sm h-100" href="javascript:void(0);" onclick="showRequestContainer('allrequest');">
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="me-3">
                                            <i class="feather text-primary mb-1" data-feather="arrow-down-circle" style="width: 64px; height: 64px;"></i>
                                            <span class="badge bg-light text-primary ms-2 position-absolute top-0 end-0 mt-2 me-2" style="font-size: 1.2rem; padding: 0.6em 1em;">
                                                {{ \App\Models\WorkforceWatson::whereYear('created_at', now()->year)->count() }}
                                            </span>
                                            <h3 class="fw-bold text-body">All Request</h3>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 mb-2">
                            <!-- CARD 3-->
                            <a class="card lift lift-sm h-100" href="javascript:void(0);" onclick="showRequestContainer('pendingrequest');">
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="me-3">
                                            <i class="feather text-warning mb-1" data-feather="alert-circle" style="width: 64px; height: 64px;"></i>
                                            <span class="badge bg-light text-primary ms-2 position-absolute top-0 end-0 mt-2 me-2" style="font-size: 1.2rem; padding: 0.6em 1em;">
                                                {{ \App\Models\WorkforceWatson::where('status', 'pending')->whereYear('created_at', now()->year)->count() }}
                                            </span>
                                            <h3 class="fw-bold text-body">Pending</h3>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 mb-2">
                            <!-- CARD 4-->
                            <a class="card lift lift-sm h-100" href="javascript:void(0);" onclick="showRequestContainer('completedrequest');">
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="me-3">
                                            <i class="feather text-success mb-1" data-feather="check-circle" style="width: 64px; height: 64px;"></i>
                                            <span class="badge bg-light text-primary ms-2 position-absolute top-0 end-0 mt-2 me-2" style="font-size: 1.2rem; padding: 0.6em 1em;">
                                                {{ \App\Models\WorkforceWatson::where('status', 'completed')->whereYear('created_at', now()->year)->count() }}
                                            </span>
                                            <h3 class="fw-bold text-body">Completed</h3>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                    </div>
                </div>

                <!-- Create Request Container -->
                <div class="container-fluid px-4 mt-2">
                    <div class="row">
                        <div class="col-12">
                            <!-- Account details card-->
                            <div class="card mb-2 d-none" id="createrequest">
                                <div class="card-header text-body">Request Details</div>
                                <div class="card-body">
                                    <form action="{{ route('superadmin_workforce_submit')}}" method="POST">
                                        @csrf

                                        <!-- REQUEST TYPE-->
                                        <div class="row gx-3 mb-2">
                                            <div class="col-md-6 form-floating mb-2 mb-md-0">
                                                <select class="form-select" name="requesttype" id="requesttype" required onchange="handleRequestTypeChange()">
                                                    <option value="" disabled selected>Select Request Type</option>
                                                    <option value="ADDITIONAL STAFF">ADDITIONAL STAFF</option>
                                                    <option value="CESSATION">CESSATION</option>
                                                    <option value="NEW STORE DEPLOYMENT">NEW STORE DEPLOYMENT</option>
                                                    <option value="RELIEVER">RELIEVER</option>
                                                    <option value="REPLACEMENT">REPLACEMENT</option>
                                                    <option value="RESHUFFLE">RESHUFFLE</option>
                                                    <option value="RESIGNATION">RESIGNATION</option>
                                                    <option value="SHARING">SHARING</option>
                                                    <option value="TRANSFER">TRANSFER</option>
                                                </select>
                                                <label for="requesttype">Request Type</label>
                                            </div>


                                        </div>
                                        <!-- REQUEST TYPE-->

                                        <!-- TARGET BRANCH -->
                                        <div class="row gx-3 mb-2" id="targetBranchSection" style="display:none;">
                                            <div class="col-md-6 form-floating mb-2 mb-md-0">
                                                <select class="form-select" name="branchname">
                                                    <option value="" disabled selected>Select Branch</option>
                                                    @foreach($branches as $branch)
                                                    <option value="{{ $branch->branchname }}">{{ $branch->branchname }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="branchname">Target Branch</label>
                                            </div>
                                        </div>
                                        <!-- TARGET BRANCH -->

                                        <!-- TRANSFER -->
                                        <div class="row gx-3 mb-2" id="transferSection" style="display:none;">
                                            <div class="col-md-6 form-floating mb-2 mb-md-0">
                                                <!-- BRANCH -->
                                                <div class="row gx-3 mb-2">
                                                    <div class="col-md-6 form-floating mb-2 mb-md-0">
                                                        <select class="form-select" name="branchtransferfrom">
                                                            <option value="" disabled selected>Select Branch</option>
                                                            @foreach($branches as $branch)
                                                            <option value="{{ $branch->branchname }}">{{ $branch->branchname }}</option>
                                                            @endforeach
                                                        </select>
                                                        <label for="branchtransferfrom">Transfer From</label>
                                                    </div>
                                                    <div class="col-md-6 form-floating mb-2 mb-md-0">
                                                        <select class="form-select" name="branchtransferto">
                                                            <option value="" disabled selected>Select Branch</option>
                                                            @foreach($branches as $branch)
                                                            <option value="{{ $branch->branchname }}">{{ $branch->branchname }}</option>
                                                            @endforeach
                                                        </select>
                                                        <label for="branchtransferto">Transfer To</label>
                                                    </div>
                                                </div>
                                                <!-- BRANCH -->
                                                <!-- EMPLOYEE -->
                                                <div class="row gx-3 mb-2">
                                                    <div class="col-md-12 form-floating">
                                                        <input type="text" class="form-control" name="employeestransferred" placeholder="Enter remarks" required>
                                                        <label for="employeestransferred">Employee</label>
                                                    </div>
                                                </div>
                                                <!-- EMPLOYEE -->
                                            </div>
                                        </div>
                                        <!-- TRANSFER -->


                                        <!-- RESHUFFLE -->
                                        <div class="row gx-3 mb-2" id="reshuffleSection" style="display:none;">
                                            <div class="col-md-6 form-floating mb-2 mb-md-0">
                                                <!-- COUNT -->
                                                <div class="row gx-3 mb-2">
                                                    <div class="col-md-12 form-floating mb-2 mb-md-0">
                                                        <select class="form-select" name="reshuffle_number" id="reshuffle_number" onchange="updateReshuffleLoop()">
                                                            <option value="" disabled selected>Select Number</option>
                                                            @for ($i = 1; $i <= 10; $i++)
                                                                <option value="{{ $i }}">{{ $i }}</option>
                                                                @endfor
                                                        </select>
                                                        <label for="reshuffle_number">Reshuffle</label>
                                                    </div>
                                                </div>

                                                <!-- RESHUFFLE LOOP COUNT -->
                                                <div id="reshuffleloop"></div>
                                            </div>
                                        </div>
                                        <!-- RESHUFFLE -->


                                        <!-- CLIENT REMARKS -->
                                        <div id="remarksSection" class="col-12" style="display:none;">
                                            <div class="row gx-3 mb-2">
                                                <div class="col-md-6 form-floating mb-2 mb-md-0">
                                                    <textarea class="form-control" name="clientremarks" placeholder="Enter remarks" style="height: 100px;" required></textarea>
                                                    <label for="clientremarks">Remarks</label>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- CLIENT REMARKS -->

                                        <!-- Submit button-->
                                        <!-- Add Admin Button triggers modal -->
                                        <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#confirmAddAdminModal">
                                            <i class="me-1" data-feather="plus"></i>
                                            Add Request
                                        </button>
                                        <!-- Submit button-->

                                        <!-- Confirmation Modal -->
                                        <div class="modal fade" id="confirmAddAdminModal" tabindex="-1" aria-labelledby="confirmAddAdminModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="confirmAddAdminModalLabel">Confirm Add Request</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to add this Request?
                                                    </div>
                                                    <div class="modal-footer justify-content-center">
                                                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                                                            <i class="me-1" data-feather="x"></i>
                                                            Cancel
                                                        </button>
                                                        <button type="submit" class="btn btn-outline-primary" id="addRequestBtn" onclick="disableAddRequestBtn(this)">
                                                            <i class="me-1" data-feather="plus"></i>
                                                            Add Request
                                                        </button>
                                                        <script>
                                                            function disableAddRequestBtn(btn) {
                                                                btn.disabled = true;
                                                                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Processing...';
                                                                btn.form.submit();
                                                            }
                                                        </script>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Confirmation Modal -->

                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Create Request Container -->

                <!-- All Request Container -->
                <div class="container-fluid px-4 mt-2">
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-2 d-none" id="allrequest">
                                <div class="card-header text-body d-flex justify-content-between align-items-center">
                                    <span>ALL REQUESTS</span>
                                    <form method="GET" action="" class="d-flex align-items-center" style="gap: 0.5rem;">
                                        <label for="year_filter" class="mb-0 me-2">Year:</label>
                                        <select name="year" id="year_filter" class="form-select form-select-sm" style="width:auto;">
                                            @php
                                            $currentYear = date('Y');
                                            $startYear = $currentYear - 2;
                                            $selectedYear = request('year', $currentYear);
                                            @endphp
                                            @for ($year = $currentYear; $year >= $startYear; $year--)
                                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                                            @endfor
                                        </select>
                                        <script>
                                            document.getElementById('year_filter').addEventListener('change', function() {
                                                const year = this.value;
                                                fetch(`?year=${year}`)
                                                    .then(response => response.text())
                                                    .then(html => {
                                                        const parser = new DOMParser();
                                                        const doc = parser.parseFromString(html, 'text/html');
                                                        const newTbody = doc.querySelector('#allrequest tbody');
                                                        const oldTbody = document.querySelector('#allrequest tbody');
                                                        if (newTbody && oldTbody) {
                                                            oldTbody.innerHTML = newTbody.innerHTML;
                                                            // Re-initialize feather icons after updating table
                                                            if (window.feather) {
                                                                window.feather.replace();
                                                            }
                                                        }
                                                    });
                                            });
                                        </script>
                                    </form>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive" style="overflow: visible !important;">
                                        <table id="datatablesSimple" class="table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Request Type</th>
                                                    <th>Date</th>
                                                    <th>TAT</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Request Type</th>
                                                    <th>Date</th>
                                                    <th>TAT</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                @foreach($workforces->where(fn($item) => optional($item->created_at)->format('Y') == $selectedYear) as $w)
                                                <tr>
                                                    <td>{{ $w->id }}</td>
                                                    <td>{{ $w->requesttype }}</td>
                                                    <td>{{ $w->requestdate }}</td>
                                                    <td>
                                                        @if($w->requestdate)
                                                        @php
                                                        $s = Carbon::parse($w->requestdate)->startOfDay();
                                                        $e = $w->attendeddate ? Carbon::parse($w->attendeddate)->startOfDay() : Carbon::now()->startOfDay();
                                                        $hol = ['2025-01-01','2025-04-17','2025-04-18','2025-04-09','2025-05-01','2025-06-12','2025-08-21','2025-08-25','2025-11-01','2025-11-30','2025-12-25','2025-12-30'];
                                                        $bd = 0;
                                                        for ($d = $s->copy(); $d->lte($e); $d->addDay())
                                                        if (!$d->isWeekend() && !in_array($d->format('Y-m-d'), $hol)) $bd++;
                                                        $c = $bd >= 6 ? 'red' : (in_array($bd, [3,4,5]) ? 'orange' : '');
                                                        @endphp
                                                        <span @if($c) style="color:{{ $c }};font-weight:bold;" @endif>
                                                            {{ $bd }} day{{ $bd !== 1 ? 's' : '' }}
                                                        </span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $w->status }}</td>
                                                    <td>
                                                        <!-- View Button triggers modal -->
                                                        <a class="btn btn-xs" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#viewRequestModal{{ $w->id }}">
                                                            <i data-feather="eye" style="width:1.2em;height:1.2em;"></i>
                                                        </a>

                                                        <!-- Modal for viewing request details -->
                                                        <div class="modal fade" id="viewRequestModal{{ $w->id }}" tabindex="-1" aria-labelledby="viewRequestModalLabel{{ $w->id }}" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="viewRequestModalLabel{{ $w->id }}">{{ $w->id }}: {{ $w->requesttype }}</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="card border-0 shadow-sm">
                                                                            <div class="card-body">
                                                                                <dl class="row mb-0">
                                                                                    <dt class="col-sm-5">Request By</dt>
                                                                                    <dd class="col-sm-7">{{ $w->requestby }}</dd>

                                                                                    <dt class="col-sm-5">Date</dt>
                                                                                    <dd class="col-sm-7">{{ $w->requestdate }}</dd>

                                                                                    <dt class="col-sm-5">Status</dt>
                                                                                    <dd class="col-sm-7">{{ $w->status }}</dd>

                                                                                    @if(!empty($w->branchname))
                                                                                    <dt class="col-sm-5">Branch</dt>
                                                                                    <dd class="col-sm-7">{{ $w->branchname }}</dd>
                                                                                    @endif

                                                                                    @if(!empty($w->branchtransferfrom))
                                                                                    <dt class="col-sm-5">Transfer From</dt>
                                                                                    <dd class="col-sm-7">{{ $w->branchtransferfrom }}</dd>
                                                                                    @endif

                                                                                    @if(!empty($w->branchtransferto))
                                                                                    <dt class="col-sm-5">Transfer To</dt>
                                                                                    <dd class="col-sm-7">{{ $w->branchtransferto }}</dd>
                                                                                    @endif

                                                                                    @if(!empty($w->employeestransferred))
                                                                                    <dt class="col-sm-5">Employee Transferred</dt>
                                                                                    <dd class="col-sm-7">{{ $w->employeestransferred }}</dd>
                                                                                    @endif

                                                                                    @if(!empty($w->reshuffle_number))
                                                                                    <dt class="col-sm-5">Reshuffle Number</dt>
                                                                                    <dd class="col-sm-7">{{ $w->reshuffle_number }}</dd>
                                                                                    @endif

                                                                                    @if(!empty($w->employeesreshuffled) || !empty($w->branchreshufflefrom) || !empty($w->branchreshuffleto))
                                                                                    <dt class="col-sm-5">Reshuffle Details</dt>
                                                                                    <dd class="col-sm-7">
                                                                                        @php
                                                                                        $employees = is_array($w->employeesreshuffled) ? $w->employeesreshuffled : (empty($w->employeesreshuffled) ? [] : explode(',', $w->employeesreshuffled));
                                                                                        $froms = is_array($w->branchreshufflefrom) ? $w->branchreshufflefrom : (empty($w->branchreshufflefrom) ? [] : explode(',', $w->branchreshufflefrom));
                                                                                        $tos = is_array($w->branchreshuffleto) ? $w->branchreshuffleto : (empty($w->branchreshuffleto) ? [] : explode(',', $w->branchreshuffleto));
                                                                                        $max = max(count($employees), count($froms), count($tos));
                                                                                        @endphp
                                                                                        @if($max > 0)
                                                                                        <div class="table-responsive">
                                                                                            <table class="table table-sm table-bordered mb-0">
                                                                                                <thead>
                                                                                                    <tr>
                                                                                                        <th>Employee Reshuffled</th>
                                                                                                        <th>Reshuffle From</th>
                                                                                                        <th>Reshuffle To</th>
                                                                                                    </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                    @for($i = 0; $i < $max; $i++)
                                                                                                        <tr>
                                                                                                        <td>{{ isset($employees[$i]) ? trim($employees[$i]) : '' }}</td>
                                                                                                        <td>{{ isset($froms[$i]) ? trim($froms[$i]) : '' }}</td>
                                                                                                        <td>{{ isset($tos[$i]) ? trim($tos[$i]) : '' }}</td>
                                                </tr>
                                                @endfor
                                            </tbody>
                                        </table>
                                    </div>
                                    @endif
                                    </dd>
                                    @endif

                                    @if(!empty($w->clientremarks))
                                    <dt class="col-sm-5">Remarks</dt>
                                    <dd class="col-sm-7">{{ $w->clientremarks }}</dd>
                                    @endif

                                    @if(!empty($w->attendeddate))
                                    <dt class="col-sm-5">Attended Date</dt>
                                    <dd class="col-sm-7">{{ $w->attendeddate }}</dd>
                                    @endif

                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <a class="btn btn-xs" href=""><i data-feather="edit" style="width:1.2em;height:1.2em;"></i></a>
        <form action="" method="POST" style="display:inline;">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-xs" style="padding:0.15rem 0.4rem;" onclick="return confirm('Are you sure?');">
                <i data-feather="trash-2" style="width:1.2em;height:1.2em;"></i>
            </button>
        </form>
        </td>
        </tr>
        @endforeach
        </tbody>
        </table>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    <!-- All Request Container -->


    <!-- Pending Request Container -->
    <div class="container-fluid px-4 mt-2">
        <div class="row">
            <div class="col-12">
                <div class="card mb-2 d-none" id="pendingrequest">
                    <div class="card-header text-body">PENDING REQUESTS</div>
                    <div class="card-body">
                        <div class="table-responsive" style="overflow: visible !important;">
                            <table id="pendingTable" class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Request Type</th>
                                        <th>Date</th>
                                        <th>TAT</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Request Type</th>
                                        <th>Date</th>
                                        <th>TAT</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach($workforces->where(fn($item) => optional($item->created_at)->format('Y') == date('Y') && $item->status == 'pending') as $w)
                                    <tr>
                                        <td>{{ $w->id }}</td>
                                        <td>{{ $w->requesttype }}</td>
                                        <td>{{ $w->requestdate }}</td>
                                        <td>
                                            @if($w->requestdate)
                                            @php
                                            $s = Carbon::parse($w->requestdate)->startOfDay();
                                            $e = $w->attendeddate ? Carbon::parse($w->attendeddate)->startOfDay() : Carbon::now()->startOfDay();
                                            $hol = ['2025-01-01','2025-04-17','2025-04-18','2025-04-09','2025-05-01','2025-06-12','2025-08-21','2025-08-25','2025-11-01','2025-11-30','2025-12-25','2025-12-30'];
                                            $bd = 0;
                                            for ($d = $s->copy(); $d->lte($e); $d->addDay())
                                            if (!$d->isWeekend() && !in_array($d->format('Y-m-d'), $hol)) $bd++;
                                            $c = $bd >= 6 ? 'red' : (in_array($bd, [3,4,5]) ? 'orange' : '');
                                            @endphp
                                            <span @if($c) style="color:{{ $c }};font-weight:bold;" @endif>
                                                {{ $bd }} day{{ $bd !== 1 ? 's' : '' }}
                                            </span>
                                            @endif
                                        </td>
                                        <td>{{ $w->status }}</td>
                                        <td>
                                            <!-- View Button triggers modal -->
                                            <a class="btn btn-xs" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#viewPendingRequestModal{{ $w->id }}">
                                                <i data-feather="eye" style="width:1.2em;height:1.2em;"></i>
                                            </a>

                                            <!-- Modal for viewing request details -->
                                            <div class="modal fade" id="viewPendingRequestModal{{ $w->id }}" tabindex="-1" aria-labelledby="viewPendingRequestModalLabel{{ $w->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="viewPendingRequestModalLabel{{ $w->id }}">{{ $w->id }}: {{ $w->requesttype }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="card border-0 shadow-sm">
                                                                <div class="card-body">
                                                                    <dl class="row mb-0">
                                                                        <dt class="col-sm-5">Request By</dt>
                                                                        <dd class="col-sm-7">{{ $w->requestby }}</dd>

                                                                        <dt class="col-sm-5">Date</dt>
                                                                        <dd class="col-sm-7">{{ $w->requestdate }}</dd>

                                                                        <dt class="col-sm-5">Status</dt>
                                                                        <dd class="col-sm-7">{{ $w->status }}</dd>

                                                                        @if(!empty($w->branchname))
                                                                        <dt class="col-sm-5">Branch</dt>
                                                                        <dd class="col-sm-7">{{ $w->branchname }}</dd>
                                                                        @endif

                                                                        @if(!empty($w->branchtransferfrom))
                                                                        <dt class="col-sm-5">Transfer From</dt>
                                                                        <dd class="col-sm-7">{{ $w->branchtransferfrom }}</dd>
                                                                        @endif

                                                                        @if(!empty($w->branchtransferto))
                                                                        <dt class="col-sm-5">Transfer To</dt>
                                                                        <dd class="col-sm-7">{{ $w->branchtransferto }}</dd>
                                                                        @endif

                                                                        @if(!empty($w->employeestransferred))
                                                                        <dt class="col-sm-5">Employee Transferred</dt>
                                                                        <dd class="col-sm-7">{{ $w->employeestransferred }}</dd>
                                                                        @endif

                                                                        @if(!empty($w->reshuffle_number))
                                                                        <dt class="col-sm-5">Reshuffle Number</dt>
                                                                        <dd class="col-sm-7">{{ $w->reshuffle_number }}</dd>
                                                                        @endif

                                                                        @if(!empty($w->employeesreshuffled) || !empty($w->branchreshufflefrom) || !empty($w->branchreshuffleto))
                                                                        <dt class="col-sm-5">Reshuffle Details</dt>
                                                                        <dd class="col-sm-7">
                                                                            @php
                                                                            $employees = is_array($w->employeesreshuffled) ? $w->employeesreshuffled : (empty($w->employeesreshuffled) ? [] : explode(',', $w->employeesreshuffled));
                                                                            $froms = is_array($w->branchreshufflefrom) ? $w->branchreshufflefrom : (empty($w->branchreshufflefrom) ? [] : explode(',', $w->branchreshufflefrom));
                                                                            $tos = is_array($w->branchreshuffleto) ? $w->branchreshuffleto : (empty($w->branchreshuffleto) ? [] : explode(',', $w->branchreshuffleto));
                                                                            $max = max(count($employees), count($froms), count($tos));
                                                                            @endphp
                                                                            @if($max > 0)
                                                                            <div class="table-responsive">
                                                                                <table class="table table-sm table-bordered mb-0">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>Employee Reshuffled</th>
                                                                                            <th>Reshuffle From</th>
                                                                                            <th>Reshuffle To</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        @for($i = 0; $i < $max; $i++)
                                                                                            <tr>
                                                                                            <td>{{ isset($employees[$i]) ? trim($employees[$i]) : '' }}</td>
                                                                                            <td>{{ isset($froms[$i]) ? trim($froms[$i]) : '' }}</td>
                                                                                            <td>{{ isset($tos[$i]) ? trim($tos[$i]) : '' }}</td>
                                    </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                        @endif
                        </dd>
                        @endif

                        @if(!empty($w->clientremarks))
                        <dt class="col-sm-5">Remarks</dt>
                        <dd class="col-sm-7">{{ $w->clientremarks }}</dd>
                        @endif

                        @if(!empty($w->attendeddate))
                        <dt class="col-sm-5">Attended Date</dt>
                        <dd class="col-sm-7">{{ $w->attendeddate }}</dd>
                        @endif

                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <a class="btn btn-xs" href=""><i data-feather="edit" style="width:1.2em;height:1.2em;"></i></a>
    <form action="" method="POST" style="display:inline;">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-xs" style="padding:0.15rem 0.4rem;" onclick="return confirm('Are you sure?');">
            <i data-feather="trash-2" style="width:1.2em;height:1.2em;"></i>
        </button>
    </form>
    </td>
    </tr>
    @endforeach
    </tbody>
    </table>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    <!-- Pending Request Container -->

    <!-- Completed Request Container -->
    <div class="container-fluid px-4 mt-2">
        <div class="row">
            <div class="col-12">
                <div class="card mb-2 d-none" id="completedrequest">
                    <div class="card-header text-body">COMPLETED REQUESTS</div>
                    <div class="card-body">
                        <p>No requests found.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Completed Request Container -->

    </main>
    @include('superadmin.footer')
</body>

<script>
    function showRequestContainer(id) {
        ['createrequest', 'allrequest', 'pendingrequest', 'completedrequest'].forEach(cid => {
            const el = document.getElementById(cid);
            if (el) el.classList.toggle('d-none', cid !== id);
        });
    }
    window.showRequestContainer = showRequestContainer;

    function handleRequestTypeChange() {
        const type = document.getElementById('requesttype')?.value;
        const s = id => document.getElementById(id);
        const hideAll = () => ['targetBranchSection', 'transferSection', 'reshuffleSection', 'remarksSection'].forEach(id => s(id).style.display = 'none');
        if (!s('targetBranchSection') || !s('transferSection') || !s('reshuffleSection') || !s('remarksSection')) return;
        hideAll();
        if ([
                'ADDITIONAL STAFF', 'CESSATION', 'NEW STORE DEPLOYMENT', 'RELIEVER', 'REPLACEMENT', 'RESIGNATION', 'SHARING'
            ].includes(type)) {
            s('targetBranchSection').style.display = 'block';
            s('remarksSection').style.display = 'block';
        } else if (type === 'TRANSFER') {
            s('transferSection').style.display = 'block';
            s('remarksSection').style.display = 'block';
        } else if (type === 'RESHUFFLE') {
            s('reshuffleSection').style.display = 'block';
            s('remarksSection').style.display = 'block';
        }
    }

    function updateReshuffleLoop() {
        const n = document.getElementById('reshuffle_number');
        const loop = document.getElementById('reshuffleloop');
        if (!n || !loop) return;
        const count = parseInt(n.value) || 0;
        let html = '';
        const branchOptions = document.getElementById('reshuffleBranchOptions')?.innerHTML || '';
        for (let i = 1; i <= count; i++) {
            html += `
<div class="row gx-3 mb-2">
<div class="col-md-12 form-floating mb-2 mb-md-0">
<input type="text" class="form-control" name="employeesreshuffled[]" placeholder="Employee Name #${i}" required>
<label>Employee Name #${i}</label>
</div>
</div>
<div class="row gx-3 mb-2">
<div class="col-md-6 form-floating mb-2 mb-md-0">
<select class="form-select" name="branchreshufflefrom[]" required>
<option value="" disabled selected>Select Branch</option>
${branchOptions}
</select>
<label>Reshuffle From</label>
</div>
<div class="col-md-6 form-floating mb-2 mb-md-0">
<select class="form-select" name="branchreshuffleto[]" required>
<option value="" disabled selected>Select Branch</option>
${branchOptions}
</select>
<label>Reshuffle To</label>
</div>
</div>
`;
        }
        loop.innerHTML = html;
    }

    document.addEventListener('DOMContentLoaded', () => {
        handleRequestTypeChange();
        updateReshuffleLoop();
        if (window.DataTable && document.querySelector('#datatablesSimple')) {
            new DataTable('#datatablesSimple', {
                paging: true,
                pageLength: 10,
                lengthChange: false,
                ordering: true,
                info: true,
                searching: true,
                language: {
                    paginate: {
                        previous: '<span aria-hidden="true">&laquo;</span>',
                        next: '<span aria-hidden="true">&raquo;</span>'
                    }
                }
            });
        }
    });
</script>
<!-- Hidden div for branch options for reshuffle loop -->
<div id="reshuffleBranchOptions" style="display:none;">
    @foreach($branches as $branch)
    <option value="{{ $branch->branchname }}">{{ $branch->branchname }}</option>
    @endforeach
</div>

</html>