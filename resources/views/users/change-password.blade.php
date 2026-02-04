@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Change Password</h1>
    </div>

    <div class="row">
        <div class="col">
            <form action="{{ route('user.update.password') }}" method="POST" class="pb-3">
                @csrf
                <div class="row">
                    <div class="form-group col pt-3 pr-3 pl-3">
                        <label>Current password</label>
                        <input wire:model="current_password" type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Current password">
                        @error('current_password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6 pt-2 pr-3 pl-3">
                        <label for="password">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Password">
                        @error("password")
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-12 col-md-6 pt-2 pr-3 pl-3">
                        <label for="conf_password">Confirm Password</label>
                        <input type="password" class="form-control" name="password_confirmation" id="conf_password" placeholder="confirm Password">
                    </div>
                </div>
                <div class="row  pt-3 pr-4">
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary" name ="add_user">Change password</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
