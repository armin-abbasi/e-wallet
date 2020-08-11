@extends('layouts.app')

@section('title', 'Update Wallet')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Create new wallet
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <form action="{{ route('update.wallet', ['wallet_id' => $wallet['id']]) }}" method="post">
                                <div class="input-group form-group">
                                    <input type="text" name="name" class="form-control" placeholder="Name"
                                           value="{{ $wallet['name'] }}">
                                </div>
                                @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <div class="input-group form-group">
                                    <input type="text" name="type" class="form-control" placeholder="Type"
                                           value="{{ $wallet['type'] }}">
                                </div>
                                @error('type')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <div class="form-group">
                                    <input type="submit" value="Submit" class="btn float-right btn-info">
                                    <input type="reset" value="Reset" class="btn float-left btn-danger">
                                </div>
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection