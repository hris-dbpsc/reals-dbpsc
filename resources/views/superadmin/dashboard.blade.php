<!DOCTYPE html>
<html lang="en">
@include('superadmin.header')
@include('superadmin.topnav')

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            position: "center",
            icon: "success",
            title: "Login Successful",
            showConfirmButton: false,
            timer: 1300,
            timerProgressBar: true,
            willClose: () => {
                // Optional: Add any action to perform when the alert closes
                console.log("Swal closed");
            }
        });
    });
</script>
@endif
<div id="layoutSidenav">
    @include('superadmin.sidenav')
    <div id="layoutSidenav_content">
        @include('superadmin.main')

        @include('superadmin.footer')
        </body>

</html>