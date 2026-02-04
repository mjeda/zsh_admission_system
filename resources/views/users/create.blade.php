@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Add User Account</h1>
    </div>
    <div class="row">
        <div class="col">
            <form action="{{ route('user.store') }}" method="POST" class="pb-3">
                @csrf
                <div class="row pt-3 pr-3 pl-3">
                    <label for="username">Username</label>
                    <input type="text" value="{{ old("username") }}" class="form-control @error('username') is-invalid @enderror" name="username" id="username" placeholder="Username">
                    @error("username")
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-6 pt-3 pr-3 pl-3">
                        <label for="email">Email</label>
                        <input type="email" value="{{ old("email") }}" class="form-control @error('email') is-invalid @enderror" name="email" id="email" placeholder="Email">
                        @error("email")
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-12 col-md-6 col-lg-6 pt-3 pr-3 pl-3">
                        <label for="role">Role</label>
                        <select class="form-select @error('role') is-invalid @enderror" name="role" id="role">
                            <option value="{{ old("type") }}">select role</option>
                            @foreach( $roles as $role)
                                <option value="{{ $role }}">{{ $role }}</option>
                            @endforeach
                        </select>
                        @error("role")
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6 pt-3 pr-3 pl-3">
                        <label for="password">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Password">
                        @error("password")
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-12 col-md-6 pt-3 pr-3 pl-3">
                        <label for="conf_password">Confirm Password</label>
                        <input type="password" class="form-control" name="password_confirmation" id="conf_password" placeholder="confirm Password">
                    </div>
                </div>
                <div class="row  pt-3 pr-4">
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary" name ="add_user">Add user</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
