@extends('admin.layouts.app')

@section('content')

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between d-flex align-items-center">
                        <div class="card-title">Deposit Details</div>
                        <a href="{{ route('admin.deposits.index') }}" class="btn btn-sm btn-secondary">Back to Deposit List</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap w-100">
                                <tbody>
                                    <tr>
                                        <th>Deposit ID:</th>
                                        <td>{{ $deposits->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Trnx. ID:</th>
                                        <td>{{ $deposits->txn_id ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Customer Name:</th>
                                        <td>{{ $deposits->customer->fname ?? 'N/A' }} {{ $deposits->customer->lname ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Amount:</th>
                                        <td>{{ number_format($deposits->amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Payment Method:</th>
                                        <td>{{ $deposits->payment_mode ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Plan:</th>
                                        <td>{{ $deposits->plan->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Reference Number:</th>
                                        <td>{{ $deposits->reference_number ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Source:</th>
                                        <td>{{ $deposits->source }}</td>
                                    </tr>
                                    <tr>
                                        <th>Proof:</th>
                                        <td>
                                            @if($deposits->proof)
                                            <img src="{{ asset('storage') . '/' . $deposits->proof }}" width="120px" class="mt-2" />
                                            @else
                                            N/A
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            @if($deposits->status == 0)
                                            <span class="badge bg-warning">Pending</span>
                                            @elseif($deposits->status == 1)
                                            <span class="badge bg-success">Approved</span>
                                            @else
                                            <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created At:</th>
                                        <td>{{ $deposits->created_at }}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated At:</th>
                                        <td>{{ $deposits->updated_at }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection