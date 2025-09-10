@extends('admin.layouts.app')

@section('content')

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between d-flex align-items-center">
                        <div class="card-title">Customer Details</div>
                        <a href="{{ route('admin.customers.index') }}" class="btn btn-sm btn-secondary">Back to Customers</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap w-100">
                                <tbody>
                                    <tr>
                                        <th>Customer ID:</th>
                                        <td>{{ $customer->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>KYC ID:</th>
                                        <td>{{ $customer->kyc_id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Franchise Name:</th>
                                        <td>{{ $customer->franchise->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>First Name:</th>
                                        <td>{{ $customer->fname }}</td>
                                    </tr>
                                    <tr>
                                        <th>Last Name:</th>
                                        <td>{{ $customer->lname }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>{{ $customer->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Mobile No.:</th>
                                        <td>{{ $customer->mobile_no }}</td>
                                    </tr>
                                    <tr>
                                        <th>Account Balance:</th>
                                        <td>{{ $customer->account_balance }}</td>
                                    </tr>
                                    <tr>
                                        <th>Account Name:</th>
                                        <td>{{ $customer->account_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Account Type:</th>
                                        <td>{{ $customer->account_type }}</td>
                                    </tr>
                                    <tr>
                                        <th>Account Number:</th>
                                        <td>{{ $customer->account_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>Account Bank:</th>
                                        <td>{{ $customer->account_bank }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            @if($customer->status)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Email Verified:</th>
                                        <td>
                                            @if($customer->email_verfied)
                                                <span class="badge bg-success">Verified</span>
                                            @else
                                                <span class="badge bg-danger">Unverified</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Mobile Verified:</th>
                                        <td>
                                            @if($customer->mobile_verfied)
                                                <span class="badge bg-success">Verified</span>
                                            @else
                                                <span class="badge bg-danger">Unverified</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created At:</th>
                                        <td>{{ $customer->created_at }}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated At:</th>
                                        <td>{{ $customer->updated_at }}</td>
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
