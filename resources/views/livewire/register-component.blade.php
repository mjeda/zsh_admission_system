<div class="row justify-content-center">
    <div class="col-md-12 col-lg-12">
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
                    <!-- Index Number Field -->
                    <div class="form-group">
                        <label class="label" for="index_number">Index Number</label>
                        <input type="text" class="form-control @error('index_number') is-invalid @enderror"
                               wire:model="index_number" name="index_number" required
                               autocomplete="index_number" autofocus
                               placeholder="S0000/0000/0000" data-inputmask-mask="S9999/9999/9999"
                               id="index_number">
                        @error('index_number')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <!-- Loading Indicator -->
                    @if($loading)
                        <div class="d-flex align-items-center">
                            <div class="spinner-border text-primary mr-2" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <span>Please wait, we verifying your Index Number</span>
                        </div>
                    @endif

                    <!-- Error Handling for API Failures -->
                    @if($apiError)
                        <div class="alert alert-danger mt-3">
                            {{ $apiError }}
                        </div>
                    @endif

                    @if($verified)
                        <strong class="text-success">
                            <i class="fa fa-check"></i> {{$studentData['first_name']}} {{$studentData['middle_name']}} {{$studentData['last_name']}}
                        </strong>
                        <!-- Phone number Field -->
                        <div class="form-group">
                            <label class="label" for="phone_number">Phone number</label>
                            <input type="text" class="form-control @error('phone_number') is-invalid @enderror"
                                   wire:model.defer="phone_number" required placeholder="0xxxxxxxxxx">
                            @error("phone_number")
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
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
                                    wire:loading.attr="disabled"
                                    @if(!$verified) disabled @endif>
                                Register
                            </button>
                        </div>
                    @elseif($failed && !$apiError)
                        <p class="alert alert-danger mt-3">
                            We can't verify the index number you entered. Please double-check its accuracy or contact us
                            for help.
                        </p>
                    @endif
                @else
                    <div class="alert alert-warning">
                        Registration is currently closed. Please check back later.
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
