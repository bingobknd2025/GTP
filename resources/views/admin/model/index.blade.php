@extends('admin.layouts.app')

@section('content')
<style>
    .news-container, .padding-space, .single-model-form {
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


<div class="news-container sticky news-ticker">
    <div class="padding-space pb-0">
        <div class="single-model-form">
        @can('Model Add')
            <form id="ModelForm">
                @csrf
                <div class="row g-2 align-items-center">
                    <div class="col-12 col-md-3">
                        <select name="brand_id" id="brand_id" class="form-control form-control-sm">
                            <option value="">Select Model Name</option>
                            @foreach($brands as $brand)
                            <option value="{{$brand->id}}">{{$brand->brand_name}}</option>
                            @endforeach
                        </select>
                        <small class="text-danger d-block mt-1" id="brand_id_error"></small>
                    </div>

                    <div class="col-12 col-md-3">
                        <select name="product_category_id" id="product_category_id" class="form-control form-control-sm">
                            <option value="">Select Product Category</option>
                            @foreach($product_categorys as $product_category)
                            <option value="{{$product_category->id}}">{{$product_category->category_name}}</option>
                            @endforeach
                        </select>
                        <small class="text-danger d-block mt-1" id="brand_id_error"></small>
                    </div>

                    <div class="col-12 col-md-3">
                        <input type="text" name="model_name" id="model_name" class="form-control form-control-sm" placeholder="Enter Model Name">
                        <small class="text-danger d-block mt-1" id="model_name_error"></small>
                    </div>

                    <div class="col-12 col-md-2">
                        <button type="submit" class="btn btn-sm btn-primary w-100">Add Model</button>
                    </div>
                </div>
            </form>

            <div id="success-message" class="alert alert-success mt-2 d-none"></div>
        @endcan

        </div>
    </div>
</div>





  
        <div class="main-content app-content">
            <div class="container-fluid">

                <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                    <div class="ms-md-1 mb-1 mb-md-0 ms-0">
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item active" aria-current="page">Model</li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Model Data</a></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="page-title fw-semibold fs-18 mb-0">
                        <div>
                        {{--
                            <a href="javascript:void(0);" class="btn bg-secondary-transparent text-secondary btn-sm" data-bs-toggle="tooltip" title="" data-bs-placement="bottom" data-bs-original-title="Rating">
                                <span>
                                    <i class="fa fa-star"></i>
                                </span>
                            </a>
                            <a href="avascript:void(0);" class="btn bg-primary-transparent text-primary mx-2 btn-sm" data-bs-toggle="tooltip" title="" data-bs-placement="bottom" data-bs-original-title="lock">
                                <span>
                                    <i class="fa fa-lock"></i>
                                </span>
                            </a>
                            <a href="javascript:void(0);" class="btn bg-warning-transparent text-warning btn-sm" data-bs-toggle="tooltip" title="" data-bs-placement="bottom" data-bs-original-title="Add New">
                                <span>
                                    <i class="fa fa-plus"></i>
                                </span>
                            </a>
                        --}}
                        
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12">
                        <div class="card custom-card">
                            <div class="card-header">
                                <div class="card-title">
                                    Model List
                                </div>
                            </div>

                            <div class="card-body">
                                <table id="responsiveDataTable" class="table table-bordered text-nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Sr. No.</th>
                                            <th>Brand Name</th>
                                            <th>Product Category</th>
                                            <th>Model Name</th>
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

<div class="modal fade" id="editModelModal" tabindex="-1" aria-labelledby="editModelLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="updateModelForm">
      @csrf
      <input type="hidden" name="id" id="editModelId">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModelLabel">Edit Model</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label for="editModelName" class="form-label">Brand Name</label>
                <select name="brand_id" id="editBrandId" class="form-control form-control-sm">
    <option value="">Select Brand</option>
    @foreach($brands as $brand)
        <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
    @endforeach
</select>
                <small class="text-danger d-block mt-1 invisible" id="edit_model_name_error"></small>
            </div>

            <div class="mb-3">
                <label for="editModelName" class="form-label">Product Category</label>
                <select name="product_category_id" id="editProductCategoryId" class="form-control form-control-sm">
    <option value="">Select Product Category</option>
    @foreach($product_categorys as $product_category)
        <option value="{{ $product_category->id }}">{{ $product_category->category_name }}</option>
    @endforeach
</select>
                <small class="text-danger d-block mt-1 invisible" id="edit_model_name_error"></small>
            </div>

            <div class="mb-3">
                <label for="editModelName" class="form-label">Model Name</label>
                <input type="text" class="form-control" name="model_name" id="editModelName" required>
                <small class="text-danger d-block mt-1 invisible" id="edit_model_name_error"></small>
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

<script>
   $(document).ready(function() {
    var table = $('#responsiveDataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.model.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'brand_name', name: 'brand_name' },
            { data: 'product_category', name: 'product_category' },
            { data: 'model_name', name: 'model_name' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]

    });

    $('#ModelForm').on('submit', function(e) {
        e.preventDefault();
        $('#model_name_error').text('');
        $('#success-message').addClass('d-none').text('');

        $.ajax({
            url: "{{ route('admin.model.store') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function(response) {
                $('#success-message').removeClass('d-none').text(response.message);
                $('#ModelForm')[0].reset();
                table.ajax.reload(null, false);
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    if (errors.model_name) {
                        $('#model_name_error').text(errors.model_name[0]);
                    }
                }
            }
        });
    });

    $(document).on('click', '.edit-btn', function () {
    let id = $(this).data('id');
    let model_name = $(this).data('model_name');
    let brand_id = $(this).data('brand_id');
    let product_category_id = $(this).data('product_category_id');

    $('#editModelId').val(id);
    $('#editModelName').val(model_name);
    $('#editModelModal select[name="brand_id"]').val(brand_id);
    $('#editModelModal select[name="product_category_id"]').val(product_category_id);

    $('#editModelModal').modal('show');
});


    let modelUpdateRoute = "{{ route('admin.model.update', ['id' => '__ID__']) }}";
    
$('#updateModelForm').submit(function (e) {
    e.preventDefault();

    let id = $('#editModelId').val();
    let updateUrl = modelUpdateRoute.replace('__ID__', id);

    $.ajax({
        url: updateUrl,
        type: 'POST',
        data: {
            _token: $('input[name="_token"]').val(),
            _method: 'POST', // Simulate PUT request
            model_name: $('#editModelName').val(),
            brand_id: $('#editBrandId').val(),
            product_category_id: $('#editProductCategoryId').val()
        },
        success: function (res) {
            $('#editModelModal').modal('hide');
            alert('Model updated successfully.');
            location.reload();
        },
        error: function (err) {
            alert('Update failed.');
            console.log(err.responseJSON);
        }
    });
});



});

</script>




     @endsection