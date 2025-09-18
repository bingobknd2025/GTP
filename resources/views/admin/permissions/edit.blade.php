@extends('admin.layouts.app')

@section('content')

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between d-flex align-items-center">
                        <div class="card-title">Edit Permission</div>
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-sm btn-secondary">Back to Permissions</a>
                    </div>

                    <div class="card-body">
                        <form id="PermissionForm">
                            @csrf
                            @method('PUT')
                            <div class="row gy-4">
                                <!-- Permission Name -->
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label class="form-label">Permission Name:</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $permission->name }}">
                                </div>

                                <!-- Guard Name -->
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label class="form-label">Guard Name:</label>
                                    <select class="form-control" id="guard_name" name="guard_name">
                                        <option value="web" {{ $permission->guard_name == 'web' ? 'selected' : '' }}>Web</option>
                                        <option value="api" {{ $permission->guard_name == 'api' ? 'selected' : '' }}>API</option>
                                    </select>
                                </div>

                                <!-- Group Name -->
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label class="form-label">Permission Group:</label>
                                    <input type="text" class="form-control" id="group_name" name="group_name" value="{{ $permission->group_name }}">
                                </div>

                                <!-- Submit -->
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" id="updatePermissionBtn" class="btn btn-sm btn-primary">
                                        Update Permission
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

<script>
    const permissionsIndexUrl = "{{ route('admin.permissions.index') }}";

    $(document).ready(function() {
        $('#PermissionForm').on('submit', function(e) {
            e.preventDefault();
            $('.text-danger').remove();

            const $btn = $('#updatePermissionBtn');
            const originalBtnHtml = $btn.html();

            $btn.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...'
            );

            let formData = new FormData(this);
            formData.append('_method', 'PUT');

            $.ajax({
                url: "{{ route('admin.permissions.update', $permission->id) }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $btn.prop('disabled', false).html(originalBtnHtml);
                    if (response.success) {
                        toastr.success(response.message || 'Permission updated successfully!');
                        window.location.href = permissionsIndexUrl;
                    } else {
                        toastr.error(response.message || 'Failed to update permission.');
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