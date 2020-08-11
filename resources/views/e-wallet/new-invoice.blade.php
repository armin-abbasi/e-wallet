@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Create new invoice record
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <form action="{{ route('store.invoice', ['wallet_id' => $walletId]) }}" method="post">
                                <div class="input-group form-group">
                                    <input type="text" name="description" class="form-control" placeholder="Description">
                                </div>
                                @error('description')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <div class="input-group form-group">
                                    <select id="type" name="type" class="form-control">
                                        <option value="">Invoice Type</option>
                                        <option value="debit">Debit</option>
                                        <option value="credit">Credit</option>
                                    </select>
                                </div>
                                @error('type')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <div class="input-group form-group">
                                    <input type="text" name="amount" class="form-control" placeholder="Amount">
                                </div>
                                @error('amount')
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