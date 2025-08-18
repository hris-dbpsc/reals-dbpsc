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
                                    Branch Information
                                </h1>
                            </div>
                            <div class="col-12 col-xl-auto mb-3">
                                <a class="btn btn-sm btn-light text-primary" href="{{ route('superadmin_branches') }}">
                                    <i class="me-1" data-feather="arrow-left"></i>
                                    Back to Branch List
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
                            <div class="card-header">Branch Photo</div>
                            <div class="card-body text-center">
                                <!-- Profile picture image-->
                                @if($branch->clientphoto)
                                <div style="position: relative; display: flex; justify-content: center; align-items: center; width: 200px; height: 200px; margin: auto;">
                                    <img src="{{ $branch->clientphoto ? asset('assets/clients/' . $branch->clientphoto) : asset('assets/assets/img/illustrations/profiles/profile-1.png') }}" alt="Photo" width="200" height="200" style="object-fit:cover; border-radius:50%; display:block; border: 2px solid #ccc;">
                                    <span style="position: absolute; bottom: 12px; right: 12px; width: 32px; height: 32px; background: {{ $branch->isactive == 1 ? '#28a745' : '#dc3545' }}; border: 2px solid #fff; border-radius: 50%; display: block;"></span>
                                </div>
                                @else
                                <img class="img-account-profile rounded-circle mb-2" src="{{ asset('assets/assets/img/illustrations/profiles/profile-1.png') }}" alt="Photo" style="object-fit:cover; display:block; margin:auto;" width="150" height="150" />
                                @endif
                                <!-- Profile picture help block-->
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8">
                        <!-- Account details card-->
                        <div class="card mb-4">
                            <div class="card-header">Branch Details</div>
                            <div class="card-body">
                                <form>
                                    <!-- Form Row-->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered mb-0">
                                                <tbody>
                                                    <tr>
                                                        <th>Client</th>
                                                        <td>{{ $branch->clientname }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Branch</th>
                                                        <td>{{ $branch->branchname }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Contact Number</th>
                                                        <td>{{ $branch->branchcontact }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Contact Person</th>
                                                        <td>{{ $branch->branchcontactperson }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Address</th>
                                                        <td>{{ $branch->branchaddress }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Region</th>
                                                        <td>{{ $branch->branchregion }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Province</th>
                                                        <td>{{ $branch->branchprovince }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>City</th>
                                                        <td>{{ $branch->branchcity }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Status</th>
                                                        <td>
                                                            @if($branch->isactive == 1)
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
                                                            @if(!empty($branch->branchgeolocation))
                                                            <a href="" target="_blank" class="btn btn-success btn-sm">
                                                                <i data-feather="map-pin" class="me-1"></i>
                                                                View Geolocation
                                                            </a>
                                                            @else
                                                            <span class="text-muted">No geolocation available</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Streetview</th>
                                                        <td>
                                                            @if(!empty($branch->branchstreetview))
                                                            <a href="{{ $branch->branchstreetview }}" target="_blank" class="btn btn-info btn-sm text-white">
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
                                    <!-- Submit button-->
                                    <!-- <button class="btn btn-primary" type="button">Save changes</button> -->
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        @include('superadmin.footer')
        </body>

</html>