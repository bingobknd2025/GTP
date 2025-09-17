@extends('admin.layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between d-flex align-items-center">
                        <div class="card-title">Edit Customer</div>
                        <a href="{{ route('admin.customers.index') }}" class="btn btn-sm btn-secondary">Back to Customers</a>
                    </div>
                    <div class="card-body">
                        <form id="customerForm" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row gy-4">

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="franchise_id" class="form-label">Franchise:</label>
                                    <select class="form-control" id="franchise_id" name="franchise_id">
                                        <option value="">Select Franchise</option>
                                        @foreach($franchises as $franchise)
                                            <option value="{{ $franchise->id }}" {{ $customer->franchise_id == $franchise->id ? 'selected' : '' }}>{{ $franchise->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="fname" class="form-label">First Name:</label>
                                    <input type="text" class="form-control" id="fname" name="fname" value="{{ $customer->fname }}">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="lname" class="form-label">Last Name:</label>
                                    <input type="text" class="form-control" id="lname" name="lname" value="{{ $customer->lname }}">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="email" class="form-label">Email:</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $customer->email }}">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="mobile_no" class="form-label">Mobile No.:</label>
                                    <input type="text" class="form-control" id="mobile_no" name="mobile_no" value="{{ $customer->mobile_no }}">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="password" class="form-label">Password (Leave blank to keep current):</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="account_balance" class="form-label">Account Balance:</label>
                                    <input type="text" class="form-control" id="account_balance" name="account_balance" value="{{ $customer->account_balance }}">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="account_name" class="form-label">Account Name:</label>
                                    <input type="text" class="form-control" id="account_name" name="account_name" value="{{ $customer->account_name }}">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="account_type" class="form-label">Account Type:</label>
                                    <input type="text" class="form-control" id="account_type" name="account_type" value="{{ $customer->account_type }}">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="account_number" class="form-label">Account Number:</label>
                                    <input type="text" class="form-control" id="account_number" name="account_number" value="{{ $customer->account_number }}">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="account_bank" class="form-label">Account Bank:</label>
                                    <input type="text" class="form-control" id="account_bank" name="account_bank" value="{{ $customer->account_bank }}">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="status" class="form-label">Status:</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="1" {{ $customer->status == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $customer->status == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="email_verfied" class="form-label">Email Verified:</label>
                                    <select class="form-control" id="email_verfied" name="email_verfied">
                                        <option value="1" {{ $customer->email_verfied == 1 ? 'selected' : '' }}>Verified</option>
                                        <option value="0" {{ $customer->email_verfied == 0 ? 'selected' : '' }}>Unverified</option>
                                    </select>
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="mobile_verfied" class="form-label">Mobile Verified:</label>
                                    <select class="form-control" id="mobile_verfied" name="mobile_verfied">
                                        <option value="1" {{ $customer->mobile_verfied == 1 ? 'selected' : '' }}>Verified</option>
                                        <option value="0" {{ $customer->mobile_verfied == 0 ? 'selected' : '' }}>Unverified</option>
                                    </select>
                                </div>

                                <div class="col-12 d-flex justify-content-end">
                                    @can('Customer Edit')
                                    <button type="submit" class="btn btn-sm btn-primary" id="updateCustomerBtn">
                                        Update Customer
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"></script>
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

            const $btn = $('#updateCustomerBtn');
            const originalBtnHtml = $btn.html();

            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...');

            let formData = new FormData(this);
            formData.append('_method', 'PUT'); // Add this for PUT request

            $.ajax({
                url: "{{ route('admin.customers.update', $customer->id) }}",
                method: "POST", // Use POST because FormData and PUT don't mix well directly
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $btn.prop('disabled', false).html(originalBtnHtml);
                    if (response.success) {
                        toastr.success(response.message || 'Customer updated successfully!');
                        window.location.href = "{{ route('admin.customers.index') }}";
                    } else {
                        toastr.error(response.message || 'Failed to update customer.');
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
