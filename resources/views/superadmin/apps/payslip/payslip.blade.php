<!DOCTYPE html>
<html lang="en">
@include('superadmin.partials.header')

<body class="nav-fixed">
    @include('superadmin.partials.topnav')
    <div id="layoutSidenav">
        @include('superadmin.partials.sidenav')
        <div id="layoutSidenav_content">
            <main>
                <header class="page-header bg-gray-200 pb-10">
                    <div class="container-fluid px-4">
                        <div class="page-header-content pt-4">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-auto mt-2">
                                    <h2 class="page-header-title text-body d-flex align-items-center" style="font-size:1.5rem;">
                                        <span class="page-header-icon me-2 text-body d-flex justify-content-center align-items-center" style="width:28px; height:28px;">
                                            <i data-feather="book" style="width:30px; height:30px;"></i>
                                        </span>
                                        Payslip
                                    </h2>
                                    <div class="page-header-subtitle text-body mt-2">Payslip Information</div>
                                </div>
                                <div class="col-auto mt-4">
                                    <a href="{{ route('user_apps') }}" class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width:50px; height:50px; padding:0;">
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
                        <div class="col-12">
                            <div class="card shadow-sm mb-4">
                                <div class="card-body">
                                    {{-- Feedback messages --}}
                                    @if(session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif
                                    @if(session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif
                                    @if($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif
                                    <form action="{{ route('superadmin_payslip_upload') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="payslipFiles" class="form-label">Upload Payslip PDFs</label>
                                            <input type="file" class="form-control" id="payslipFiles" name="payslip_files[]" accept="application/pdf" multiple>
                                            <small class="form-text text-muted">You can select multiple PDF files. Leave empty if uploading a ZIP archive below.</small>
                                        </div>

                                        <button type="submit" class="btn btn-primary">
                                            <i data-feather="upload" class="me-2"></i>
                                            Upload
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>



            </main>
            @include('superadmin.partials.footer')
</body>


</html>