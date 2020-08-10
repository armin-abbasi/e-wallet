@extends('master')

@section('title', 'Sign-in')

@section('content')
    <div class="d-flex justify-content-center h-100">
        <div class="card mt-5">
            <div class="card-header">
                <h3 class="float-left">Sign In</h3>
                <div class="d-flex justify-content-end social_icon float-right">
                    <span><i class="fab fa-facebook-square"></i></span>
                    <span><i class="fab fa-google-plus-square"></i></span>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('sign-in') }}" method="post">
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        </div>
                        <input type="text" name="email" class="form-control" placeholder="Email">

                    </div>
                    @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                        </div>
                        <input type="password" name="password" class="form-control" placeholder="Password">
                    </div>
                    @error('password')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <div class="row align-items-center remember">
                        <input name="remember" type="checkbox">&nbsp;Remember Me
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Login" class="btn float-right btn-info">
                    </div>
                    @csrf
                </form>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-center links">
                    Don't have an account?<a href="{{ route('register') }}">&nbsp;Sign Up</a>
                </div>
            </div>
        </div>
    </div>
@endsection