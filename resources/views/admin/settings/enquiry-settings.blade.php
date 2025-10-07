@extends('admin.layouts.app')

@section('content')

<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<div class="main-content app-content">
   <div class="container-fluid">
      <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
         <div class="ms-md-1 mb-1 mb-md-0 ms-0">
            <nav>
               <ol class="breadcrumb mb-0">
                  <li class="breadcrumb-item active" aria-current="page">Enquiry</li>
                  <li class="breadcrumb-item"><a href="javascript:void(0);">Enquiry Data</a></li>
               </ol>
            </nav>
         </div>
         <div class="page-title fw-semibold fs-18 mb-0">
            <div>
               <a href="#" class="btn bg-warning-transparent text-warning btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Add New">
                  <span><i class="fa fa-plus"></i></span>
               </a>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-xl-12">
            <div class="card custom-card">
               <div class="card-body">
                  <div class="table-responsive">
                     <table id="responsiveDataTable" class="table table-bordered text-nowrap w-100">
                        <thead>
                           <tr>
                              <th>Enquiry ID</th>
                              <th>Name</th>
                              <th>Email</th>
                              <th>Phone Number</th>
                              <th>Subject</th>
                              <th>Message</th>
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
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });

   $(document).ready(function() {
      var table = $('#responsiveDataTable').DataTable({
         processing: true,
         serverSide: true,
         responsive: true,
         ajax: "{{ route('admin.enquiries.index') }}",
         columns: [{
               data: 'id',
               name: 'id'
            },
            {
               data: 'name',
               name: 'name',
               orderable: false,
               searchable: false
            },
            {
               data: 'email',
               name: 'email'
            },
            {
               data: 'phone',
               name: 'phone'
            },
            {
               data: 'subject',
               name: 'subject',
               orderable: false,
               searchable: false
            },
            {
               data: 'message',
               name: 'message',
               orderable: false,
               searchable: false
            },
            {
               data: 'action',
               name: 'action',
               orderable: false,
               searchable: false
            }
         ]

      });

      // Handle delete action via AJAX
      $('#responsiveDataTable').on('submit', '.delete-enquiry-form', function(e) {
         e.preventDefault();
         let form = $(this);
         let url = form.attr('action');

         if (confirm('Are you sure to delete this enquiry entry?')) {
            $.ajax({
               url: url,
               type: 'POST',
               data: form.serialize(),
               success: function(response) {
                  toastr.success(response.message || 'Enquiry entry deleted successfully!');
                  table.ajax.reload(null, false);
               },
               error: function(xhr) {
                  toastr.error(xhr.responseJSON.message || 'An error occurred. Please try again.');
               }
            });
         }
      });

   });
</script>

@endsection