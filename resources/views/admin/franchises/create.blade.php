@extends('admin.layouts.app')

@section('content')

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                     <div class="card-header justify-content-between d-flex align-items-center">
                         <div class="card-title">Add Franchise</div>
                         <a href="{{ route('admin.franchises.index') }}" class="btn btn-sm btn-secondary">Back to Franchises</a>
                     </div>

                    <div class="card-body">
                        <form id="franchiseForm" enctype="multipart/form-data">
                            @csrf
                            <div class="row gy-4">

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="name" class="form-label">Franchise Name:</label>
                                    <input type="text" class="form-control" id="name" name="name">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="address" class="form-label">Address:</label>
                                    <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="pincode" class="form-label">Pincode:</label>
                                    <input type="text" class="form-control" id="pincode" name="pincode">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="contact_no" class="form-label">Contact No.:</label>
                                    <input type="text" class="form-control" id="contact_no" name="contact_no">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="email" class="form-label">Email:</label>
                                    <input type="email" class="form-control" id="email" name="email" >
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="password" class="form-label">Password:</label>
                                    <input type="password" class="form-control" id="password" name="password" >
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="contact_person_name" class="form-label">Contact Person Name:</label>
                                    <input type="text" class="form-control" id="contact_person_name" name="contact_person_name">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="contact_person_number" class="form-label">Contact Person Number:</label>
                                    <input type="text" class="form-control" id="contact_person_number" name="contact_person_number">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="store_lat" class="form-label">Store Latitude:</label>
                                    <input type="text" class="form-control" id="store_lat" name="store_lat">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="store_long" class="form-label">Store Longitude:</label>
                                    <input type="text" class="form-control" id="store_long" name="store_long">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="status" class="form-label">Status:</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label for="image" class="form-label">Franchise Image:</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                </div>

                                <div class="col-12 d-flex justify-content-end">
                                    @can('Franchise Add')
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        Add Franchise
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

<script>
    const franchisesIndexUrl = "{{ route('admin.franchises.index') }}";
</script>
<script>
$(document).ready(function() {

    $('#franchiseForm').on('submit', function(e) {
        e.preventDefault();
        $('.text-danger').remove();

        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('admin.franchises.store') }}",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert(response.message || 'Franchise added successfully!');
                $('#franchiseForm')[0].reset();
                window.location.href = franchisesIndexUrl;
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, messages) {
                        let input = $('[name="'+key+'"]');
                        if(input.length) {
                            input.closest('.col-xl-4, .col-lg-6, .col-md-6, .col-sm-12').append('<span class="text-danger validation-error-message">' + messages[0] + '</span>');
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
