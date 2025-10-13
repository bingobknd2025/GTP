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
                  <li class="breadcrumb-item active" aria-current="page">Activity</li>
                  <li class="breadcrumb-item"><a href="javascript:void(0);">Admin Activity Data</a></li>
               </ol>
            </nav>
         </div>
         <div class="page-title fw-semibold fs-18 mb-0">
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
                              <th><span>Activity ID</span></th>
                              <th><span>User ID</span></th>
                              <th><span>Type</span></th>
                              <th><span>Details</span></th>
                              <th><span>IP Address</span></th>
                              <th><span>Device</span></th>
                              <th><span>Browser</span></th>
                              <th><span>OS</span></th>
                              <th><span>Created At</span></th>
                              <th><span>Updated At</span></th>
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

   toastr.options = {
      "closeButton": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "timeOut": "4000"
   };

   $(document).ready(function() {
      var table = $('#responsiveDataTable').DataTable({
         processing: true,
         serverSide: true,
         responsive: true,
         ajax: "{{ route('admin.activity.index') }}",
         columns: [{
               data: 'id',
               name: 'id'
            }, // Deposit ID
            {
               data: 'user_id',
               name: 'user_id'
            },
            {
               data: 'type',
               name: 'type'
            },
            {
               data: 'details',
               name: 'details'
            },
            {
               data: 'ip_address',
               name: 'ip_address'
            },
            {
               data: 'device',
               name: 'device'
            },
            {
               data: 'browser',
               name: 'browser'
            },
            {
               data: 'os',
               name: 'os'
            },
            {
               data: 'created_at',
               name: 'created_at'
            },
            {
               data: 'updated_at',
               name: 'updated_at'
            },
            {
               data: 'action',
               name: 'action',
               orderable: false,
               searchable: false
            }
         ]
      });

      // Delete action
      $('#responsiveDataTable').on('submit', '.delete-activity-form', function(e) {
         e.preventDefault();

         let form = $(this);
         let url = form.attr('action');

         if (confirm('Are you sure to delete this activity entry?')) {
            $.ajax({
               url: url,
               type: 'POST',
               data: form.serialize(),
               success: function(response) {
                  toastr.success(response.message || 'Activity deleted successfully!');
                  table.ajax.reload(); // Reload DataTable (better than full page reload)
               },
               error: function(xhr) {
                  toastr.error(xhr.responseJSON?.message || 'Error occurred. Please try again.');
                  console.error('AJAX Error:', xhr.responseText);
               }
            });
         }
      });

   });
</script>


@endsection