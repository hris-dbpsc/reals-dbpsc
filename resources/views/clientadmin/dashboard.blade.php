<!DOCTYPE html>
<html lang="en">

<body>
    CLIENT ADMIN DASHBOARD
    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit" class="btn btn-outline-primary">
            <i data-feather="log-out" class="me-1"></i>
            Logout
        </button>
    </form>
</body>

</html>