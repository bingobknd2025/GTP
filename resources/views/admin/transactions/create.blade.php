@extends('admin.layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between d-flex align-items-center">
                        <div class="card-title">Add Transaction</div>
                        <a href="{{ route('admin.transactions.index') }}" class="btn btn-sm btn-secondary">Back to Transaction List</a>
                    </div>
                    <div class="card-body">
                        <form id="transactionForm" enctype="multipart/form-data">
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
                                    <label for="amount" class="form-label">Amount:</label>
                                    <input type="number" class="form-control" id="amount" name="amount" min="1" step="0.01" required>
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="type" class="form-label">Transaction Type:</label>
                                    <select class="form-control" id="type" name="type" required>
                                        <option value="Deposite">Deposite</option>
                                        <option value="Withdrawal">Withdrawal</option>
                                        <option value="Bonous">Bonus</option>
                                        <option value="Referral">Referral</option>
                                    </select>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <label for="naration" class="form-label">Narration:</label>
                                    <textarea class="form-control" id="naration" name="naration"></textarea>
                                </div>

                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-sm btn-primary" id="addTransactionBtn">
                                        Add Transaction
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
        $('#transactionForm').on('submit', function(e) {
            e.preventDefault();
            $('.text-danger').remove();

            const $btn = $('#addTransactionBtn');
            const originalBtnHtml = $btn.html();

            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...');

            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('admin.transactions.store') }}", // ✅ store route
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $btn.prop('disabled', false).html(originalBtnHtml);
                    if (response.success) {
                        toastr.success(response.message || 'Transaction created successfully!');
                        $('#transactionForm')[0].reset();
                        window.location.href = "{{ route('admin.transactions.index') }}"; // ✅ redirect to transaction list
                    } else {
                        toastr.error(response.message || 'Failed to add transaction.');
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
                        toastr.error(xhr.responseJSON.message || 'An unexpected error occurred. Please try again.');
                    }
                }
            });
        });
    });
</script>

@endsection