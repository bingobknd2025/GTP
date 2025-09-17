@extends('admin.layouts.app')

@section('content')

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Show Franchise Details</div>
                        <div class="text-end mb-3">
                            <a href="{{ route('admin.franchises.index') }}">
                                <button type="button" class="btn btn-primary btn-sm">
                                    <i class="fas fa-arrow-left me-1"></i> Back
                                </button>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row gy-4">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <strong>Code:</strong>
                                    <div class="mt-1">{{ $franchise->code }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <strong>Name:</strong>
                                    <div class="mt-1">{{ $franchise->name }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <strong>Email:</strong>
                                    <div class="mt-1">{{ $franchise->email }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <strong>Contact No.:</strong>
                                    <div class="mt-1">{{ $franchise->contact_no }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <strong>Pincode:</strong>
                                    <div class="mt-1">{{ $franchise->pincode }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <strong>Address:</strong>
                                    <div class="mt-1">{{ $franchise->address }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <strong>Contact Person Name:</strong>
                                    <div class="mt-1">{{ $franchise->contact_person_name }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <strong>Contact Person Number:</strong>
                                    <div class="mt-1">{{ $franchise->contact_person_number }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <strong>Store Latitude:</strong>
                                    <div class="mt-1">{{ $franchise->store_lat }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <strong>Store Longitude:</strong>
                                    <div class="mt-1">{{ $franchise->store_long }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <strong>Status:</strong>
                                    <div class="mt-1">
                                        @if($franchise->status)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <strong>Image:</strong>
                                    <div class="mt-1">
                                        @if($franchise->image)
                                            <img src="{{ asset($franchise->image) }}" alt="Franchise Image" width="150">
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <strong>Created By:</strong>
                                    <div class="mt-1">{{ $franchise->created_by }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <strong>Updated By:</strong>
                                    <div class="mt-1">{{ $franchise->updated_by }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <strong>Created At:</strong>
                                    <div class="mt-1">{{ $franchise->created_at }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <strong>Updated At:</strong>
                                    <div class="mt-1">{{ $franchise->updated_at }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
