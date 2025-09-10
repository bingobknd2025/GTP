@extends('admin.layouts.app')

@section('content')

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between d-flex align-items-center">
                        <div class="card-title">KYC Details</div>
                        <a href="{{ route('admin.kycs.index') }}" class="btn btn-sm btn-secondary">Back to KYC List</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap w-100">
                                <tbody>
                                    <tr>
                                        <th>KYC ID:</th>
                                        <td>{{ $kyc->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Customer Name:</th>
                                        <td>{{ $kyc->customer->fname ?? 'N/A' }} {{ $kyc->customer->lname ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>First Name:</th>
                                        <td>{{ $kyc->first_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Last Name:</th>
                                        <td>{{ $kyc->last_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>{{ $kyc->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Country Code:</th>
                                        <td>{{ $kyc->country_code }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone Number:</th>
                                        <td>{{ $kyc->phone_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>Date of Birth:</th>
                                        <td>{{ $kyc->dob }}</td>
                                    </tr>
                                    <tr>
                                        <th>Social Media:</th>
                                        <td>{{ $kyc->social_media }}</td>
                                    </tr>
                                    <tr>
                                        <th>Address:</th>
                                        <td>{{ $kyc->address }}</td>
                                    </tr>
                                    <tr>
                                        <th>City:</th>
                                        <td>{{ $kyc->city }}</td>
                                    </tr>
                                    <tr>
                                        <th>State:</th>
                                        <td>{{ $kyc->state }}</td>
                                    </tr>
                                    <tr>
                                        <th>Country:</th>
                                        <td>{{ $kyc->country }}</td>
                                    </tr>
                                    <tr>
                                        <th>Document Type:</th>
                                        <td>{{ $kyc->document_type }}</td>
                                    </tr>
                                    <tr>
                                        <th>KYC Type:</th>
                                        <td>{{ $kyc->kyc_type }}</td>
                                    </tr>
                                    <tr>
                                        <th>Source:</th>
                                        <td>{{ $kyc->source }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            @if($kyc->status == 0)
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($kyc->status == 1)
                                                <span class="badge bg-success">Approved</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Front Image:</th>
                                        <td>
                                            @if($kyc->frontimg)
                                                <img src="{{ asset('storage/' . $kyc->frontimg) }}" width="100px" class="mt-2"/>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Back Image:</th>
                                        <td>
                                            @if($kyc->backimg)
                                                <img src="{{ asset('storage/' . $kyc->backimg) }}" width="100px" class="mt-2"/>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created At:</th>
                                        <td>{{ $kyc->created_at }}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated At:</th>
                                        <td>{{ $kyc->updated_at }}</td>
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
