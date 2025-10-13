@extends('admin.layouts.app')

@section('content')

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between d-flex align-items-center">
                        <div class="card-title">Add Permission</div>
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-sm btn-secondary">Back to Permissions</a>
                    </div>

                    <div class="card-body">
                        <form id="PermissionForm">
                            @csrf
                            <div class="row gy-4">

                                <!-- Permission Name -->
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label class="form-label">Permission Name:</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter permission name">
                                </div>

                                <!-- Guard Name -->
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label class="form-label">Guard Name:</label>
                                    <select class="form-control" id="guard_name" name="guard_name">
                                        <option value="web">Web</option>
                                        <option value="api">API</option>
                                    </select>
                                </div>

                                <!-- Group Name -->
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label class="form-label">Permission Group:</label>
                                    <input type="text" class="form-control" id="group_name" name="group_name" placeholder="Enter group name (e.g. User, Role, Report)">
                                </div>

                                <!-- Submit -->
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-sm btn-primary">Add Permission</button>
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

            $.ajax({
                url: "{{ route('admin.permissions.store') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    alert(response.message || 'Permission added successfully!');
                    $('#PermissionForm')[0].reset();
                    window.location.href = permissionsIndexUrl;
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, messages) {
                            let input = $('[name="' + key + '"]');
                            if (input.length) {
                                input.after('<span class="text-danger">' + messages[0] + '</span>');
                            }
                        });
                    } else {
                        alert('An error occurred. Please try again.');
                    }
                }
            });
        });
    });
</script>

@endsection