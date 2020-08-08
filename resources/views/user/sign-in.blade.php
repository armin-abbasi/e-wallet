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
                <form>
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="Email">

                    </div>
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                        </div>
                        <input type="password" class="form-control" placeholder="Password">
                    </div>
                    <div class="row align-items-center remember">
                        <input type="checkbox">&nbsp;Remember Me
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Login" class="btn float-right btn-info">
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-center links">
                    Don't have an account?<a href="#">&nbsp;Sign Up</a>
                </div>
            </div>
        </div>
    </div>
@endsection