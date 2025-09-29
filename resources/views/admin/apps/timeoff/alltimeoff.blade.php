<!DOCTYPE html>
<html lang="en">
@include('admin.partials.admin_header')

<body class="nav-fixed">
    @include('admin.partials.admin_topnav')
    <div id="layoutSidenav">
        @include('admin.partials.admin_sidenav')
        <div id="layoutSidenav_content">
            <main>
                <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-2">
                    <div class="container-fluid px-4">
                        <div class="page-header-content">
                            <div class="row align-items-center justify-content-between pt-3">
                                <div class="col-auto mb-2">
                                    <h1 class="page-header-title text-body d-flex align-items-center">
                                        <a href="{{ route('admin_timeoff') }}" class="btn rounded-circle shadow-sm d-flex align-items-center justify-content-center me-2" style="width:50px; height:50px; padding:0;">
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
                                                <th>Action</th>
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
                                                    <td>
                                                        <div class="d-flex justify-content-center gap-1">
                                                            @if($timeOff->leavestatus == 'approved')
                                                            <button type="button" class="btn btn-xs" data-bs-toggle="modal" data-bs-target="#printLeaveModal{{ $timeOff->id }}" title="Print Leave">
                                                                <i data-feather="printer" style="width:1.5em;height:1.5em;"></i>
                                                            </button>
                                                            @else
                                                            <span style="width:34px;display:inline-block;"></span>
                                                            @endif

                                                            <button type="button" class="btn btn-xs" data-bs-toggle="modal" data-bs-target="#viewLeaveModal{{ $timeOff->id }}" title="View Leave">
                                                                <i data-feather="eye" style="width:1.5em;height:1.5em;"></i>
                                                            </button>

                                                            @if($timeOff->leavestatus == 'pending')
                                                            <button type="button" class="btn btn-xs" data-bs-toggle="modal" data-bs-target="#approveLeaveModal{{ $timeOff->id }}" title="Approve Leave">
                                                                <i data-feather="check-circle" style="width:1.5em;height:1.5em;"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-xs" data-bs-toggle="modal" data-bs-target="#disapproveLeaveModal{{ $timeOff->id }}" title="Disapprove Leave">
                                                                <i data-feather="x-circle" style="width:1.5em;height:1.5em;"></i>
                                                            </button>
                                                            @else
                                                            <span style="width:34px;display:inline-block;"></span>
                                                            <span style="width:34px;display:inline-block;"></span>
                                                            @endif
                                                        </div>
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
    @include('admin.partials.admin_footer')


</body>

{{-- MODALS --}}
@foreach($timeOffs as $timeOff)
{{-- View Leave Modal Partial --}}
<div class="modal fade" id="viewLeaveModal{{ $timeOff->id }}" tabindex="-1" aria-labelledby="viewLeaveModalLabel{{ $timeOff->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewLeaveModalLabel{{ $timeOff->id }}">
                    [{{ $timeOff->id }}] {{ $timeOff->leavetype }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-3"><strong>Status</strong></dt>
                            <dd class="col-sm-9">
                                @if($timeOff->leavestatus == 'approved')
                                <span class="text-success">Approved</span>
                                @elseif($timeOff->leavestatus == 'pending')
                                <span class="text-warning">Pending</span>
                                @elseif($timeOff->leavestatus == 'cancelled')
                                <span class="text-danger">Cancelled</span>
                                @else
                                <span class="text-danger">Disapproved</span>
                                @endif
                            </dd>
                            <dt class="col-sm-3"><strong>Request Date</strong></dt>
                            <dd class="col-sm-9">{{ $timeOff->leaverequestdate }}</dd>
                            <dt class="col-sm-3"><strong>Leave Dates</strong></dt>
                            <dd class="col-sm-9">
                                {{($timeOff->leavedatefrom)}}
                                @if($timeOff->leavedatefrom != $timeOff->leavedateto)
                                &mdash; {{ ($timeOff->leavedateto)}}
                                @endif
                            </dd>
                            </dd>

                            <dt class="col-sm-3"><strong>Day(s)</strong></dt>
                            <dd class="col-sm-9">{{ $timeOff->leavedays }}</dd>

                            <dt class="col-sm-3"><strong>Reason</strong></dt>
                            <dd class="col-sm-9">{{ $timeOff->leavereason }}</dd>

                            @if(!empty($timeOff->leaveattachment))
                            <dt class="col-sm-3"><strong>Attachment</strong></dt>
                            <dd class="col-sm-9">
                                @if($timeOff->leaveattachment)
                                <a href="{{ route('user.timeoff.attachment', $timeOff->id) }}" target="_blank" class="btn btn-xs" title="View Attachment">
                                    <i data-feather="file" style="width:1.5em;height:1.5em;"></i> view
                                </a>
                                @else
                                <span class="text-muted">No attachment</span>
                                @endif
                            </dd>
                            @endif
                            @if(!empty($timeOff->leaveapprovedby))
                            @if($timeOff->leavestatus == 'approved')
                            <dt class="col-sm-3"><strong>Approved by</strong></dt>
                            @else
                            <dt class="col-sm-3"><strong>Disapproved by</strong></dt>
                            @endif
                            <dd class="col-sm-9">
                                {{ $approvedAdmin ? $approvedAdmin->lastname . ', ' . $approvedAdmin->firstname : $timeOff->leaveapprovedby }}
                                <span class="text-muted small">{{ $timeOff->leaveapproveddate }}</span>
                            </dd>
                            @endif

                            @if(!empty($timeOff->leaveremarks))
                            <dt class="col-sm-3"><strong>Remarks</strong></dt>
                            <dd class="col-sm-9">{{ $timeOff->leaveremarks}}</dd>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Disapprove Leave Modal Partial --}}
<div class="modal fade" id="disapproveLeaveModal{{ $timeOff->id }}" tabindex="-1" aria-labelledby="disapproveLeaveModalLabel{{ $timeOff->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin_disapprove_timeoff_submit', $timeOff->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="disapproveLeaveModal{{ $timeOff->id }}">Approve Leave Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to disapprove this leave request?</p>
                    <ul>
                        <li><strong>Type:</strong> {{ $timeOff->leavetype }}</li>
                        <li><strong>Date:</strong> {{ $timeOff->leavedatefrom }} @if($timeOff->leavedatefrom != $timeOff->leavedateto) &mdash; {{ $timeOff->leavedateto }} @endif</li>
                        <li><strong>@if ($timeOff->leavedays > 1) Days:</strong> {{ $timeOff->leavedays }} days @else Day:</strong> {{ $timeOff->leavedays }}@endif</li>
                    </ul>

                    <div class="row gx-3 mb-2">
                        <div class="col-md-12 mb-2 mb-md-0">
                            <label for="leaveremarks" class="form-label">Remarks</label>
                            <textarea class="form-control" id="leaveremarks" name="leaveremarks" rows="4" required>@if(!empty($timeOff->leaveremarks)){{ $timeOff->leaveremarks }}@else Leave application approved.@endif</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="me-1" data-feather="x-circle"></i> Disapprove Leave
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Leave Modal Partial --}}
<div class="modal fade" id="approveLeaveModal{{ $timeOff->id }}" tabindex="-1" aria-labelledby="approveLeaveModalLabel{{ $timeOff->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin_approve_timeoff_submit', $timeOff->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="approveLeaveModalLabel{{ $timeOff->id }}">Approve Leave Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to approve this leave request?</p>
                    <ul>
                        <li><strong>Type:</strong> {{ $timeOff->leavetype }}</li>
                        <li><strong>Date:</strong> {{ $timeOff->leavedatefrom }} @if($timeOff->leavedatefrom != $timeOff->leavedateto) &mdash; {{ $timeOff->leavedateto }} @endif</li>
                        <li><strong>@if ($timeOff->leavedays > 1) Days:</strong> {{ $timeOff->leavedays }} days @else Day:</strong> {{ $timeOff->leavedays }}@endif</li>
                    </ul>
                    <div class="row gx-3 mb-2">
                        <div class="col-md-12 mb-2 mb-md-0">
                            <label for="leaveremarks" class="form-label">Remarks</label>
                            <textarea class="form-control" id="leaveremarks" name="leaveremarks" rows="4" required>@if(!empty($timeOff->leaveremarks)){{ $timeOff->leaveremarks }}@else Leave application approved.@endif</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                        <i class="me-1" data-feather="x"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-outline-success">
                        <i class="me-1" data-feather="check-circle"></i> Approve Leave
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Print Leave Modal Partial --}}
@if($timeOff->leavestatus == 'approved')
<div class="modal fade" id="printLeaveModal{{ $timeOff->id }}" tabindex="-1" aria-labelledby="printLeaveModalLabel{{ $timeOff->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" id="printContent{{ $timeOff->id }}">
            <div class="modal-header">
                <h5 class="modal-title" id="printLeaveModalLabel{{ $timeOff->id }}">
                    Print Leave Request #{{ $timeOff->id }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="print-leave-format card border-0 shadow-sm p-4">
                    <div class="text-center mb-3">
                        <img src="https://dbpsc.com.ph/wp-content/uploads/2022/08/favicon1-150x150.png" alt="Logo" style="height:48px;">
                        <h3 class="mt-2 mb-0">DBP Service Corporation</h3>
                        <h4 class="mt-2 mb-0">Leave application</h4>
                    </div>
                    <div class="mb-3">
                        <strong>To:</strong> Technical and Maintenance Services Unit<br>
                        @php
                        $leaveUser = $users->firstWhere('employeenumber', $timeOff->leaveby);
                        @endphp
                        <strong>From:</strong> {{ $leaveUser ? $leaveUser->lastname . ', ' . $leaveUser->firstname . ' ' . $leaveUser->middlename : $timeOff->leaveby }}
                    </div>
                    <div class="mb-3">
                        I wish to apply for <strong>{{ $timeOff->leavedays }}</strong> day(s) (<strong>{{ $timeOff->leavetype }}</strong>) from <strong>{{ $timeOff->leavedatefrom }}</strong> to <strong>{{ $timeOff->leavedateto }}</strong>.<br>
                        The reason for this leave is <strong>{{ $timeOff->leavereason }}</strong>.
                    </div>
                    <div class="mt-4">
                        <strong>Approved by:</strong><br>
                        {{ $approvedAdmin ? $approvedAdmin->lastname . ', ' . $approvedAdmin->firstname : $timeOff->leaveapprovedby }}<br>
                        {{ $timeOff->leaveapproveddate ?? '' }}
                    </div>
                    <div class="mt-3 text-muted small">
                        <em>This is an automated document. No signature is required.</em>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-primary" onclick="printModalContent('printContent{{ $timeOff->id }}')">
                    <i data-feather="printer" class="me-1"></i> Print
                </button>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach
@include('user.partials.timeoffdayscounteredit')

<script>
    function printModalContent(contentId) {
        var card = document.querySelector('#' + contentId + ' .print-leave-format');
        var printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Print Leave</title>');
        var styles = Array.from(document.querySelectorAll('link[rel=stylesheet], style')).map(function(style) {
            return style.outerHTML;
        }).join('');
        printWindow.document.write(styles);
        printWindow.document.write('</head><body>');
        printWindow.document.write(card.outerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();
        setTimeout(function() {
            printWindow.print();
            printWindow.close();
        }, 500);
    }
</script>

</html>