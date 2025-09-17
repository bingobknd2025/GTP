@extends('admin.layouts.app')

@section('content')
<style>
    .news-container, .padding-space, .single-pincode-form {
        display: block !important;
        opacity: 1 !important;
        visibility: visible !important;
        margin-left: 80px;
    }

    @media (max-width: 768px) {
        .news-container {
            margin-left: 0 !important;
        }
    }
</style>
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="news-container sticky news-ticker">
    <div class="padding-space pb-0">
        <div class="single-pincode-form">
            <form id="PincodeForm">
                @csrf
                <input type="hidden" name="user_id" value="{{ $userData->id }}">
                <div class="row g-2 align-items-center">
                    <div class="col-12 col-md-4">
                        <input type="text" name="pincode" id="pincode" 
                           class="form-control form-control-sm" 
                           placeholder="Enter Pincode" 
                           maxlength="6" 
                           pattern="\d{6}" 
                           inputmode="numeric"
                           oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,6)">

                        <small class="text-danger d-block mt-1" id="pincode_error"></small>
                    </div>
                    <div class="col-12 col-md-2">
                        <button type="submit" class="btn btn-sm btn-primary w-100">Add Pincode</button>
                    </div>
                </div>
            </form>
            <div id="success-message" class="alert alert-success mt-2 d-none"></div>
        </div>
    </div>
</div>

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <div class="ms-md-1 mb-1 mb-md-0 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item active" aria-current="page">Pincode</li>
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Pincode Data</a></li>
                    </ol>
                </nav>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif


        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            Pincode List
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="responsiveDataTable" class="table table-bordered text-nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Pincode</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Pincode Modal -->
<div class="modal fade" id="editPincodeModal" tabindex="-1" aria-labelledby="editPincodeLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="updatePincodeForm">
            @csrf
            <input type="hidden" name="id" id="editPincodeId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPincodeLabel">Edit Pincode</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editPincodeName" class="form-label">Pincode</label>
                        <input type="text" class="form-control" name="pincode" id="editPincodeName" required>
                        <small class="text-danger d-block mt-1 invisible" id="edit_pincode_error"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Optional, for confirmation dialog -->
<script>
$(document).on('click', '.delete-btn', function () {
    var id = $(this).data('id');

    Swal.fire({
        title: 'Are you sure?',
        text: "You want to delete this pincode?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ url('admin/users/pincode/delete') }}/" + id,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire('Deleted!', response.message, 'success');
                        $('#responsiveDataTable').DataTable().ajax.reload(null, false);
                    }
                },
                error: function () {
                    Swal.fire('Failed!', 'Something went wrong.', 'error');
                }
            });
        }
    });
});
</script>

<script>
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

</script>
<script>
$(document).ready(function () {
    var table = $('#responsiveDataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('users.pincode_list', $userData->id) }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'pincode', name: 'pincode' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    $('#PincodeForm').on('submit', function (e) {
        e.preventDefault();
        $('#pincode_error').text('');
        $('#success-message').addClass('d-none').text('');

        $.ajax({
            url: "{{ route('users.pincode_store') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function (response) {
                $('#success-message').removeClass('d-none').text(response.message);
                $('#PincodeForm')[0].reset();
                table.ajax.reload(null, false);
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    if (errors.pincode) {
                        $('#pincode_error').text(errors.pincode[0]);
                    }
                }
            }
        });
    });

    $(document).on('click', '.edit-btn', function () {
        let id = $(this).data('id');
        let name = $(this).data('name');

        $('#editPincodeId').val(id);
        $('#editPincodeName').val(name);
        $('#editPincodeModal').modal('show');
    });

    let brandUpdateRoute = "{{ route('users.pincode_update', ['id' => '__ID__']) }}";

    $('#updatePincodeForm').submit(function (e) {
        e.preventDefault();
        let id = $('#editPincodeId').val();
        let BrandName = $('#editPincodeName').val();
        let updateUrl = brandUpdateRoute.replace('__ID__', id);

        $.ajax({
            url: updateUrl,
            type: 'POST',
            data: {
                _token: $('input[name="_token"]').val(),
                pincode: BrandName
            },
            success: function (res) {
                $('#editPincodeModal').modal('hide');
                alert('Pincode updated successfully.');
                table.ajax.reload(null, false);
            },
            error: function () {
                alert('Update failed.');
            }
        });
    });
});
</script>
@endsection
