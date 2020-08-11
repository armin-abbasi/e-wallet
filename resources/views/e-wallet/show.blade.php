@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ $wallet['name'] }} / Balance: {{ $wallet['balance'] }}
                        <a href="{{ route('create.invoice', ['wallet_id' => $wallet['id']]) }}"
                           class="btn btn-success btn-sm float-right">New Invoice</a>
                    </div>

                    <div class="card-body">
                        @if(count($wallet['invoices']))
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Operations</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($wallet['invoices'] as $invoice)
                                    <tr>
                                        <th scope="row">{{ $invoice['id'] }}</th>
                                        <td>{{ $invoice['type'] }}</td>
                                        <td>{{ $invoice['amount'] }}</td>
                                        <td>{{ $invoice['description'] }}</td>
                                        <td>{{ \Carbon\Carbon::createFromDate($invoice['created_at'])->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            <a href="{{ route('delete.invoice', ['invoice_id' => $invoice['id']]) }}"
                                               class="btn btn-sm btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            {{ __('messages.no_invoices') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection