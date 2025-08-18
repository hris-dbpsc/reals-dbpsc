<!DOCTYPE html>
<html lang="en">
@include('superadmin.header')
@include('superadmin.topnav')
<div id="layoutSidenav">
    @include('superadmin.sidenav')
    <div id="layoutSidenav_content">
        <main>
            <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
                <div class="container-fluid px-4">
                    <div class="page-header-content">
                        <div class="row align-items-center justify-content-between pt-3">
                            <div class="col-auto mb-3">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i data-feather="info"></i></div>
                                    Client Information
                                </h1>
                            </div>
                            <div class="col-12 col-xl-auto mb-3">
                                <a class="btn btn-sm btn-light text-primary" href="{{ route('superadmin_clients') }}">
                                    <i class="me-1" data-feather="arrow-left"></i>
                                    Back to Client List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Main page content-->
            <div class="container-fluid px-4 mt-4">
                <div class="row">
                    <div class="col-xl-4">
                        <!-- Profile picture card-->
                        <div class="card mb-4 mb-xl-0">
                            <div class="card-header">Client Photo</div>
                            <div class="card-body text-center">
                                <!-- Profile picture image-->
                                <div style="position: relative; display: flex; justify-content: center; align-items: center; width: 200px; height: 200px; margin: auto;">
                                    <img src="{{ isset($client) && $client->clientphoto ? asset('assets/clients/' . $client->clientphoto) : asset('assets/assets/img/illustrations/profiles/profile-1.png') }}" alt="Photo" width="200" height="200" style="object-fit:cover; border-radius:50%; display:block; border: 2px solid #ccc;">
                                    <span style="position: absolute; bottom: 12px; right: 12px; width: 32px; height: 32px; background: {{ $client->isactive == 1 ? '#28a745' : '#dc3545' }}; border: 2px solid #fff; border-radius: 50%; display: block;"></span>
                                </div>
                                <!-- Profile picture help block-->
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8">
                        <!-- Account details card-->
                        <div class="card mb-4">
                            <div class="card-header">Client Details</div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <tbody>
                                            <tr>
                                                <th class="w-25">Client Name</th>
                                                <td><strong>{{ $client->clientname }}</strong></td>
                                            </tr>
                                            <tr>
                                                <th>Short Name</th>
                                                <td><strong>{{ $client->clientshortname }}</strong></td>
                                            </tr>
                                            <tr>
                                                <th>Type</th>
                                                <td>
                                                    @if($client->clienttype === 'Government')
                                                    <span class="badge bg-danger d-inline-flex align-items-center gap-1" style="font-size: 0.8rem; padding: 0.25em 0.5em;">
                                                        <i data-feather="shield" class="me-1" style="width: 0.9em; height: 0.9em;"></i>
                                                        <span>{{ $client->clienttype }}</span>
                                                    </span>
                                                    @elseif($client->clienttype === 'Private')
                                                    <span class="badge bg-primary d-inline-flex align-items-center gap-1" style="font-size: 0.8rem; padding: 0.25em 0.5em;">
                                                        <i data-feather="briefcase" class="me-1" style="width: 0.9em; height: 0.9em;"></i>
                                                        <span>{{ $client->clienttype }}</span>
                                                    </span>
                                                    @else
                                                    <span class="badge bg-secondary d-inline-flex align-items-center gap-1" style="font-size: 0.8rem; padding: 0.25em 0.5em;">
                                                        <i data-feather="help-circle" class="me-1" style="width: 0.9em; height: 0.9em;"></i>
                                                        <span>{{ $client->clienttype }}</span>
                                                    </span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>
                                                    @if($client->isactive == 1)
                                                    <span class="badge bg-primary d-inline-flex align-items-center gap-1" style="font-size: 0.8rem; padding: 0.25em 0.5em;">
                                                        <i data-feather="check-circle" class="me-1" style="width: 0.9em; height: 0.9em;"></i>
                                                        <span>Active</span>
                                                    </span>
                                                    @else
                                                    <span class="badge bg-danger d-inline-flex align-items-center gap-1" style="font-size: 0.8rem; padding: 0.25em 0.5em;">
                                                        <i data-feather="x-circle" class="me-1" style="width: 0.9em; height: 0.9em;"></i>
                                                        <span>Inactive</span>
                                                    </span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Geolocation</th>
                                                <td>
                                                    @if(!empty($client->clientgeolocation))
                                                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($client->clientgeolocation) }}" target="_blank" class="btn btn-success btn-sm">
                                                        <i data-feather="map-pin" class="me-1"></i>
                                                        View Geolocation
                                                    </a>
                                                    @else
                                                    <span class="text-muted">No geolocation available</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Street View</th>
                                                <td>
                                                    @if(!empty($client->clientstreetview))
                                                    <a href="{{ $client->clientstreetview }}" target="_blank" class="btn btn-info btn-sm text-white">
                                                        <i data-feather="eye" class="me-1"></i>
                                                        View Street View
                                                    </a>
                                                    @else
                                                    <span class="text-muted">No street view available</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        @include('superadmin.footer')
        </body>

</html>