<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.98.0">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <title>ZSH - Student Admission System</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset("css/bootstrap.css")}}" rel="stylesheet">
    <!-- CSS only -->
    <link href="{{asset("css/style.css")}}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.css') }}">
    @livewireStyles
    <!-- Styles -->
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Sidebar styles for large screens */
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background-color: rgb(238, 238, 238);
            color: #000;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 60px; /* space for topbar */
        }

        .sidebar a {
            color: #000;
            text-decoration: none;
            display: block;
            padding: 12px 20px;
        }
        .active {
            background-color: #cfd6db;
            color: whitesmoke;
        }

        .sidebar a:hover {
            background-color: #34495e;
            color: whitesmoke;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .topbar {
            background-color: #2c3e50;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 250px;
            right: 0;
            height: 60px;
            z-index: 1030;
        }

        .topbar .btn-logout {
            background-color: #e74c3c;
            border: none;
        }

        .content-wrapper {
            margin-top: 80px;
        }

        /* Mobile adjustments */
        @media (max-width: 991px) {
            .sidebar {
                display: none; /* hide fixed sidebar on mobile */
            }

            .main-content {
                margin-left: 0; /* remove margin for mobile */
            }

            .topbar {
                left: 0; /* topbar full width */
            }
        }

        /* Logo and title wrapper */
        .sidebar-header {
            text-align: center;
            padding: 20px 10px;
        }

        .sidebar-header img {
            max-width: 100px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <!-- Sidebar for large screens -->
    <div class="sidebar d-none d-lg-block">
        <div class="sidebar-header">
            <img src="{{asset("images/logo.png")}}" alt="College Logo">
            <h4 class="m-0">Admission System</h4>
        </div>
        <hr class="text-white">
        <a href="{{ route('portal-dashboard') }}" class="active">Dashboard</a>
        <a href="{{ route('portal-application') }}">Applications</a>
        <a href="#">Course Descriptions</a>
        <a href="#">Fee Structures</a>
        <a href="{{ route('user.change.password') }}">Account Settings</a>
    </div>

    <!-- Offcanvas Sidebar for mobile -->
    <div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="mobileSidebar">
        <div class="offcanvas-header w-100 d-block text-center">
            <img src="{{asset("images/logo.png")}}" alt="College Logo" style="max-width:100px;">
            <h5 class="mt-2">Admission System</h5>
            <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <a href="{{ route('portal-dashboard') }}" class="d-block text-white text-decoration-none mb-2">Dashboard</a>
            <a href="{{ route('portal-application') }}" class="d-block text-white text-decoration-none mb-2">Applications</a>
            <a href="#" class="d-block text-white text-decoration-none mb-2">Course Instructions</a>
            <a href="{{ route('user.change.password') }}" class="d-block text-white text-decoration-none mb-2">Account Settings</a>
        </div>
    </div>

    <!-- Top Bar -->
    <div class="topbar">
        <div class="d-flex align-items-center">
            <!-- Hamburger button visible only on mobile -->
            <button class="btn btn-light d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
                ☰
            </button>
        </div>
        <div>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <a class="btn btn-sm btn-logout text-white ms-3" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    Sign out
                </a>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="content-wrapper">
            @yield("content")
        </div>
    </div>

<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@livewireScripts
<!-- Alert -->
<script src="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.js')}}"></script>

<script>
    //Sweet alert for livewire
    const toastMixin = Swal.mixin({
        toast: true,
        animation: false,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    window.addEventListener('swal:info',event=>{
        toastMixin.fire({
            animation: true,
            icon: event.detail.title,
            title: '<b style="font-size:13pt!important; padding-left:30px">'+event.detail.text+'</b>'
        });
    })

</script>
</body>
</html>
