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

    <!-- CSS only -->
    <link href="{{asset("css/bootstrap.css")}}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset("css/dashboard.css") }}">
    <link rel="stylesheet" href="{{ asset("css/simple.css") }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
          integrity="sha512-SfTiTlX6kk+qitfevl/7LibUOeJWlt9rbyDn92a1DqWOw9vWG2MFoays0sgObmWazO5BQPiFucnnEAjpAB+/Sw=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        fieldset {
            border: 2px dashed #ccc;
            padding: 16px;
            margin-bottom: 10px;
        }
        .fa { font-size: 15px; }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
    @livewireStyles
    @stack('style')
</head>
<body data-new-gr-c-s-check-loaded="8.896.0" data-gr-ext-installed="">

<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="#">ZSH - Student Admission System</a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
            aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="navbar-nav">
        <div class="nav-item text-nowrap">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a class="nav-link px-3" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); this.closest('form').submit();">
                    Sign out
                </a>
            </form>
        </div>
    </div>
</header>

<div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-lg-2 col-md-3 d-md-block bg-light sidebar collapse">
            <div class="sidebar-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/">
                            <span data-feather="home" class="align-text-bottom"></span>
                            <i class="fa fa-home text-success"></i> Home
                        </a>
                    </li>
                    @can('view admission')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admission.index') }}">
                                <span data-feather="file" class="align-text-bottom"></span>
                                <i class="fa fa-address-card text-success"></i> Applications
                            </a>
                        </li>
                    @endcan
                    @can('view payment and control number')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('payments') }}">
                                <span data-feather="shopping-cart" class="align-text-bottom"></span>
                                <i class="fa fa-money text-success"></i> Payments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route("control-numbers") }}">
                                <span data-feather="users" class="align-text-bottom"></span>
                                <i class="fa fa-bookmark text-success"></i> Control Numbers
                            </a>
                        </li>
                    @endcan
                    @can('view selection')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('selections') }}">
                                <span data-feather="bar-chart-2" class="align-text-bottom"></span>
                                <i class="fa fa-check-square text-success"></i> Selection
                            </a>
                        </li>
                    @endcan

                    @can('view course')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('course.index') }}">
                                <span data-feather="layers" class="align-text-bottom"></span>
                                <i class="fa fa-graduation-cap text-success"></i> Courses
                            </a>
                        </li>
                    @endcan
                    @can('view intake')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('intake.index') }}">
                                <span data-feather="file" class="align-text-bottom"></span>
                                <i class="fa fa-download text-success"></i> Intakes
                            </a>
                        </li>
                    @endcan
                    @can('send sms')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('send.sms.form') }}">
                                <span data-feather="file" class="align-text-bottom"></span>
                                <i class="fa fa-comment text-success"></i> SMS
                            </a>
                        </li>
                    @endcan
                </ul>

                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
                    <span>Nacte</span>
                    <a class="link-secondary" href="#" aria-label="Add a new report">
                        <span data-feather="plus-circle" class="align-text-bottom"></span>
                    </a>
                </h6>

                <ul class="nav flex-column mb-2">
                    @can('view user')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route("nacte.feedback-error") }}">
                                <span data-feather="layers" class="align-text-bottom"></span>
                                <i class="fa fa-bug text-success"></i> Feedback Errors
                            </a>
                        </li>
                    @endcan
                    @can('view selection')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route("nacte.pushed.list") }}">
                                <span data-feather="layers" class="align-text-bottom"></span>
                                <i class="fa fa-download text-success"></i> Pushed List
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route("nacte.verified.student") }}">
                                <span data-feather="layers" class="align-text-bottom"></span>
                                <i class="fa fa-check text-success"></i> Verified Students
                            </a>
                        </li>
                    @endcan
                </ul>

                @canany(['view payment report','view admission report','search result'])
                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
                    <span>Reports</span>
                    <a class="link-secondary" href="#" aria-label="Add a new report">
                        <span data-feather="plus-circle" class="align-text-bottom"></span>
                    </a>
                </h6>
                <ul class="nav flex-column mb-2">
                    @can('view payment report')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('report.payment') }}">
                            <span data-feather="file-text" class="align-text-bottom"></span>
                            <i class="fa fa-file-pdf-o text-success"></i> Payments Report
                        </a>
                    </li>
                    @endcan
                    @can('view admission report')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('report.admission') }}">
                            <span data-feather="file-text" class="align-text-bottom"></span>
                            <i class="fa fa-file-pdf-o text-success"></i> Applications Report
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('report.admission_by_course') }}">
                            <span data-feather="file-text" class="align-text-bottom"></span>
                            <i class="fa fa-file-pdf-o text-success"></i> Applications by Course
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('report.selection_by_course') }}">
                            <span data-feather="file-text" class="align-text-bottom"></span>
                            <i class="fa fa-file-pdf-o text-success"></i> Selection by Course
                        </a>
                    </li>
                    @endcan
                    @can('search result')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('report.search_results') }}">
                            <span data-feather="file-text" class="align-text-bottom"></span>
                            <i class="fa fa-search text-success"></i> Search Results
                        </a>
                    </li>
                    @endcan

                </ul>
                @endcanany

                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
                    <span>Setting</span>
                    <a class="link-secondary" href="#" aria-label="Add a new report">
                        <span data-feather="plus-circle" class="align-text-bottom"></span>
                    </a>
                </h6>

                <ul class="nav flex-column mb-2">
                    @can('view user')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route("user.index") }}">
                                <span data-feather="layers" class="align-text-bottom"></span>
                                <i class="fa fa-users text-success"></i> Manage Users
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="/admin/user-activity">
                                <span data-feather="layers" class="align-text-bottom"></span>
                                <i class="fa fa-lock text-success"></i> User Activity
                            </a>
                        </li>
                    @endcan

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route("user.change.password") }}">
                            <span data-feather="file-text" class="align-text-bottom"></span>
                            <i class="fa fa-cogs text-success"></i> Change password
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <main role="main" class="col-md-9 ms-sm-auto col-lg-10 offset-lg-2 offset-md-3 px-md-4">
            @include('errors')

            @yield('content')
        </main>
    </div>
</div>

<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2"
        crossorigin="anonymous"></script>
<script src="{{ asset('js/jquery.min.js') }}"></script>

@livewireScripts

@stack('scripts')
<script>
    // Print

    function PrintElem(elem) {
        Popup($(elem).html());
    }

    function Popup(data) {
        //        Print date
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1; //January is 0!
        var yyyy = today.getFullYear();
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();
        if (h < 10) {
            h = '0' + h;
        }
        if (h < 10) {
            m = '0' + m;
        }
        if (h < 10) {
            s = '0' + s;
        }
        var time = h + ':' + m + ':' + s;

        if (dd < 10) {
            dd = '0' + dd
        }

        if (mm < 10) {
            mm = '0' + mm
        }
        today = dd + '/' + mm + '/' + yyyy + ' at ' + time;
        var mywindow = window.open('', 'Admission Form Print', 'height=800,width=900');
        mywindow.document.write('<html><head><title>Admission Form  Printing</title>');
        {{--mywindow.document.write('<link rel="stylesheet" href="{{ asset('css/simple.min.css') }}"></link>');--}}
        mywindow.document.write('<h1 align="center"><img src="{{ asset('images/logo-02.png') }}" width="100"> </h1>');
        mywindow.document.write('<h1 align="center">Zanzibar School of Health</h1>');
        mywindow.document.write('<h3 align="center">Admission Form</h3>');
        mywindow.document.write('<style>.hide{display: none} fieldset {margin-bottom: 12px} table {font-family: "Times New Roman",Courier,arial, sans-serif;border-collapse: collapse;width: 100%;}td, th { border: 1px solid #dddddd;text-align: left;padding: 4px;}tr:nth-child(even) {background-color: #ffffff;}</style>');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('Printed On: ' + today);
        mywindow.document.write('<div style="position: fixed;bottom:0;width: 100%;">' +
            '<hr/>' +
            '<small> ZSH Admission System | <i>Proundly Zanzibar</i></small> ' +
            '</div>');
        mywindow.document.write('</body></html>');

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10

        mywindow.print();
        // mywindow.close();

        return true;
    }

</script>
</body>
</html>
