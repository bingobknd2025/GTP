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
                        <div class="card-title">Edit Order</div>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-secondary">Back to Order List</a>
                    </div>
                    <div class="card-body">
                        <form id="orderForm" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row gy-4">

                                <!-- Customer -->
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="customer_id" class="form-label">Customer:</label>
                                    <select class="form-control" id="customer_id" name="customer_id" required>
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ $order->customer_id == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->fname }} {{ $customer->lname }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Franchise -->
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="franchise_id" class="form-label">Franchise:</label>
                                    <select class="form-control" id="franchise_id" name="franchise_id" required>
                                        <option value="">Select Franchise</option>
                                        @foreach($franchises as $franchise)
                                        <option value="{{ $franchise->id }}" {{ $order->franchise_id == $franchise->id ? 'selected' : '' }}>
                                            {{ $franchise->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Purity -->
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="purity" class="form-label">Purity:</label>
                                    <input type="text" class="form-control" id="purity" name="purity" value="{{ $order->purity }}">
                                </div>

                                <!-- Before Melting Weight -->
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="before_melting_weight" class="form-label">Before Melting Weight:</label>
                                    <input type="number" class="form-control" id="before_melting_weight" name="before_melting_weight"
                                        step="0.0001" min="0" value="{{ $order->before_melting_weight }}" required>
                                </div>

                                <!-- After Melting Weight -->
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="after_melting_weight" class="form-label">After Melting Weight:</label>
                                    <input type="number" class="form-control" id="after_melting_weight" name="after_melting_weight"
                                        step="0.0001" min="0" value="{{ $order->after_melting_weight }}" required>
                                </div>

                                <!-- Unit Price -->
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="unite_price" class="form-label">Unit Price:</label>
                                    <input type="number" class="form-control" id="unite_price" name="unite_price"
                                        step="0.01" min="0" value="{{ $order->unite_price }}" required>
                                </div>

                                <!-- Total Price -->
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="total_price" class="form-label">Total Price:</label>
                                    <input type="number" class="form-control" id="total_price" name="total_price"
                                        step="0.01" min="0" value="{{ $order->total_price }}" required>
                                </div>

                                <!-- Amount Paid -->
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="amount_paid" class="form-label">Amount Paid:</label>
                                    <input type="number" class="form-control" id="amount_paid" name="amount_paid"
                                        step="0.01" min="0" value="{{ $order->amount_paid }}">
                                </div>

                                <!-- Before Images -->
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="card custom-card">
                                        <div class="card-header">
                                            <div class="card-title">Before Melting Image</div>
                                        </div>
                                        <div class="card-body">
                                            <!-- Existing images preview -->
                                            @if($order->before_image)
                                            @php $beforeImgs = json_decode($order->before_image, true); @endphp
                                            <div class="mb-3">
                                                @foreach($beforeImgs as $img)
                                                <img src="{{ asset('storage/' . $img) }}"
                                                    alt="Before Image"
                                                    class="img-fluid mb-2 me-2"
                                                    style="max-width:120px; border:1px solid #ddd; padding:3px;">
                                                @endforeach
                                            </div>
                                            @endif

                                            <!-- New upload -->
                                            <input type="file" class="multiple-filepond" id="before_image" name="before_image[]" multiple>
                                        </div>
                                    </div>
                                </div>

                                <!-- After Images -->
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="card custom-card">
                                        <div class="card-header">
                                            <div class="card-title">After Melting Image</div>
                                        </div>
                                        <div class="card-body">
                                            <!-- Existing images preview -->
                                            @if($order->after_image)
                                            @php $afterImgs = json_decode($order->after_image, true); @endphp
                                            <div class="mb-3">
                                                @foreach($afterImgs as $img)
                                                <img src="{{ asset('storage/' . $img) }}"
                                                    alt="After Image"
                                                    class="img-fluid mb-2 me-2"
                                                    style="max-width:120px; border:1px solid #ddd; padding:3px;">
                                                @endforeach
                                            </div>
                                            @endif

                                            <!-- New upload -->
                                            <input type="file" class="multiple-filepond" id="after_image" name="after_image[]" multiple>
                                        </div>
                                    </div>
                                </div>


                                <!-- Status -->
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <label for="status" class="form-label">Status:</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="Created" {{ $order->status == 'Created' ? 'selected' : '' }}>Created</option>
                                        <option value="Gold_Recived" {{ $order->status == 'Gold_Recived' ? 'selected' : '' }}>Gold Received</option>
                                        <option value="Order_Cancelled" {{ $order->status == 'Order_Cancelled' ? 'selected' : '' }}>Completed</option>
                                        <option value="In_Process" {{ $order->status == 'In_Process' ? 'selected' : '' }}>Cancelled</option>
                                        <option value="Payment_Done" {{ $order->status == 'Payment_Done' ? 'selected' : '' }}>Payment Done</option>
                                    </select>
                                </div>

                                <!-- Order Note -->
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <label for="order_note" class="form-label">Order Note:</label>
                                    <textarea class="form-control" id="order_note" name="order_note">{{ $order->order_note }}</textarea>
                                </div>

                                <!-- Submit -->
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-sm btn-primary" id="updateOrderBtn">
                                        Update Order
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

            const $btn = $('#updateOrderBtn');
            const originalBtnHtml = $btn.html();

            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...');

            let formData = new FormData(this);
            formData.append('_method', 'PUT');

            $.ajax({
                url: "{{ route('admin.orders.update', $order->id) }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $btn.prop('disabled', false).html(originalBtnHtml);
                    if (response.success) {
                        toastr.success(response.message || 'Order updated successfully!');
                        window.location.href = "{{ route('admin.orders.index') }}";
                    } else {
                        toastr.error(response.message || 'Failed to update order.');
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
                        toastr.error(xhr.responseJSON?.message || 'An unexpected error occurred.');
                    }
                }
            });
        });
    });
</script>

@endsection