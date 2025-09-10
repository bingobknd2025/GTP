@extends('admin.layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between d-flex align-items-center">
                        <div class="card-title">Add Customer</div>
                        <a href="{{ route('admin.customers.index') }}" class="btn btn-sm btn-secondary">Back to Customers</a>
                    </div>
                    <div class="card-body">
                        <form id="customerForm" enctype="multipart/form-data">
                            @csrf
                            <div class="row gy-4">

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="franchise_id" class="form-label">Franchise:</label>
                                    <select class="form-control" id="franchise_id" name="franchise_id">
                                        <option value="">Select Franchise</option>
                                        @foreach($franchises as $franchise)
                                            <option value="{{ $franchise->id }}">{{ $franchise->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="fname" class="form-label">First Name:</label>
                                    <input type="text" class="form-control" id="fname" name="fname">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="lname" class="form-label">Last Name:</label>
                                    <input type="text" class="form-control" id="lname" name="lname">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="email" class="form-label">Email:</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="mobile_no" class="form-label">Mobile No.:</label>
                                    <input type="text" class="form-control" id="mobile_no" name="mobile_no">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="password" class="form-label">Password:</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="account_balance" class="form-label">Account Balance:</label>
                                    <input type="text" class="form-control" id="account_balance" name="account_balance">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="account_name" class="form-label">Account Name:</label>
                                    <input type="text" class="form-control" id="account_name" name="account_name">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="account_type" class="form-label">Account Type:</label>
                                    <input type="text" class="form-control" id="account_type" name="account_type">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="account_number" class="form-label">Account Number:</label>
                                    <input type="text" class="form-control" id="account_number" name="account_number">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="account_bank" class="form-label">Account Bank:</label>
                                    <input type="text" class="form-control" id="account_bank" name="account_bank">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="status" class="form-label">Status:</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="email_verfied" class="form-label">Email Verified:</label>
                                    <select class="form-control" id="email_verfied" name="email_verfied">
                                        <option value="1">Verified</option>
                                        <option value="0" selected>Unverified</option>
                                    </select>
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="mobile_verfied" class="form-label">Mobile Verified:</label>
                                    <select class="form-control" id="mobile_verfied" name="mobile_verfied">
                                        <option value="1">Verified</option>
                                        <option value="0" selected>Unverified</option>
                                    </select>
                                </div>

                                <div class="col-12 d-flex justify-content-end">
                                    @can('Customer Add')
                                    <button type="submit" class="btn btn-sm btn-primary" id="addCustomerBtn">
                                        Add Customer
                                    </button>
                                    @endcan
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
        $('#customerForm').on('submit', function(e) {
            e.preventDefault();
            $('.text-danger').remove(); // Clear previous error messages

            const $btn = $('#addCustomerBtn');
            const originalBtnHtml = $btn.html();

            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...');

            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('admin.customers.store') }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $btn.prop('disabled', false).html(originalBtnHtml);
                    if (response.success) {
                        toastr.success(response.message || 'Customer added successfully!');
                        $('#customerForm')[0].reset(); // Clear form fields
                        window.location.href = "{{ route('admin.customers.index') }}";
                    } else {
                        toastr.error(response.message || 'Failed to add customer.');
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
