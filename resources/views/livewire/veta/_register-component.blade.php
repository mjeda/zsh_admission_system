<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-12">
            @include('errors')
            <div class="wrap d-md-flex">
                <div class="img">
                    <img class="logo-img" src="{{asset("images/logo.png")}}" alt="zanzibar school of health logo">
                    <div class="px-1 py-2">
                        <h4 class="text-dark">
                            Already have an Account ? <a href="{{ route('login') }}">Login here</a>
                        </h4>
                        <fieldset class="mt-4">
                            <legend><b>Important Message to New Applicants</b></legend>
                            <p class="">
                                Welcome to the Zanzibar School of Health Admissions Portal. We're delighted that you're
                                considering applying to our institution.
                                Before proceeding, please take note of the following:
                            </p>
                            <ol>
                                <li>
                                    <b>Non-refundable Application Fee:</b>
                                    Kindly be aware that there is a non-refundable application fee associated with your
                                    submission.
                                </li>
                                <li>
                                    <b>Entry Requirements:</b>
                                    Ensure that you have carefully reviewed and understood the entry requirements for the
                                    course(s) you intend to apply for before starting your application process.
                                </li>
                                <li>
                                    <b>Application Does Not Guarantee Selection:</b>
                                    Please note that our admissions process is competitive, and meeting the minimum
                                    requirements does not automatically secure admission.
                                </li>
                            </ol>

                            If you have any questions or need assistance during the application process, please don't
                            hesitate to contact our admissions team.
                        </fieldset>
                    </div>
                </div>
                <!-- Registration Form -->
                <form wire:submit.prevent="submitForm" class="signin-form login-wrap p-4 p-md-5">
                    @csrf
                    @if($intake)
                        <div class="d-flex">
                            <div class="w-100">
                                <h3 class="mb-3">Registration Form</h3>
                                @error('particular')
                                <h5 class="text-danger">{{ $message }}</h5>
                                @enderror
                            </div>
                        </div>

                        <!-- Email Field -->
                        <div class="form-group">
                            <label class="label" for="email">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   wire:model.defer="email" name="email" required autocomplete="email"
                                   placeholder="Email">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div class="form-group">
                            <label class="label" for="password">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   wire:model.defer="password" name="password" required autocomplete="new-password"
                                   placeholder="Password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Confirm Password Field -->
                        <div class="form-group">
                            <label class="label" for="password_confirmation">Confirm Password</label>
                            <input type="password" class="form-control"
                                   wire:model.defer="password_confirmation" name="password_confirmation" required
                                   autocomplete="new-password" placeholder="Confirm Password">
                        </div>

                        <!-- Register Button -->
                        <div class="form-group">
                            <button type="submit"
                                    class="btn btn-primary btn-block rounded"
                                    wire:loading.attr="disabled">
                                Register
                            </button>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            Registration is currently closed. Please check back later.
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
