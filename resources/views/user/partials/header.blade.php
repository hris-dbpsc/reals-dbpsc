<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>REALS - DBPSC</title>
    <!-- Bootstrap & Vendor CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" rel="stylesheet" />
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet" />
    <!-- Favicon best practice: .ico, .png, and apple-touch-icon -->
    <link rel="icon" type="image/x-icon" href="https://dbpsc.com.ph/wp-content/uploads/2022/08/favicon1-150x150.png" />
    <!-- FontAwesome, Feather, SweetAlert2 -->
    <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: #f8f9fa;
        }
        /* Dark mode root styles */
        body.dark-mode {
            background: #181a1b !important;
            color: #e0e0e0 !important;
        }
        body.dark-mode, body.dark-mode * {
            font-family: 'Segoe UI', Arial, sans-serif !important;
            color-scheme: dark;
        }
        body.dark-mode h1, body.dark-mode h2, body.dark-mode h3, body.dark-mode h4, body.dark-mode h5, body.dark-mode h6, body.dark-mode .fw-bold, body.dark-mode .sidenav-footer-title {
            color: #fff !important;
            font-weight: 600 !important;
        }
        body.dark-mode .form-label, body.dark-mode label, body.dark-mode .form-floating > label {
            color: #b0b3b8 !important;
            font-weight: 500 !important;
        }
        body.dark-mode .text-muted {
            color: #b0b3b8 !important;
            font-style: italic;
        }
        body.dark-mode .badge, body.dark-mode .btn, body.dark-mode .nav-link, body.dark-mode .dropdown-item {
            font-family: 'Segoe UI', Arial, sans-serif !important;
        }
        body.dark-mode .sidebar,
        body.dark-mode .card,
        body.dark-mode .main-content,
        body.dark-mode .bottom-bar,
        body.dark-mode .modal-content {
            background: #23272b !important;
            color: #e0e0e0 !important;
            border-color: #343a40 !important;
        }
        body.dark-mode .sidebar .nav-link,
        body.dark-mode .sidebar .nav-link.active {
            background: transparent !important;
            color: #e0e0e0 !important;
        }
        body.dark-mode .sidebar .nav-link.active {
            background: #23272b !important;
            color: #0dcaf0 !important;
            border-left: 4px solid #0dcaf0 !important;
        }
        body.dark-mode .bottom-bar {
            background: #23272b !important;
            border-top: 1px solid #343a40 !important;
        }
        body.dark-mode .bottom-bar .nav-link,
        body.dark-mode .bottom-bar .nav-link.active {
            color: #e0e0e0 !important;
            background: transparent !important;
        }
        body.dark-mode .bottom-bar .nav-link.active {
            background: #23272b !important;
            color: #0dcaf0 !important;
            border-top: 3px solid #0dcaf0 !important;
        }
        body.dark-mode .card {
            background: #23272b !important;
            color: #e0e0e0 !important;
            border-color: #343a40 !important;
        }
        body.dark-mode .form-control {
            background: #23272b !important;
            color: #e0e0e0 !important;
            border-color: #495057 !important;
        }
        body.dark-mode .form-control:focus {
            background: #23272b !important;
            color: #fff !important;
            border-color: #0dcaf0 !important;
            box-shadow: 0 0 0 0.2rem rgba(13,202,240,.25);
        }
        body.dark-mode .modal-content {
            background: #23272b !important;
            color: #e0e0e0 !important;
        }
        body.dark-mode .btn-light {
            background: #343a40 !important;
            color: #e0e0e0 !important;
            border-color: #23272b !important;
        }
        body.dark-mode .btn-outline-primary {
            color: #0dcaf0 !important;
            border-color: #0dcaf0 !important;
        }
        body.dark-mode .btn-outline-primary:hover, body.dark-mode .btn-outline-primary:focus {
            background: #0dcaf0 !important;
            color: #23272b !important;
        }
        body.dark-mode .bg-light, body.dark-mode .sidenav-footer.bg-light {
            background: #23272b !important;
            color: #e0e0e0 !important;
        }
        body.dark-mode .border-top {
            border-top: 1px solid #343a40 !important;
        }
        body.dark-mode .text-body, body.dark-mode .sidenav-footer-title, body.dark-mode .sidenav-footer-subtitle {
            color: #e0e0e0 !important;
        }
        body.dark-mode .badge.bg-light {
            background: #343a40 !important;
            color: #e0e0e0 !important;
        }
        body.dark-mode .badge.bg-primary {
            background: #0dcaf0 !important;
            color: #23272b !important;
        }
        body.dark-mode .badge.bg-success {
            background: #198754 !important;
            color: #fff !important;
        }
        body.dark-mode .badge.bg-warning {
            background: #ffc107 !important;
            color: #23272b !important;
        }
        body.dark-mode .dropdown-menu {
            background: #23272b !important;
            color: #e0e0e0 !important;
        }
        body.dark-mode .text-muted {
            color: #b0b3b8 !important;
        }
        body.dark-mode .form-floating > label {
            color: #b0b3b8 !important;
        }
        body.dark-mode .btn {
            border-radius: 0.375rem;
        }
        body.dark-mode .shadow-sm {
            box-shadow: 0 .125rem .25rem rgba(0,0,0,.25)!important;
        }
        /* Add more dark mode overrides as needed for visibility and contrast */
        .sidebar {
            min-width: 70px;
            max-width: 220px;
            background: #fff;
            border-right: 1px solid #eee;
            height: 100vh;
            position: fixed;
            top: 0; left: 0;
            z-index: 1030;
            transition: all 0.3s;
        }
        .sidebar .nav-link {
            color: #333;
            font-size: 1.1rem;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border-left: 4px solid transparent; /* Ensure consistent border for all */
            background: transparent; /* Reset background for all */
            transition: background 0.2s, color 0.2s, border-left 0.2s;
            width: 100%; /* Ensure nav-link fills sidebar width */
            border-radius: 0; /* Remove border radius for full highlight */
        }
        .sidebar .nav-link.active {
            background: #e9ecef !important;
            color: #007bff !important;
            border-left: 4px solid #007bff !important;
            width: 100%; /* Ensure highlight covers full width */
            border-radius: 0 !important;
        }
        .sidebar .nav-link i {
            width: 24px; height: 24px;
        }
        @media (max-width: 768px) {
            .sidebar {
                display: none !important;
            }
            .bottom-bar {
                display: flex !important;
            }
            .main-content {
                margin-left: 0;
                padding-bottom: 4.5rem;
            }
        }
        .bottom-bar {
            display: none;
            position: fixed;
            bottom: 0; left: 0; right: 0;
            background: #fff;
            border-top: 1px solid #eee;
            z-index: 1030;
            justify-content: space-around;
            padding: 0.5rem 0;
        }
        .bottom-bar .nav-link {
            color: #333;
            font-size: 0.95rem;
            flex-direction: column;
            gap: 0.2rem;
            padding: 0.2rem 0.5rem;
            border-top: 3px solid transparent; /* Consistent border for all */
            background: transparent; /* Reset background for all */
            transition: background 0.2s, color 0.2s, border-top 0.2s;
            width: 100%; /* Ensure nav-link fills bottom bar width */
            border-radius: 0; /* Remove border radius for full highlight */
        }
        .bottom-bar .nav-link.active {
            color: #007bff !important;
            border-top: 3px solid #007bff !important;
            background: #e9ecef !important;
            width: 100%; /* Ensure highlight covers full width */
            border-radius: 0 !important;
        }
        .main-content {
            margin-left: 220px;
            padding: 2rem 1rem 4rem 1rem;
        }
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding-bottom: 4.5rem;
            }
        }
    </style>
</head>
