<!DOCTYPE html>
<html lang="en">
@include('clientadmin.partials.client_header')
@include('clientadmin.partials.client_topnav')

<div id="layoutSidenav">
    @include('clientadmin.partials.client_sidenav')
    <div id="layoutSidenav_content">
        <main>
            <header class="page-header bg-gray-200 pb-10">
                <div class="container-fluid px-4">
                    <div class="page-header-content pt-4">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto mt-2">
                                <h1 class="page-header-title text-body d-flex align-items-center" style="font-size:1.5rem;">
                                    <span class="page-header-icon me-2 text-body d-flex justify-content-center align-items-center" style="width:28px; height:28px;">
                                        <i data-feather="user" style="width:30px; height:30px;"></i>
                                    </span>
                                    People
                                </h1>
                                <div class="page-header-subtitle text-body mt-2">Employee Information System</div>
                            </div>
                            <div class="col-auto mt-4">
                                <a href="{{ route('clientadmin_apps') }}" class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width:50px; height:50px; padding:0;">
                                    <i data-feather="arrow-left-circle" class="text-primary" style="width:40px; height:40px;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <div class="container-fluid px-4 mt-n10 mb-3">
                <div class="row">
                    <!-- CARD 1 -->
                    <div class="col-xl-12 mb-2">
                        <div class="page-header-search mt-4">
                            <div class="input-group input-group-joined" style="height: 56px; font-size: 1.2rem;">
                                <input class="form-control" type="text" name="searchengine" id="searchengine" placeholder="Search Employee" aria-label="Search" autofocus style="height: 56px; font-size: 1.2rem;" />
                                <span class="input-group-text" style="height: 56px;"><i data-feather="search"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Employee Data Table-->
            <div class="container-fluid px-4">
                <div class="card">
                    <div class="card-body d-none" id="employeelocator">
                        <table id="datatablesSimple" class="table table-bordered table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>PHOTO</th>
                                    <th>CLIENT</th>
                                    <th>BRANCH</th>
                                    <th>EMPLOYEE NUMBER</th>
                                    <th>POSITION</th>
                                    <th>NAME</th>
                                    <th>REGION</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>PHOTO</th>
                                    <th>CLIENT</th>
                                    <th>BRANCH</th>
                                    <th>EMPLOYEE NUMBER</th>
                                    <th>POSITION</th>
                                    <th>NAME</th>
                                    <th>REGION</th>
                                    <th>ACTION</th>
                                </tr>
                            </tfoot>
                            <tbody id="searchResultsBody">
                                <!-- Results will be injected here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Employee Data Table-->
        </main>
        @include('clientadmin.partials.client_footer')
        </body>

</html>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchengine');
        const tableContainer = document.getElementById('employeelocator');
        const resultsBody = document.getElementById('searchResultsBody');
        let searchTimeout;
        // Prevent Enter key from submitting the form or reloading the page
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        });
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            if (query.length === 0) {
                tableContainer.classList.add('d-none');
                resultsBody.innerHTML = '';
                return;
            }
            searchTimeout = setTimeout(function() {
                fetch("{{ route('clientadmin_people_search_users') }}?q=" + encodeURIComponent(query))
                    .then(res => res.json())
                    .then(data => {
                        if (Array.isArray(data) && data.length > 0) {
                            let html = '';
                            data.forEach(user => {
                                html += `<tr>
                                    <td>
                                        <img class='img-fluid rounded-circle' style='width: 40px; height: 40px; object-fit: cover;'
                                             src='${user.photo_url ? user.photo_url : "/assets/assets/img/demo/user-placeholder.svg"}'>
                                    </td>
                                    <td>${user.clientname || ''}</td>
                                    <td>${user.branchname || ''}</td>
                                    <td>${user.employeenumber || ''}</td>
                                    <td>${user.position || ''}</td>
                                    <td>${[user.lastname, user.firstname, user.middlename].filter(Boolean).join(', ')}</td>
                                    <td>${user.region || ''}</td>
                                    <td>
                                        <a href='/clientadmin/peopleview/${user.id}' 
                                            class='btn btn-outline-primary d-flex align-items-center gap-1'
                                            target='_blank' rel='noopener'>
                                            <i data-feather="eye"></i>
                                            View
                                        </a>
                                    </td>
                                </tr>`;
                            });
                            resultsBody.innerHTML = html;
                            tableContainer.classList.remove('d-none');
                            if (window.feather) {
                                feather.replace();
                            }
                        } else {
                            resultsBody.innerHTML = `<tr><td colspan='8' class='text-center text-muted'>No results found.</td></tr>`;
                            tableContainer.classList.remove('d-none');
                        }
                    });
            }, 300); // debounce
        });
    });
</script>