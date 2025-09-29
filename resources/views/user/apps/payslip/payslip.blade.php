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
                            <div class="card mb-2">
                                <div class="card-header text-body d-flex justify-content-between align-items-center">
                                    <span>PAYSLIP</span>
                                </div>
                                <div class="card-body">
                                    @if(isset($files) && count($files) > 0)
                                    <div class="list-group">
                                        @foreach($files as $f)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                @php
                                                    $label = $f['label'] ?? $f['name'];
                                                    $displayLabel = $label;
                                                    // Attempt to find common date patterns and convert them to a long, friendly date
                                                    if (class_exists(\Carbon\Carbon::class)) {
                                                        $patterns = [
                                                            '/\b(\d{8})\b/' => ['mdY','dmY','Ymd'],
                                                            '/\b(\d{4}-\d{2}-\d{2})\b/' => ['Y-m-d'],
                                                            '/\b(\d{2}-\d{2}-\d{4})\b/' => ['m-d-Y','d-m-Y'],
                                                            '/\b(\d{2}\.\d{2}\.\d{4})\b/' => ['m.d.Y','d.m.Y'],
                                                            '/\b(\d{2}\/\d{2}\/\d{4})\b/' => ['m/d/Y','d/m/Y'],
                                                        ];
                                                        foreach ($patterns as $regex => $formats) {
                                                            if (preg_match($regex, $label, $m)) {
                                                                $raw = $m[1];
                                                                foreach ($formats as $fmt) {
                                                                    try {
                                                                        $dt = \Carbon\Carbon::createFromFormat($fmt, $raw);
                                                                        $friendly = $dt->format('F j, Y');
                                                                        $displayLabel = str_replace($raw, $friendly, $label);
                                                                        break 2; // parsed successfully; exit both loops
                                                                    } catch (\Exception $e) {
                                                                        // try next format
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                <strong>{{ $displayLabel }}</strong>
                                                <!-- <div class="text-muted small">{{ $f['name'] }} • {{ date('Y-m-d H:i', $f['mtime']) }} • {{ round($f['size']/1024, 2) }} KB</div> -->
                                            </div>
                                            <div class="btn-group">
                                                <a href="{{ route('user_payslip_download', ['filename' => $f['name']]) }}" class="btn btn-sm btn-outline-primary" target="_blank">View</a>
                                                <a href="{{ route('user_payslip_download', ['filename' => $f['name'], 'download' => 1]) }}" class="btn btn-sm btn-primary">Download</a>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                    <div class="text-muted">No payslips found.</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </main>
            @include('user.partials.user_footer')
</body>


</html>