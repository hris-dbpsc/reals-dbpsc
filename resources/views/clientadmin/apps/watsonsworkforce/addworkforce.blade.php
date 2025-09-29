<!DOCTYPE html>
<html lang="en">
@include('clientadmin.partials.client_header')

<body class="nav-fixed">
    @include('clientadmin.partials.client_topnav')
    <div id="layoutSidenav">
        @include('clientadmin.partials.client_sidenav')
        <div id="layoutSidenav_content">
            <main>
                <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-2">
                    <div class="container-fluid px-4">
                        <div class="page-header-content">
                            <div class="row align-items-center justify-content-between pt-3">
                                <div class="col-auto mb-2">
                                    <h1 class="page-header-title text-body d-flex align-items-center">
                                        <a href="{{ route('clientadmin_watsons_workforce') }}" class="btn rounded-circle shadow-sm d-flex align-items-center justify-content-center me-2" style="width:50px; height:50px; padding:0;">
                                            <i data-feather="arrow-left-circle" class="text-primary" style="width:40px; height:40px;"></i>
                                        </a>
                                        Add Request
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
                                <div class="card-header text-body">Request Details</div>
                                <div class="card-body">
                                    <form action="{{ route('clientadmin_watsons_workforce_submit') }}" method="POST">
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
            </main>

            @include('clientadmin.partials.client_footer')
</body>

<script>
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