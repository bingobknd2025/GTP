@extends('admin.layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between d-flex align-items-center">
                        <div class="card-title">Add KYC Entry</div>
                        <a href="{{ route('admin.kycs.index') }}" class="btn btn-sm btn-secondary">Back to KYC List</a>
                    </div>
                    <div class="card-body">
                        <form id="kycForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row gy-4">

                                {{-- Customer --}}
                                <div class="col-md-4">
                                    <label for="customer_id" class="form-label">Customer:</label>
                                    <select class="form-control" id="customer_id" name="customer_id" required>
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->fname }} {{ $customer->lname }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- First & Last Name --}}
                                <div class="col-md-4">
                                    <label for="first_name" class="form-label">First Name:</label>
                                    <input type="text" class="form-control" id="first_name" value="" name="first_name" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="last_name" class="form-label">Last Name:</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                                </div>

                                {{-- Email --}}
                                <div class="col-md-4">
                                    <label for="email" class="form-label">Email:</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>

                                {{-- Country Code & Phone --}}
                                <div class="col-md-4">
                                    <label for="country_code" class="form-label">Country Code:</label>
                                    <input type="text" class="form-control" id="country_code" name="country_code" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="phone_number" class="form-label">Phone Number:</label>
                                    <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                                </div>

                                {{-- DOB --}}
                                <div class="col-md-4">
                                    <label for="dob" class="form-label">Date of Birth:</label>
                                    <input type="date" class="form-control" id="dob" name="dob" required>
                                </div>

                                {{-- Social Media --}}
                                <div class="col-md-4">
                                    <label for="social_media" class="form-label">Social Media Link:</label>
                                    <input type="url" class="form-control" id="social_media" name="social_media" placeholder="https://facebook.com/username" required>
                                </div>

                                {{-- Address --}}
                                <div class="col-md-4">
                                    <label for="address" class="form-label">Address:</label>
                                    <textarea class="form-control" id="address" name="address" rows="2" required></textarea>
                                </div>

                                {{-- City / State / Country --}}
                                <div class="col-md-4">
                                    <label for="city" class="form-label">City:</label>
                                    <input type="text" class="form-control" id="city" name="city" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="state" class="form-label">State:</label>
                                    <input type="text" class="form-control" id="state" name="state" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="country" class="form-label">Country:</label>
                                    <input type="text" class="form-control" id="country" name="country" required>
                                </div>

                                {{-- Address Proof --}}
                                <div class="col-md-4">
                                    <label for="address_proof_type" class="form-label">Address Proof Type:</label>
                                    <select class="form-control" id="address_proof_type" name="address_proof_type">
                                        <option value="">Select Proof</option>
                                        <option value="Utility Bill">Utility Bill</option>
                                        <option value="Rent Agreement">Rent Agreement</option>
                                        <option value="Bank Statement">Bank Statement</option>
                                        <option value="Passport">Passport</option>
                                        <option value="Driving License">Driving License</option>
                                        <option value="Voter ID">Voter ID</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="address_proof_file" class="form-label">Upload Address Proof:</label>
                                    <input type="file" class="form-control" id="address_proof_file" name="address_proof_file">
                                </div>

                                {{-- Document Type --}}
                                <div class="col-md-4">
                                    <label for="document_type" class="form-label">Document Type:</label>
                                    <input type="text" class="form-control" id="document_type" name="document_type" required>
                                </div>

                                {{-- Front & Back Images --}}
                                <div class="col-md-4">
                                    <label for="frontimg" class="form-label">Front Image:</label>
                                    <input type="file" class="form-control" id="frontimg" name="frontimg" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="backimg" class="form-label">Back Image:</label>
                                    <input type="file" class="form-control" id="backimg" name="backimg" required>
                                </div>

                                {{-- Identity Proof --}}
                                <div class="col-md-4">
                                    <label for="identity_type" class="form-label">Identity Type:</label>
                                    <select class="form-control" id="identity_type" name="identity_type">
                                        <option value="">Select Identity</option>
                                        <option value="Aadhar">Aadhar</option>
                                        <option value="PAN">PAN</option>
                                        <option value="Passport">Passport</option>
                                        <option value="VoterID">Voter ID</option>
                                        <option value="DrivingLicense">Driving License</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="identity_number" class="form-label">Identity Number:</label>
                                    <input type="text" class="form-control" id="identity_number" name="identity_number">
                                </div>
                                <div class="col-md-4">
                                    <label for="identity_file" class="form-label">Upload Identity File:</label>
                                    <input type="file" class="form-control" id="identity_file" name="identity_file">
                                </div>

                                {{-- Identity Status --}}
                                <div class="col-md-4">
                                    <label for="identity_status" class="form-label">Identity Status:</label>
                                    <select class="form-control" id="identity_status" name="identity_status">
                                        <option value="pending" selected>Pending</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </div>

                                {{-- Status --}}
                                <div class="col-md-4">
                                    <label for="status" class="form-label">KYC Status:</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="pending" selected>Pending</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </div>

                                {{-- KYC Type --}}
                                <div class="col-md-4">
                                    <label for="kyc_type" class="form-label">KYC Type:</label>
                                    <select class="form-control" id="kyc_type" name="kyc_type" required>
                                        <option value="online">Online</option>
                                        <option value="offline">Offline</option>
                                    </select>
                                </div>

                                {{-- Source --}}
                                <div class="col-md-4">
                                    <label for="source" class="form-label">Source:</label>
                                    <select class="form-control" id="source" name="source" required>
                                        <option value="APP">APP</option>
                                        <option value="WEB">WEB</option>
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

            const $btn = $('#addKycBtn');
            const originalBtnHtml = $btn.html();

            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...');

            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('admin.kycs.store') }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $btn.prop('disabled', false).html(originalBtnHtml);
                    if (response.success) {
                        toastr.success(response.message || 'KYC entry added successfully!');
                        $('#kycForm')[0].reset(); // Clear form fields
                        window.location.href = "{{ route('admin.kycs.index') }}";
                    } else {
                        toastr.error(response.message || 'Failed to add KYC entry.');
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