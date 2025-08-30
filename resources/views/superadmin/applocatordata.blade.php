<!DOCTYPE html>
<html lang="en">
@include('superadmin.header')

<body>
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <header class="page-header bg-gray-200 pb-10">
                    <div class="container-fluid px-4">
                        <div class="page-header-content pt-4">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-auto mt-2">
                                    <h2 class="page-header-title text-body d-flex align-items-center" style="font-size:1.5rem;">
                                        <span class="page-header-icon me-2 text-body d-flex justify-content-center align-items-center" style="width:28px; height:28px;">
                                            <i data-feather="map-pin" style="width:30px; height:30px;"></i>
                                        </span>
                                        Client Locator
                                        @if($client)
                                        (Client: {{ $client }})
                                        @elseif($branch)
                                        (Branch: {{ $branch }})
                                        @endif
                                    </h2>
                                    <div class="page-header-subtitle text-body mt-2">A Geographic Information System Application</div>
                                </div>

                                <div class="col-auto mt-4">
                                    <a href="javascript:void(0);" onclick="window.close();" class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width:50px; height:50px; padding:0;">
                                        <i data-feather="x" class="text-primary" style="width:40px; height:40px;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <!-- Main page content-->

                <div class="container-fluid px-4 mt-n10">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">LIST OF EMPLOYEE(S)</h5>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>PHOTO</th>
                                        <th>BRANCH</th>
                                        <th>EMPLOYEE NUMBER</th>
                                        <th>POSITION</th>
                                        <th>LAST NAME</th>
                                        <th>FIRST NAME</th>
                                        <th>MIDDLE NAME</th>
                                        <th>EMAIL</th>
                                        <th>CONTACT</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>PHOTO</th>
                                        <th>BRANCH</th>
                                        <th>EMPLOYEE NUMBER</th>
                                        <th>POSITION</th>
                                        <th>LAST NAME</th>
                                        <th>FIRST NAME</th>
                                        <th>MIDDLE NAME</th>
                                        <th>EMAIL</th>
                                        <th>CONTACT</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @php
                                    $filteredUsers = collect($users);
                                    if ($client) {
                                    $filteredUsers = $filteredUsers->where('clientname', $client);
                                    } elseif ($branch) {
                                    $filteredUsers = $filteredUsers->where('branchname', $branch);
                                    }
                                    $filteredUsers = $filteredUsers->sortBy([
                                    ['branchname', 'asc'],
                                    ['position', 'asc'],
                                    ['lastname', 'asc'],
                                    ]);
                                    @endphp
                                    @foreach($filteredUsers as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex justify-content-center align-items-center">
                                                <img class="img-fluid rounded-circle" style="width: 48px; height: 48px; object-fit: cover;" src="{{ $user->photo ? asset('assets/users/admin/' . $user->photo) : asset('assets/assets/img/demo/user-placeholder.svg') }}" alt="Admin Photo" />
                                            </div>
                                        </td>
                                        <td>{{ $user->branchname }}</td>
                                        <td>{{ $user->employeenumber }}</td>
                                        <td>{{ $user->position }}</td>
                                        <td>{{ $user->lastname }}</td>
                                        <td>{{ $user->firstname }}</td>
                                        <td>{{ $user->middlename }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->contact }}</td>
                                    </tr>
                                    @endforeach
                                    </tr>

                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center my-3">
                                <!-- <button class="btn bg-light text-primary d-flex align-items-center">
                                    <i data-feather="printer" class="me-2"></i> Print Employee List
                                </button> -->
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            @include('superadmin.footer')
        </div>
</body>

</html>