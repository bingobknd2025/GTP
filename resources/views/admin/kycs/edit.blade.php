@extends('admin.layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between d-flex align-items-center">
                        <div class="card-title">Edit KYC Entry</div>
                        <a href="{{ route('admin.kycs.index') }}" class="btn btn-sm btn-secondary">Back to KYC List</a>
                    </div>
                    <!-- <div class="card-body">
                        <form id="kycForm" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row gy-4">

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="customer_id" class="form-label">Customer:</label>
                                    <select class="form-control" id="customer_id" name="customer_id">
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ $kyc->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->fname }} {{ $customer->lname }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="first_name" class="form-label">First Name:</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $kyc->first_name }}">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="last_name" class="form-label">Last Name:</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $kyc->last_name }}">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="email" class="form-label">Email:</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $kyc->email }}">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="country_code" class="form-label">Country Code:</label>
                                    <input type="text" class="form-control" id="country_code" name="country_code" value="{{ $kyc->country_code }}">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="phone_number" class="form-label">Phone Number:</label>
                                    <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ $kyc->phone_number }}">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="dob" class="form-label">Date of Birth:</label>
                                    <input type="date" class="form-control" id="dob" name="dob" value="{{ $kyc->dob }}">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="social_media" class="form-label">Social Media:</label>
                                    <input type="text" class="form-control" id="social_media" name="social_media" value="{{ $kyc->social_media }}" placeholder="Enter social media link (e.g., https://facebook.com/yourprofile)">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="address" class="form-label">Address:</label>
                                    <textarea class="form-control" id="address" name="address" rows="2">{{ $kyc->address }}</textarea>
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="city" class="form-label">City:</label>
                                    <input type="text" class="form-control" id="city" name="city" value="{{ $kyc->city }}">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="state" class="form-label">State:</label>
                                    <input type="text" class="form-control" id="state" name="state" value="{{ $kyc->state }}">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="country" class="form-label">Country:</label>
                                    <input type="text" class="form-control" id="country" name="country" value="{{ $kyc->country }}">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="document_type" class="form-label">Document Type:</label>
                                    <input type="text" class="form-control" id="document_type" name="document_type" value="{{ $kyc->document_type }}">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="kyc_type" class="form-label">KYC Type:</label>
                                    <select class="form-control" id="kyc_type" name="kyc_type">
                                        <option value="online" {{ $kyc->kyc_type == 'online' ? 'selected' : '' }}>Online</option>
                                        <option value="offline" {{ $kyc->kyc_type == 'offline' ? 'selected' : '' }}>Offline</option>
                                    </select>
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="source" class="form-label">Source:</label>
                                    <select class="form-control" id="source" name="source">
                                        <option value="APP" {{ $kyc->source == 'APP' ? 'selected' : '' }}>APP</option>
                                        <option value="WEB" {{ $kyc->source == 'WEB' ? 'selected' : '' }}>WEB</option>
                                    </select>
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="frontimg" class="form-label">Front Image:</label>
                                    <input type="file" class="form-control" id="frontimg" name="frontimg">
                                    @if($kyc->frontimg)
                                        <img src="{{ asset('storage/' . $kyc->frontimg) }}" width="50px" class="mt-2"/>
                                    @endif
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="backimg" class="form-label">Back Image:</label>
                                    <input type="file" class="form-control" id="backimg" name="backimg">
                                    @if($kyc->backimg)
                                        <img src="{{ asset('storage/' . $kyc->backimg) }}" width="50px" class="mt-2"/>
                                    @endif
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="status" class="form-label">Status:</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="0" {{ $kyc->status == 0 ? 'selected' : '' }}>Pending</option>
                                        <option value="1" {{ $kyc->status == 1 ? 'selected' : '' }}>Approved</option>
                                        <option value="2" {{ $kyc->status == 2 ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                                <div class="col-12 d-flex justify-content-end">
                                    {{-- @can('KYC Edit') --}}{{-- Permission will be added later --}}
                                    <button type="submit" class="btn btn-sm btn-primary" id="updateKycBtn">
                                        Update KYC Entry
                                    </button>
                                    {{-- @endcan --}}
                                </div>

                            </div>
                        </form>
                    </div> -->
                    <div class="card-body">
                        <form id="kycForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row gy-4">

                                {{-- Customer --}}
                                <div class="col-md-4">
                                    <label for="customer_id" class="form-label">Customer</label>
                                    <input type="text" class="form-control" id="customer_id" name="customer_id" value="{{$kyc->customer_id}}" required>
                                    </select>
                                </div>

                                {{-- First & Last Name --}}
                                <div class="col-md-4">
                                    <label for="first_name" class="form-label">First Name:</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{$kyc->first_name}}" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="last_name" class="form-label">Last Name:</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{$kyc->last_name}}" required>
                                </div>

                                {{-- Email --}}
                                <div class="col-md-4">
                                    <label for="email" class="form-label">Email:</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{$kyc->email}}" required>
                                </div>

                                {{-- Country Code & Phone --}}
                                <div class="col-md-4">
                                    <label for="country_code" class="form-label">Country Code:</label>
                                    <input type="text" class="form-control" id="country_code" name="country_code" value="{{$kyc->country_code}}" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="phone_number" class="form-label">Phone Number:</label>
                                    <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{$kyc->phone_number}}" required>
                                </div>

                                {{-- DOB --}}
                                <div class="col-md-4">
                                    <label for="dob" class="form-label">Date of Birth:</label>
                                    <input type="date" class="form-control" id="dob" name="dob" value="{{$kyc->dob}}" required>
                                </div>

                                {{-- Social Media --}}
                                <div class="col-md-4">
                                    <label for="social_media" class="form-label">Social Media Link:</label>
                                    <input type="url" class="form-control" id="social_media" name="social_media" value="{{$kyc->social_media}}" required>
                                </div>

                                {{-- Address --}}
                                <div class="col-md-4">
                                    <label for="address" class="form-label">Address:</label>
                                    <textarea class="form-control" id="address" name="address" rows="2" required>{{$kyc->address}}</textarea>
                                </div>

                                {{-- City / State / Country --}}
                                <div class="col-md-4">
                                    <label for="city" class="form-label">City:</label>
                                    <input type="text" class="form-control" id="city" name="city" value="{{$kyc->city}}" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="state" class="form-label">State:</label>
                                    <input type="text" class="form-control" id="state" name="state" value="{{$kyc->state}}" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="country" class="form-label">Country:</label>
                                    <input type="text" class="form-control" id="country" name="country" value="{{$kyc->country}}" required>
                                </div>

                                {{-- Address Proof --}}
                                <div class="col-md-4">
                                    <label for="address_proof_type" class="form-label">Address Proof Type:</label>
                                    <select class="form-control" id="address_proof_type" name="address_proof_type">
                                        <option value="">Select Proof</option>
                                        <option value="Utility Bill" {{ old('address_proof_type', $kyc->address_proof_type) == 'Utility Bill' ? 'selected' : '' }}>Utility Bill</option>
                                        <option value="Rent Agreement" {{ old('address_proof_type', $kyc->address_proof_type) == 'Rent Agreement' ? 'selected' : '' }}>Rent Agreement</option>
                                        <option value="Bank Statement" {{ old('address_proof_type', $kyc->address_proof_type) == 'Bank Statement' ? 'selected' : '' }}>Bank Statement</option>
                                        <option value="Passport" {{ old('address_proof_type', $kyc->address_proof_type) == 'Passport' ? 'selected' : '' }}>Passport</option>
                                        <option value="Driving License" {{ old('address_proof_type', $kyc->address_proof_type) == 'Driving License' ? 'selected' : '' }}>Driving License</option>
                                        <option value="Voter ID" {{ old('address_proof_type', $kyc->address_proof_type) == 'Voter ID' ? 'selected' : '' }}>Voter ID</option>

                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="address_proof_file" class="form-label">Upload Address Proof:</label>
                                    <input type="file" class="form-control" id="address_proof_file" name="address_proof_file">
                                    @if($kyc->address_proof_file)
                                    <img src="{{ asset('storage/' . $kyc->address_proof_file) }}" width="50px" class="mt-2" />
                                    @endif
                                </div>

                                {{-- Document Type --}}
                                <div class="col-md-4">
                                    <label for="document_type" class="form-label">Document Type:</label>
                                    <input type="text" class="form-control" id="document_type" name="document_type" value="{{$kyc->document_type}}" required>
                                </div>

                                {{-- Front & Back Images --}}
                                <div class="col-md-4">
                                    <label for="frontimg" class="form-label">Front Image:</label>
                                    <input type="file" class="form-control" id="frontimg" name="frontimg" required>
                                    @if($kyc->frontimg)
                                    <img src="{{ asset('storage/' . $kyc->frontimg) }}" width="50px" class="mt-2" />
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label for="backimg" class="form-label">Back Image:</label>
                                    <input type="file" class="form-control" id="backimg" name="backimg" required>
                                    @if($kyc->backimg)
                                    <img src="{{ asset('storage/' . $kyc->backimg) }}" width="50px" class="mt-2" />
                                    @endif
                                </div>

                                {{-- Identity Proof --}}
                                <div class="col-md-4">
                                    <label for="identity_type" class="form-label">Identity Type:</label>
                                    <select class="form-control" id="identity_type" name="identity_type">
                                        <option value="">Select Identity</option>
                                        <option value="Aadhar" {{ old('identity_type', $kyc->identity_type) == 'Aadhar' ? 'selected' : '' }}>Aadhar</option>
                                        <option value="PAN" {{ old('identity_type', $kyc->identity_type) == 'PAN' ? 'selected' : '' }}>PAN</option>
                                        <option value="Passport" {{ old('identity_type', $kyc->identity_type) == 'Passport' ? 'selected' : '' }}>Passport</option>
                                        <option value="VoterID" {{ old('identity_type', $kyc->identity_type) == 'VoterID' ? 'selected' : '' }}>Voter ID</option>
                                        <option value="DrivingLicense" {{ old('identity_type', $kyc->identity_type) == 'DrivingLicense' ? 'selected' : '' }}>Driving License</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="identity_number" class="form-label">Identity Number:</label>
                                    <input type="text" class="form-control" id="identity_number" name="identity_number" value="{{$kyc->identity_number}}">
                                </div>
                                <div class="col-md-4">
                                    <label for="identity_file" class="form-label">Upload Identity File:</label>
                                    <input type="file" class="form-control" id="identity_file" name="identity_file">
                                    @if($kyc->identity_file)
                                    <img src="{{ asset('storage/' . $kyc->identity_file) }}" width="50px" class="mt-2" />
                                    @endif
                                </div>

                                {{-- Identity Status --}}
                                <div class="col-md-4">
                                    <label for="identity_status" class="form-label">Identity Status:</label>
                                    <select class="form-control" id="identity_status" name="identity_status">
                                        <option value="pending" {{ old('identity_status', $kyc->identity_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ old('identity_status', $kyc->identity_status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ old('identity_status', $kyc->identity_status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>

                                {{-- KYC Status --}}
                                <div class="col-md-4">
                                    <label for="status" class="form-label">KYC Status:</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="pending" {{ old('status', $kyc->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ old('status', $kyc->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ old('status', $kyc->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>

                                {{-- KYC Type --}}
                                <div class="col-md-4">
                                    <label for="kyc_type" class="form-label">KYC Type:</label>
                                    <select class="form-control" id="kyc_type" name="kyc_type" required>
                                        <option value="online" {{ old('kyc_type', $kyc->kyc_type) == 'online' ? 'selected' : '' }}>Online</option>
                                        <option value="offline" {{ old('kyc_type', $kyc->kyc_type) == 'offline' ? 'selected' : '' }}>Offline</option>
                                    </select>
                                </div>

                                {{-- Source --}}
                                <div class="col-md-4">
                                    <label for="source" class="form-label">Source:</label>
                                    <select class="form-control" id="source" name="source" required>
                                        <option value="APP" {{ old('source', $kyc->source) == 'APP' ? 'selected' : '' }}>APP</option>
                                        <option value="WEB" {{ old('source', $kyc->source) == 'WEB' ? 'selected' : '' }}>WEB</option>
                                    </select>
                                </div>

                                {{-- Submit --}}
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-sm btn-primary" id="addKycBtn">
                                        Add KYC Entry
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        $('#kycForm').on('submit', function(e) {
            e.preventDefault();
            $('.text-danger').remove(); // Clear previous error messages

            const $btn = $('#updateKycBtn');
            const originalBtnHtml = $btn.html();

            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...');

            let formData = new FormData(this);
            formData.append('_method', 'PUT'); // Add this for PUT request

            $.ajax({
                url: "{{ route('admin.kycs.update', $kyc->id) }}",
                method: "POST", // Use POST because FormData and PUT don't mix well directly
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $btn.prop('disabled', false).html(originalBtnHtml);
                    if (response.success) {
                        toastr.success(response.message || 'KYC entry updated successfully!');
                        window.location.href = "{{ route('admin.kycs.index') }}";
                    } else {
                        toastr.error(response.message || 'Failed to update KYC entry.');
                    }
                },
                error: function(xhr) {
                    $btn.prop('disabled', false).html(originalBtnHtml);
                    if (xhr.status === 422) { // Validation errors
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, messages) {
                            let input = $('[name="' + key + '"]');
                            if (input.length) {
                                input.closest('.col-xl-4, .col-lg-6, .col-md-6, .col-sm-12').append('<span class="text-danger validation-error-message">' + messages[0] + '</span>');
                                input.on('input', function() {
                                    $(this).closest('.col-xl-4, .col-lg-6, .col-md-6, .col-sm-12').find('.validation-error-message').remove();
                                });
                            }
                        });
                    } else {
                        toastr.error(xhr.responseJSON.message || 'An unexpected error occurred. Please try again.');
                    }
                }
            });
        });
    });
</script>

@endsection