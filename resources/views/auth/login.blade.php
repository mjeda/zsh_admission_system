@extends('layouts.guest')

@section('content')
    <div class="container">
        <!--
        <div class="row justify-content-center">
            <div class="col-md-6 text-center mb-5">
                <h2 class="heading-section" style="color: #e3b04b">Zanzibar School Of Health</h2>
                <h5 style="color: #002752">Student Admission System</h5>
            </div>
        </div>
    -->
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-12">
                <div class="wrap d-md-flex">
                    <div class="img">
                        <img class="logo-img" src="{{asset("images/logo.png")}}" alt="Zanzibar school of health logo">
                        <div class="px-1 py-4">
                            @php
                                $v_intake = \App\Models\Intake::where('status', 'Open')->where('type', 'VTA/VETA')->latest()->first();
                                $d_intake = \App\Models\Intake::where('status', 'Open')->where('type', 'Diploma')->latest()->first();
                            @endphp
                            <h5 class="text-dark text-capitalize">Application
                            @if(\App\Models\Intake::where('status', 'Open')->count() > 0)
                                    For the {{ $v_intake->title ?? $d_intake->title }} intake is now <span class="text-success">OPEN</span>
                                </h5>
                                @if($d_intake)
                                    <h5 class="font-weight-bold">For Diploma <a href="{{ route('register') }}"><b>Click Here</b></a> to Apply</h5>
                                    <h5>
                                        <i class="fa fa-calendar"></i> Deadline:
                                        <span class="text-danger">{{ \Carbon\Carbon::parse($d_intake->expire_date)->format('jS F Y') }}</span>
                                    </h5>
                                @endif
                                @if($v_intake)
                                    <h5 class="font-weight-bold">For VTA/VETA <a href="{{ route('veta.register') }}"><b>Click Here</b></a> to Apply</h5>
                                    <h5>
                                        <i class="fa fa-calendar"></i> Deadline:
                                        <span class="text-danger">{{ \Carbon\Carbon::parse($v_intake->expire_date)->format('jS F Y') }}</span>
                                    </h5>
                                @endif
                            @else <h4>Application is Closed</h4> @endif
                            <fieldset class="mt-4">
                                <legend><b>Important Message to New Applicants</b></legend>
                                <p class="">
                                    Welcome to the Zanzibar School of Health Admissions Portal. We're delighted that you're considering applying to our institution.
                                    Before proceeding, please take note of the following:
                                </p>
                                <ol>
                                    <li>
                                        <b>Non-refundable Application Fee:</b>
                                        Kindly be aware that there is a non-refundable application fee associated with your submission.
                                    </li>
                                    <li>
                                        <b>Entry Requirements:</b>
                                        Ensure that you have carefully reviewed and understood the entry requirements for the course(s) you intend to apply for before starting your application process.
                                    </li>
                                    <li>
                                        <b>Application Does Not Guarantee Selection:</b>
                                        Please note that our admissions process is competitive, and meeting the minimum requirements does not automatically secure admission.
                                    </li>
                                </ol>

                                If you have any questions or need assistance during the application process, please don't hesitate to contact our admissions team.
                            </fieldset>
                        </div>
                    </div>
                    <div class="login-wrap p-4 p-md-5">
                        <div class="d-flex">
                            <div class="w-100">
                                <h4 class="mb-4 text-muted">Login ZSH to Admission System</h4>
                            </div>
                        </div>
                        <form action="{{ route('login') }}" method="post" class="signin-form">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="label" for="name">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="text-muted">If you forget email, please contact our admissions team</small>
                            </div>
                            <div class="form-group mb-3">
                                <label class="label" for="password">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="text-muted">If you forget password, click forget password link under login button</small>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="form-control btn btn-primary rounded submit px-3">Login</button>
                            </div>
                            <div class="form-group d-md-flex">
                                <div class="w-50 text-left">
                                    <label class="checkbox-wrap checkbox-primary mb-0">Remember Me
                                        <input type="checkbox"  name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="w-50 text-md-right">
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="btn-link text-primary" style="text-decoration: underline">
                                            <b>Forgot Password</b>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                        <h5>Open Courses For Application</h5>
                        <h6 class="text-capitalize ">
                            click any course below to check its entry requirements
                        </h6>
                        @php
                            $d_courses = \App\Models\Course::where('status', 'Open')->where('type', 'Diploma')->get();
                        @endphp
                        <strong class="fw-bold">Diploma Course</strong>
                        <table class="table table-borderless table-sm">
                            @forelse($d_courses as $course)
                                <tr>
                                    <th class="text-success">{{$loop->index + 1}}</th>
                                    <th class="text-success text-capitalize">
                                        <a href="https://zsh.ac.tz/" target="_blank">
                                            {{ucfirst(strtolower($course->name))}}
                                        </a>
                                    </th>
                                </tr>
                            @empty
                            @endforelse
                        </table>
                        @php
                            $veta_courses = \App\Models\Course::where('status', 'Open')->where('type', 'VTA/VETA')->get();
                        @endphp
                        <strong class="fw-bold">VTA/VETA Course</strong>
                        <table class="table table-borderless table-sm">
                            @forelse($veta_courses as $course)
                                <tr>
                                    <th class="text-success">{{$loop->index + 1}}</th>
                                    <th class="text-success text-capitalize">
                                        <a href="https://zsh.ac.tz/" target="_blank">
                                            {{ucfirst(strtolower($course->name))}}
                                        </a>
                                    </th>
                                </tr>
                            @empty
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
