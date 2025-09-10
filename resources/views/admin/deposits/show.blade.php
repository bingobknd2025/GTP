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
                                        <th>KYC ID:</th>
                                        <td>{{ $deposits->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Customer Name:</th>
                                        <td>{{ $deposits->customer->fname ?? 'N/A' }} {{ $deposits->customer->lname ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>First Name:</th>
                                        <td>{{ $deposits->first_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Last Name:</th>
                                        <td>{{ $deposits->last_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>{{ $deposits->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Country Code:</th>
                                        <td>{{ $deposits->country_code }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone Number:</th>
                                        <td>{{ $deposits->phone_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>Date of Birth:</th>
                                        <td>{{ $deposits->dob }}</td>
                                    </tr>
                                    <tr>
                                        <th>Social Media:</th>
                                        <td>{{ $deposits->social_media }}</td>
                                    </tr>
                                    <tr>
                                        <th>Address:</th>
                                        <td>{{ $deposits->address }}</td>
                                    </tr>
                                    <tr>
                                        <th>City:</th>
                                        <td>{{ $deposits->city }}</td>
                                    </tr>
                                    <tr>
                                        <th>State:</th>
                                        <td>{{ $deposits->state }}</td>
                                    </tr>
                                    <tr>
                                        <th>Country:</th>
                                        <td>{{ $deposits->country }}</td>
                                    </tr>
                                    <tr>
                                        <th>Document Type:</th>
                                        <td>{{ $deposits->document_type }}</td>
                                    </tr>
                                    <tr>
                                        <th>KYC Type:</th>
                                        <td>{{ $deposits->kyc_type }}</td>
                                    </tr>
                                    <tr>
                                        <th>Source:</th>
                                        <td>{{ $deposits->source }}</td>
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
                                        <th>Front Image:</th>
                                        <td>
                                            @if($deposits->frontimg)
                                            <img src="{{ asset('storage/' . $deposits->frontimg) }}" width="100px" class="mt-2" />
                                            @else
                                            N/A
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Back Image:</th>
                                        <td>
                                            @if($deposits->backimg)
                                            <img src="{{ asset('storage/' . $deposits->backimg) }}" width="100px" class="mt-2" />
                                            @else
                                            N/A
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