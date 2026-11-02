@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Correct error for applicant <strong>{{ $applicant->user_id }}</strong></h1>
    </div>
    <div class="row">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('nacte.upload-feedback-error', $data) }}">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <label for="firstname">First name</label>
                            <input type="text" value="{{ $applicant->firstname }}" name="first_name" class="form-control @error('first_name') is-invalid @enderror" id="firstname">
                            @error("first_name")
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col">
                            <label for="second_name">Second name</label>
                            <input type="text" value="{{ $applicant->secondname }}" name="second_name" class="form-control @error('first_name') is-invalid @enderror" id="second_name">
                            @error("second_name")
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col">
                            <label for="surname">Surname</label>
                            <input type="text" value="{{ $applicant->surname }}" name="surname" class="form-control @error('first_name') is-invalid @enderror" id="surname">
                            @error("surname")
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" value="{{ $applicant->email_address }}" name="email" class="form-control @error('first_name') is-invalid @enderror" id="email">
                            @error("email")
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col">
                            <label for="phone_number">Phone number</label>
                            <input type="number" value="{{ $applicant->mobile_number }}" name="phone_number" class="form-control @error('first_name') is-invalid @enderror" id="phone_number">
                            @error("phone_number")
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col">
                            <label for="form_four_year">Academic Year</label>
                            <input type="text" value="{{ $applicant->form_four_year }}" name="form_four_year" class="form-control @error('form_four_year') is-invalid @enderror" id="form_four_year">
                            @error("form_four_year")
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col">
                            <label for="form_four_index">Form four index number</label>
                            <input type="text" value="{{ $applicant->form_four_indexnumber }}" name="form_four_index" class="form-control @error('form_four_index') is-invalid @enderror" id="form_four_index">
                            @error("form_four_index")
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="" class="row">.</label>
                            <button type="submit" class="btn btn-primary float-end">
                                send change
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
