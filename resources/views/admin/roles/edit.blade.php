@extends('admin.layouts.app')

@section('content')


<div class="main-content app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                     <div class="card-header justify-content-between d-flex align-items-center">
                         <div class="card-title">Edit Role</div>
                         <a href="{{ route('roles.index') }}" class="btn btn-sm btn-secondary">Back to Roles</a>
                     </div>

                    <div class="card-body">
                            <form id="UserForm" method="POST" action="{{ route('roles.update', $role->id) }}">
                            @csrf
                            <div class="row gy-4">

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label class="form-label">Role Name:</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $role->name }}">
                                </div>

                                <h5>Admin Permissions</h5>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="30%" style="color: darkslateblue;">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="select_all_permissions">
                                                    <label class="form-check-label fw-bold ms-1" for="select_all_permissions">
                                                        Group Name
                                                    </label>
                                                </div>
                                            </th>
                                            <th style="color: darkslateblue;">Permissions</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($permissionGroups as $groupName => $permissions)
                                            @php $groupKey = Str::slug($groupName); @endphp
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input group-checkbox" type="checkbox" data-group="{{ $groupKey }}" id="group_{{ $groupKey }}">
                                                        <label class="form-check-label" for="group_{{ $groupKey }}">
                                                            {{ $groupName }}
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    @foreach($permissions as $permission)
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input permission-checkbox"
                                                                type="checkbox"
                                                                name="permissions[]"
                                                                value="{{ $permission->id }}"
                                                                id="perm_{{ $permission->id }}"
                                                                data-group="{{ $groupKey }}"
                                                                {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                                {{ $permission->name }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>


                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-sm btn-primary">Update Role</button>
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
    const usersIndexUrl = "{{ route('roles.index') }}";
</script>
<script>
$(document).ready(function() {

    $('#UserForm').on('submit', function(e) {
            e.preventDefault();
            $('.text-danger').remove();

            $.ajax({
                url: $(this).attr('action'),
                method: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    alert('Role updated successfully');
                    window.location.href = usersIndexUrl;
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, messages) {
                            const input = $('[name="'+key+'"]');
                            if(input.length) {
                                input.after('<span class="text-danger">'+messages[0]+'</span>');
                            }
                        });
                    } else {
                        alert('Something went wrong, please try again.');
                    }
                }
            });
        });
});
</script>

<script>
    $(document).ready(function () {

        $('.group-checkbox').each(function () {
            const groupKey = $(this).data('group');
            const all = $('.permission-checkbox[data-group="' + groupKey + '"]');
            const checked = all.filter(':checked');
            $(this).prop('checked', all.length === checked.length);
        });

        updateSelectAllCheckbox();

        $('.group-checkbox').on('change', function () {
            const groupKey = $(this).data('group');
            const isChecked = $(this).prop('checked');
            $('.permission-checkbox[data-group="' + groupKey + '"]').prop('checked', isChecked);
            updateSelectAllCheckbox();
        });

        $('.permission-checkbox').on('change', function () {
            const groupKey = $(this).data('group');
            const all = $('.permission-checkbox[data-group="' + groupKey + '"]');
            const checked = all.filter(':checked');
            $('.group-checkbox[data-group="' + groupKey + '"]').prop('checked', all.length === checked.length);
            updateSelectAllCheckbox();
        });

        $('#select_all_permissions').on('change', function () {
            const isChecked = $(this).prop('checked');
            $('.group-checkbox, .permission-checkbox').prop('checked', isChecked);
        });

        function updateSelectAllCheckbox() {
            const totalPermissions = $('.permission-checkbox').length;
            const checkedPermissions = $('.permission-checkbox:checked').length;
            $('#select_all_permissions').prop('checked', totalPermissions === checkedPermissions);
        }
    });
</script>

@endsection
