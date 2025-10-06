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
                  <li class="breadcrumb-item active" aria-current="page">KYC</li>
                  <li class="breadcrumb-item"><a href="javascript:void(0);">KYC Data</a></li>
               </ol>
            </nav>
         </div>
         <div class="page-title fw-semibold fs-18 mb-0">
            <div>
               @can('KYC Add')
               <a href="{{route('admin.kycs.create')}}" class="btn bg-warning-transparent text-warning btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Add New">
                  <span><i class="fa fa-plus"></i></span>
               </a>
               @endcan
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
                              <th>KYC ID</th>
                              <th>Customer Name</th>
                              <th>Email</th>
                              <th>Phone Number</th>
                              <th>Identity Type</th>
                              <th>Identity Number</th>
                              <th>Identity Status</th>
                              <th>Mobile Status</th>
                              <th>Residential Address</th>
                              <th>Address Proof</th>
                              <th>Final Status</th>
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
         ajax: "{{ route('admin.kycs.index') }}",
         columns: [{
               data: 'id',
               name: 'id'
            },
            {
               data: 'customer_name',
               name: 'customer_name',
               orderable: false,
               searchable: false
            },
            {
               data: 'email',
               name: 'email'
            },
            {
               data: 'phone_number',
               name: 'phone_number'
            },
            {
               data: 'identity_type',
               name: 'identity_type'
            },
            {
               data: 'identity_number',
               name: 'identity_number'
            },
            {
               data: 'identity_status',
               name: 'identity_status',
               orderable: false,
               searchable: false
            },
            {
               data: 'mobile_status',
               name: 'mobile_status',
               orderable: false,
               searchable: false
            },
            {
               data: 'resi_address_status',
               name: 'resi_address_status',
               orderable: false,
               searchable: false
            },
            {
               data: 'address_veri_status',
               name: 'address_veri_status',
               orderable: false,
               searchable: false
            },
            {
               data: 'final_status',
               name: 'final_status',
               orderable: false,
               searchable: false,
               render: function(data, type, row) {
                  // handle ENUM('true', 'false') values as strings
                  let isApproved = (data === 'true');
                  let statusText = isApproved ? 'Approved' : 'Pending';
                  let statusClass = isApproved ? 'btn-success' : 'btn-warning';

                  return `
                     <div class="dropdown">
                        <button class="btn btn-sm ${statusClass} dropdown-toggle" type="button" id="dropdownFinalStatus${row.id}" data-bs-toggle="dropdown" aria-expanded="false">
                           ${statusText}
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownFinalStatus${row.id}">
                           <li><a class="dropdown-item final-status-toggle" href="#" data-id="${row.id}" data-status="true">Approved</a></li>
                           <li><a class="dropdown-item final-status-toggle" href="#" data-id="${row.id}" data-status="false">Pending</a></li>
                        </ul>
                     </div>
                  `;
               }
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
      $('#responsiveDataTable').on('submit', '.delete-kyc-form', function(e) {
         e.preventDefault();
         let form = $(this);
         let url = form.attr('action');

         if (confirm('Are you sure to delete this KYC entry?')) {
            $.ajax({
               url: url,
               type: 'POST',
               data: form.serialize(),
               success: function(response) {
                  toastr.success(response.message || 'KYC entry deleted successfully!');
                  table.ajax.reload(null, false);
               },
               error: function(xhr) {
                  toastr.error(xhr.responseJSON.message || 'An error occurred. Please try again.');
               }
            });
         }
      });

      $(document).on('click', '.final-status-toggle', function(e) {
         e.preventDefault();

         let id = $(this).data('id');
         let status = $(this).data('status'); // true or false (string)

         $.ajax({
            url: "{{ route('admin.kycs.finalStatus') }}",
            type: 'POST',
            data: {
               _token: $('meta[name="csrf-token"]').attr('content'),
               id: id,
               status: status
            },
            success: function(response) {
               if (response.success) {
                  toastr.success('Final status updated successfully!');
                  $('#kycTable').DataTable().ajax.reload(null, false);
               } else {
                  toastr.error('Something went wrong.');
               }
            },
            error: function() {
               toastr.error('Server error. Please try again.');
            }
         });
      });

   });
</script>

@endsection