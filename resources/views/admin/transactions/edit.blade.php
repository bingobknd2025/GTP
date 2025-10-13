@extends('admin.layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between d-flex align-items-center">
                        <div class="card-title">Edit Withdrawal Entry</div>
                        <a href="{{ route('admin.withdraws.index') }}" class="btn btn-sm btn-secondary">Back to Withdrawal List</a>
                    </div>
                    <div class="card-body">
                        <form id="withdrawForm" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row gy-4">

                                <!-- Customer -->
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="user" class="form-label">Customer:</label>
                                    <select class="form-control" id="user" name="user">
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ $withdraw->user == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->fname }} {{ $customer->lname }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Amount -->
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="amount" class="form-label">Amount:</label>
                                    <input type="number" class="form-control" id="amount" name="amount" min="1" step="0.01" value="{{ $withdraw->amount }}">
                                </div>

                                <!-- Payment Mode -->
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="payment_mode" class="form-label">Payment Mode</label>
                                    <select class="form-control" id="payment_mode" name="payment_mode" readonly>
                                        <option value="system" selected>Add By System</option>
                                    </select>
                                </div>

                                <!-- Source -->
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="source" class="form-label">Source:</label>
                                    <select class="form-control" id="source" name="source">
                                        <option value="APP" {{ $withdraw->source == 'APP' ? 'selected' : '' }}>APP</option>
                                        <option value="WEB" {{ $withdraw->source == 'WEB' ? 'selected' : '' }}>WEB</option>
                                    </select>
                                </div>

                                <!-- Status -->
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="status" class="form-label">Status:</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="pending" {{ $withdraw->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ $withdraw->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ $withdraw->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>

                                <!-- Charges -->
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="charges" class="form-label">Charges:</label>
                                    <input type="number" class="form-control" id="charges" name="charges" min="0" step="0.01" value="{{ $withdraw->charges }}">
                                </div>

                                <!-- To Deduct -->
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="to_deduct" class="form-label">To Deduct:</label>
                                    <input type="number" class="form-control" id="to_deduct" name="to_deduct" min="0" step="0.01" value="{{ $withdraw->to_deduct }}">
                                </div>

                                <!-- Payment Details -->
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="paydetails" class="form-label">Payment Details:</label>
                                    <textarea class="form-control" id="paydetails" name="paydetails">{{ $withdraw->paydetails }}</textarea>
                                </div>

                                <!-- Comment -->
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="comment" class="form-label">Comment:</label>
                                    <textarea class="form-control" id="comment" name="comment">{{ $withdraw->comment }}</textarea>
                                </div>

                                <!-- Submit -->
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-sm btn-primary" id="updateWithdrawBtn">
                                        Update Withdrawal Entry
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
        $('#withdrawForm').on('submit', function(e) {
            e.preventDefault();
            $('.text-danger').remove();

            const $btn = $('#updateWithdrawBtn');
            const originalBtnHtml = $btn.html();

            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...');

            let formData = new FormData(this);
            formData.append('_method', 'PUT');

            $.ajax({
                url: "{{ route('admin.withdraws.update', $withdraw->id) }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $btn.prop('disabled', false).html(originalBtnHtml);
                    if (response.success) {
                        toastr.success(response.message || 'Withdrawal updated successfully!');
                        window.location.href = "{{ route('admin.withdraws.index') }}";
                    } else {
                        toastr.error(response.message || 'Failed to update withdrawal.');
                    }
                },
                error: function(xhr) {
                    $btn.prop('disabled', false).html(originalBtnHtml);
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, messages) {
                            let input = $('[name="' + key + '"]');
                            if (input.length) {
                                input.closest('.col-xl-4, .col-lg-6, .col-md-6, .col-sm-12')
                                    .append('<span class="text-danger validation-error-message">' + messages[0] + '</span>');
                                input.on('input', function() {
                                    $(this).closest('.col-xl-4, .col-lg-6, .col-md-6, .col-sm-12')
                                        .find('.validation-error-message').remove();
                                });
                            }
                        });
                    } else {
                        toastr.error(xhr.responseJSON.message || 'An unexpected error occurred.');
                    }
                }
            });
        });
    });
</script>

@endsection