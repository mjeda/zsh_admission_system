@extends('layouts.portal')

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div>
                <h4 class="card-title mb-1">Account Setting</h4>
                <p class="text-muted mb-0">
                    Use this section to update your password and basic personal information. For any additional changes, please contact the system administrator.
                </p>
            </div>

            <div class="row">
                <div class="col">
                    <form action="{{ route('user.update.password') }}" method="POST" class="pb-3">
                        @csrf
                        <div class="row">
                            <div class="form-group col pt-3 pr-3 pl-3">
                                <label class="form-label">Current password</label>
                                <input wire:model="current_password" type="password" name="current_password" class="form-control  @error('current_password') is-invalid @enderror" placeholder="Current password">
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
        </div>
    </div>
@endsection
