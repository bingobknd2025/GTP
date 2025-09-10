@extends('admin.layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between d-flex align-items-center">
                        <div class="card-title">Change Password</div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.change-password.store') }}">
                            @csrf
                            <div class="row justify-content-center">
                                <div class="col-xl-6 col-lg-8 col-md-10 col-sm-12">
                                    <div class="row gy-4">
                                        <div class="col-12">
                                    <label for="current_password" class="form-label">Current Password:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ri-lock-password-line"></i></span>
                                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                                    </div>
                                    @error('current_password')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="password" class="form-label">New Password:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ri-lock-line"></i></span>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                    @error('password')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="password_confirmation" class="form-label">Confirm New Password:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ri-lock-line"></i></span>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary" id="changePasswordBtn">Change Password</button>
                                </div>
                                    </div>
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
        $('#changePasswordBtn').on('click', function(e) {
            e.preventDefault();
            $('.text-danger').remove(); // Clear previous error messages

            const $btn = $(this);
            const originalBtnHtml = $btn.html();

            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...');

            let formData = new FormData($('form')[0]);

            $.ajax({
                url: $('form').attr('action'),
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $btn.prop('disabled', false).html(originalBtnHtml);
                    if (response.success) {
                        toastr.success(response.message || 'Password changed successfully!');
                        $('form')[0].reset(); // Clear form fields
                    } else {
                        toastr.error(response.message || 'Failed to change password.');
                    }
                },
                error: function(xhr) {
                    $btn.prop('disabled', false).html(originalBtnHtml);
                    if (xhr.status === 422) { // Validation errors
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, messages) {
                            let input = $('[name="' + key + '"]');
                            if (input.length) {
                                input.closest('.col-12').append('<div class="text-danger mt-2">' + messages[0] + '</div>');
                            }
                        });
                    } else {
                        toastr.error(xhr.responseJSON.message || 'An error occurred. Please try again.');
                    }
                }
            });
        });
    });
</script>
@endsection
