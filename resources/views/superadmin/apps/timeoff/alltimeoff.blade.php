<!DOCTYPE html>
<html lang="en">
@include('superadmin.partials.header')

<body class="nav-fixed">
    @include('superadmin.partials.topnav')
    <div id="layoutSidenav">
        @include('superadmin.partials.sidenav')
        <div id="layoutSidenav_content">
            <main>
                <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-2">
                    <div class="container-fluid px-4">
                        <div class="page-header-content">
                            <div class="row align-items-center justify-content-between pt-3">
                                <div class="col-auto mb-2">
                                    <h1 class="page-header-title text-body d-flex align-items-center">
                                        <a href="{{ route('superadmin_timeoff') }}" class="btn rounded-circle shadow-sm d-flex align-items-center justify-content-center me-2" style="width:50px; height:50px; padding:0;">
                                            <i data-feather="arrow-left-circle" class="text-primary" style="width:40px; height:40px;"></i>
                                        </a>
                                        @if($status == 'all')
                                        All Leave application
                                        @elseif($status == 'pending')
                                        Pending Leave application
                                        @elseif($status == 'approved')
                                        Approved Leave application
                                        @elseif($status == 'disapproved')
                                        Disapproved Leave application
                                        @endif
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <!-- Main page content-->
                <!-- All Request Container -->
                <div class="container-fluid px-4 mt-2">
                    @if (session('success'))
                    <div class="alert alert-success alert-sm py-1 px-2">
                        {{ session('success') }}
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-2" id="allrequest">
                                <div class="card-header text-body d-flex justify-content-between align-items-center">
                                    <span>LEAVE DETAILS</span>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="datatablesSimple" class="table table-sm table-striped align-middle">
                                            {{-- Table Header/Footer Partial --}}
                                            @php
                                            $tableHeader = '<tr>
                                                <th>Employee Name</th>
                                                <th>Branch</th>
                                                <th>Type</th>
                                                <th>Request date</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                                <th>Approved by</th>
                                            </tr>';
                                            @endphp
                                            <thead>{!! $tableHeader !!}</thead>
                                            <tfoot>{!! $tableHeader !!}</tfoot>
                                            <tbody>
                                                @foreach($timeOffs as $timeOff)
                                                @php
                                                $approvedAdmin = $admins->firstWhere('id', $timeOff->leaveapprovedby);
                                                @endphp
                                                <tr>
                                                    <td>
                                                        @php
                                                        $leaveUser = $users->firstWhere('employeenumber', $timeOff->leaveby);
                                                        @endphp
                                                        {{ $leaveUser ? $leaveUser->lastname . ', ' . $leaveUser->firstname . ' ' . $leaveUser->middlename : $timeOff->leaveby }}
                                                        <span class="text-muted fst-italic">{{ $timeOff->leaveby }}</span>
                                                    </td>
                                                    <td>
                                                        @php
                                                        $leaveUser = $users->firstWhere('employeenumber', $timeOff->leaveby);
                                                        @endphp
                                                        {{ $leaveUser ? $leaveUser->branchname : '' }}
                                                    </td>
                                                    <td>{{ $timeOff->leavetype }}</td>
                                                    <td>{{ $timeOff->leaverequestdate }}</td>
                                                    <td>
                                                        @if($timeOff->leavestatus == 'approved')
                                                        <span class="text-success">Approved</span>
                                                        @elseif($timeOff->leavestatus == 'pending')
                                                        <span class="text-warning">Pending</span>
                                                        @elseif($timeOff->leavestatus == 'cancelled')
                                                        <span class="text-danger">Cancelled</span>
                                                        @else
                                                        <span class="text-danger">Disapproved</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $timeOff->leavedatefrom }} &mdash; {{ $timeOff->leavedateto }} <br> <span class="fst-italic text-muted">{{ $timeOff->leavedays }} @if($timeOff->leavedays > 1) days @else day @endif</span></td>
                                                    <td>
                                                        {{ $approvedAdmin ? $approvedAdmin->lastname . ', ' . $approvedAdmin->firstname : $timeOff->leaveapprovedby }}
                                                        <span class="text-muted small">{{ $timeOff->leaveapproveddate }}</span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

        </div>
    </div>
    @include('superadmin.partials.footer')


</body>


</html>