@extends('admin.layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link href="https://unpkg.com/filepond/dist/filepond.min.css" rel="stylesheet">
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css" rel="stylesheet">


<div class="main-content app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between d-flex align-items-center">
                        <div class="card-title">Add Order</div>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-secondary">Back to Order List</a>
                    </div>
                    <div class="card-body">
                        <form id="orderForm" enctype="multipart/form-data">
                            @csrf
                            <div class="row gy-4">
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="customer_id" class="form-label">Customer:</label>
                                    <select class="form-control" id="customer_id" name="customer_id" required>
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->fname }} {{ $customer->lname }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="franchise_id" class="form-label">Franchise:</label>
                                    <select class="form-control" id="franchise_id" name="franchise_id" required>
                                        <option value="">Select Franchise</option>
                                        @foreach($franchises as $franchise)
                                        <option value="{{ $franchise->id }}">{{ $franchise->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="purity" class="form-label">Purity:</label>
                                    <input type="text" class="form-control" id="purity" name="purity">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="before_melting_weight" class="form-label">Before Melting Weight:</label>
                                    <input type="number" class="form-control" id="before_melting_weight" name="before_melting_weight" step="0.0001" min="0" required>
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="after_melting_weight" class="form-label">After Melting Weight:</label>
                                    <input type="number" class="form-control" id="after_melting_weight" name="after_melting_weight" step="0.0001" min="0" required>
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="unite_price" class="form-label">Unit Price:</label>
                                    <input type="number" class="form-control" id="unite_price" name="unite_price" step="0.01" min="0" required>
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="total_price" class="form-label">Total Price:</label>
                                    <input type="number" class="form-control" id="total_price" name="total_price" step="0.01" min="0" required>
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="amount_paid" class="form-label">Amount Paid:</label>
                                    <input type="number" class="form-control" id="amount_paid" name="amount_paid" step="0.01" min="0">
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="status" class="form-label">Status:</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="Created">Created</option>
                                        <option value="Gold_Recived">Gold Received</option>
                                        <option value="Order_Cancelled">Completed</option>
                                        <option value="In_Process">Cancelled</option>
                                        <option value="Payment_Done">Payment Done</option>
                                    </select>
                                </div>


                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="card custom-card">
                                        <div class="card-header">
                                            <div class="card-title">
                                                Before Melting Image
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <input type="file" class="multiple-filepond" id="before_image" name="before_image[]" multiple data-allow-reorder="true" data-max-file-size="3MB" data-max-files="6">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="card custom-card">
                                        <div class="card-header">
                                            <div class="card-title">
                                                After Melting Image
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <input type="file" class="multiple-filepond" id="after_image" name="after_image[]" multiple data-allow-reorder="true" data-max-file-size="3MB" data-max-files="6">
                                        </div>
                                    </div>
                                </div>


                                <div class="col-xl-12">
                                    <label for="order_note" class="form-label">Order Note:</label>
                                    <textarea class="form-control" id="order_note" name="order_note"></textarea>
                                </div>

                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-sm btn-primary" id="addOrderBtn">
                                        Add Order
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
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
<script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>

<script>
    // Register plugin
    FilePond.registerPlugin(FilePondPluginImagePreview);

    // Init Before Image
    FilePond.create(document.querySelector('#before_image'), {
        allowMultiple: true,
        maxFiles: 6,
        acceptedFileTypes: ['image/*'],
        storeAsFile: true
    });

    // Init After Image
    FilePond.create(document.querySelector('#after_image'), {
        allowMultiple: true,
        maxFiles: 6,
        acceptedFileTypes: ['image/*'],
        storeAsFile: true
    });
</script>


<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        $('#orderForm').on('submit', function(e) {
            e.preventDefault();
            $('.text-danger').remove();

            const $btn = $('#addOrderBtn');
            const originalBtnHtml = $btn.html();
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...');

            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('admin.orders.store') }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $btn.prop('disabled', false).html(originalBtnHtml);
                    if (response.success) {
                        toastr.success(response.message || 'Order created successfully!');
                        $('#orderForm')[0].reset();
                        window.location.href = "{{ route('admin.orders.index') }}";
                    } else {
                        toastr.error(response.message || 'Failed to add order.');
                    }
                },
                error: function(xhr) {
                    $btn.prop('disabled', false).html(originalBtnHtml);
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, messages) {
                            let input = $('[name="' + key + '"]');
                            if (input.length) {
                                input.closest('.col-xl-4, .col-lg-6, .col-md-6, .col-sm-12, .col-xl-6')
                                    .append('<span class="text-danger validation-error-message">' + messages[0] + '</span>');
                                input.on('input', function() {
                                    $(this).closest('.col-xl-4, .col-lg-6, .col-md-6, .col-sm-12, .col-xl-6')
                                        .find('.validation-error-message').remove();
                                });
                            }
                        });
                    } else {
                        toastr.error(xhr.responseJSON?.message || 'An unexpected error occurred. Please try again.');
                    }
                }
            });
        });
    });
</script>

@endsection