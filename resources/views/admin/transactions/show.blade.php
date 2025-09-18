@extends('admin.layouts.app')

@section('content')

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between d-flex align-items-center">
                        <div class="card-title">Withdraw Details</div>
                        <a href="{{ route('admin.withdraws.index') }}" class="btn btn-sm btn-secondary">Back to Withdraw List</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap w-100">
                                <tbody>
                                    <tr>
                                        <th>Withdraw ID:</th>
                                        <td>{{ $withdrawal->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Trnx. ID:</th>
                                        <td>{{ $withdrawal->txn_id ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Customer Name:</th>
                                        <td>{{ $withdrawal->customer->fname ?? 'N/A' }} {{ $withdrawal->customer->lname ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Amount:</th>
                                        <td>{{ number_format($withdrawal->amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Payment Method:</th>
                                        <td>{{ $withdrawal->payment_mode ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Reference Number:</th>
                                        <td>{{ $withdrawal->reference_number ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Source:</th>
                                        <td>{{ $withdrawal->source }}</td>
                                    </tr>
                                    <tr>
                                        <th>Charges:</th>
                                        <td>{{ number_format($withdrawal->charges, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>To Deduct:</th>
                                        <td>{{ number_format($withdrawal->to_deduct, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Pay Details:</th>
                                        <td>{{ $withdrawal->pay_details ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Comment:</th>
                                        <td>{{ $withdrawal->comment ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            @if($withdrawal->status == 'Pending')
                                            <span class="badge bg-warning">Pending</span>
                                            @elseif($withdrawal->status == 'Approved')
                                            <span class="badge bg-success">Approved</span>
                                            @elseif($withdrawal->status == 'Rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                            @else
                                            <span class="badge bg-secondary">{{ $withdrawal->status }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created At:</th>
                                        <td>{{ $withdrawal->created_at }}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated At:</th>
                                        <td>{{ $withdrawal->updated_at }}</td>
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