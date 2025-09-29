<!DOCTYPE html>
<html lang="en">
@include('user.partials.user_header')

<body class="nav-fixed">
    @include('user.partials.user_topnav')
    <div id="layoutSidenav">
        @include('user.partials.user_sidenav')
        <div id="layoutSidenav_content">
            <main>
                <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-2">
                    <div class="container-fluid px-4">
                        <div class="page-header-content">
                            <div class="row align-items-center justify-content-between pt-3">
                                <div class="col-auto mb-2">
                                    <h1 class="page-header-title text-body d-flex align-items-center">
                                        <a href="{{ route('user_timeoff') }}" class="btn rounded-circle shadow-sm d-flex align-items-center justify-content-center me-2" style="width:50px; height:50px; padding:0;">
                                            <i data-feather="arrow-left-circle" class="text-primary" style="width:40px; height:40px;"></i>
                                        </a>
                                        File a Leave
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <!-- Main page content-->
                <div class="container-fluid px-4 mt-2">
                    @if (session('success'))
                    <div class="alert alert-success alert-sm py-1 px-2">
                        {{ session('success') }}
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-12">
                            <!-- Account details card-->
                            <div class="card mb-2" id="createrequest">
                                <div class="card-header text-body">Leave details</div>
                                <div class="card-body">
                                    <form action="{{ route('user_addtimeoff_submit') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row gx-3 mb-2">
                                            <div class="col-md-3 form-floating mb-2 mb-md-0">
                                                <select class="form-select" name="leavetype" id="leavetype" required>
                                                    <option value="" disabled selected>Select Leave Type</option>
                                                    <option value="SIL">SIL (Service Incentive Leave)</option>
                                                    <option value="Solo Parent Leave">Solo Parent Leave</option>
                                                    @if (Auth::guard('user')->user() && Auth::guard('user')->user()->gender === 'Male')
                                                    <option value="Paternity Leave">Paternity Leave</option>
                                                    @endif
                                                    @if (Auth::guard('user')->user() && Auth::guard('user')->user()->gender === 'Female')
                                                    <option value="Maternity Leave">Maternity Leave</option>
                                                    <option value="Special Leave for Women">Special Leave for Women</option>
                                                    <option value="VAWC Leave">VAWC Leave</option>
                                                    @endif
                                                </select>
                                                <label for="leavetype">Leave Type</label>
                                            </div>
                                            <div class="col-md-3 form-floating mb-2 mb-md-0">
                                                <input type="text" class="form-control" name="number_of_days" id="number_of_days" required>
                                                <label for="number_of_days">Days</label>
                                            </div>
                                        </div>

                                        <div class="row gx-3 mb-2">
                                            <div class="col-md-3 form-floating mb-2 mb-md-0">
                                                <input type="date" class="form-control datepicker" name="leave_date_from" id="leave_date_from" autocomplete="off" required>
                                                <label for="leave_date_from">From</label>
                                            </div>
                                            <div class="col-md-3 form-floating mb-2 mb-md-0">
                                                <input type="date" class="form-control datepicker" name="leave_date_to" id="leave_date_to" autocomplete="off" required>
                                                <label for="leave_date_to">To</label>
                                            </div>
                                        </div>

                                        <div class="row gx-3 mb-2">
                                            <div class="col-md-6 form-floating mb-2 mb-md-0">
                                                <textarea class="form-control" name="leavereason" id="leavereason" rows="4" style="height:auto;" required></textarea>
                                                <label for="leavereason">Reason for Leave</label>
                                            </div>
                                        </div>


                                        <div class="row gx-3 mb-2">
                                            <div class="col-md-6 mb-2 mb-md-0">
                                                <label for="leaveattachment" class="form-label small text-muted">Attachment</label>
                                                <input type="file" class="form-control" name="leaveattachment" id="leaveattachment" accept=".pdf,.jpg,.jpeg,.png">
                                            </div>
                                        </div>

                                        <div class="row gx-3 mb-2">
                                            <div class="col-md-6">
                                                <!-- Button trigger modal -->
                                                <button class="btn btn-outline-primary d-flex align-items-center" type="button" data-bs-toggle="modal" data-bs-target="#confirmLeaveModal">
                                                    <i class="me-2" data-feather="plus"></i>
                                                    Add Leave
                                                </button>

                                                <!-- Confirmation Modal -->
                                                <div class="modal fade" id="confirmLeaveModal" tabindex="-1" aria-labelledby="confirmLeaveModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="confirmLeaveModalLabel">Confirm Leave Submission</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to submit this leave request?
                                                            </div>
                                                            <div class="modal-footer justify-content-center">
                                                                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                                                                    <i class="me-1" data-feather="x"></i>
                                                                    Cancel
                                                                </button>
                                                                <button type="submit" class="btn btn-outline-primary" id="addLeaveBtn" onclick="disableAddLeaveBtn(this)">
                                                                    <i class="me-1" data-feather="plus"></i>
                                                                    Add Leave
                                                                </button>
                                                                <script>
                                                                    function disableAddLeaveBtn(btn) {
                                                                        btn.disabled = true;
                                                                        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Processing...';
                                                                        btn.form.submit();
                                                                    }
                                                                </script>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            @include('user.partials.user_footer')
</body>
@include('user.partials.timeoffdayscounter')

</html>