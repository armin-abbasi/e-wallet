@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Total balance: {{ $totalBalance }}
                        <a href="{{ route('create.wallet') }}" class="btn btn-success btn-sm float-right">New Wallet</a>
                    </div>

                    <div class="card-body">
                        @if(count($wallets))
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Balance</th>
                                    <th scope="col">Invoice</th>
                                    <th scope="col">Operations</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($wallets as $wallet)
                                    <tr>
                                        <th scope="row">{{ $wallet->id }}</th>
                                        <td>{{ $wallet->name }}</td>
                                        <td>{{ $wallet->type }}</td>
                                        <td>{{ $wallet->balance }}</td>
                                        <td><a href="{{ route('show.wallet', ['wallet_id' => $wallet->id]) }}"
                                               class="btn btn-sm btn-primary">Browse</a></td>
                                        <td>
                                            <a href="{{ route('show.update.wallet', ['wallet_id' => $wallet->id]) }}"
                                               class="btn btn-sm btn-secondary mr-2">Update</a>
                                            <a href="{{ route('delete.wallet', ['wallet_id' => $wallet->id]) }}"
                                               class="btn btn-sm btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            {{ __('messages.no_wallets') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection