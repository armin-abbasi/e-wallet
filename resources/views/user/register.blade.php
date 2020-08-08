@extends('master')

@section('title', 'Register')

@section('content')
    <div class="d-flex justify-content-center h-100">
        <div class="card mt-5">
            <div class="card-header">
                <h3 class="float-left">Register</h3>
            </div>
            <div class="card-body">
                <form>
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="Name">
                    </div>
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
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                        </div>
                        <input type="password" class="form-control" placeholder="Re-Password">
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Submit" class="btn float-right btn-info">
                        <input type="reset" value="Reset" class="btn float-left btn-danger">
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-center links">
                    Your account will be usable after verification.
                </div>
            </div>
        </div>
    </div>
@endsection