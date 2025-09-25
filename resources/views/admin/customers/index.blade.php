@extends('admin.layouts.app')

@section('content')

<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<div class="main-content app-content">
   <div class="container-fluid">
      <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
         <div class="ms-md-1 mb-1 mb-md-0 ms-0">
            <nav>
               <ol class="breadcrumb mb-0">
                  <li class="breadcrumb-item active" aria-current="page">Customers</li>
                  <li class="breadcrumb-item"><a href="javascript:void(0);">Customer Data</a></li>
               </ol>
            </nav>
         </div>
         <div class="page-title fw-semibold fs-18 mb-0">
            <div>
               @can('Customer Add')
               <a href="{{route('admin.customers.create')}}" class="btn bg-warning-transparent text-warning btn-sm" data-bs-toggle="tooltip" title="Add New">
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
                              <th><span>Customer ID</span></th>
                              <th><span>Customer Name</span></th>
                              <th><span>KYC ID</span></th>
                              <th><span>Franchise Name</span></th>
                              <th><span>Email</span></th>
                              <th><span>Mobile No.</span></th>
                              <th><span>Country</span></th>
                              <th><span>Email Verified</span></th>
                              <th><span>Mobile Verified</span></th>
                              <th><span>KYC Status</span></th>
                              <th><span>Status</span></th>
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
      "timeOut": "5000",
   };

   $(document).ready(function() {
      var table = $('#responsiveDataTable').DataTable({
         processing: true,
         serverSide: true,
         responsive: true,
         ajax: "{{ route('admin.customers.index') }}",
         columns: [{
               data: 'id',
               name: 'id'
            },
            {
               data: 'customer_name',
               name: 'customer_name'
            },
            {
               data: 'kyc_id',
               name: 'kyc_id'
            },
            {
               data: 'franchise_name',
               name: 'franchise_name',
               orderable: false,
               searchable: false
            },
            {
               data: 'email',
               name: 'email'
            },
            {
               data: 'mobile_no',
               name: 'mobile_no'
            },
            {
               data: 'country',
               name: 'country'
            },
            {
               data: 'email_verfied',
               name: 'email_verfied',
               render: function(data) {
                  let verifiedText = data ? 'Verified' : 'Unverified';
                  let verifiedClass = data ? 'btn-success' : 'btn-danger';
                  return '<span class="btn btn-sm ' + verifiedClass + '">' + verifiedText + '</span>';
               }
            },
            {
               data: 'mobile_verfied',
               name: 'mobile_verfied',
               render: function(data) {
                  let verifiedText = data ? 'Verified' : 'Unverified';
                  let verifiedClass = data ? 'btn-success' : 'btn-danger';
                  return '<span class="btn btn-sm ' + verifiedClass + '">' + verifiedText + '</span>';
               }
            },
            {
               data: 'kyc_status',
               name: 'kyc_status',
               orderable: false,
               searchable: false
            },
            {
               data: 'status',
               name: 'status',
            },
            {
               data: 'action',
               name: 'action',
               orderable: false,
               searchable: false
            }
         ]
      });

      // Delete Handler
      $('#responsiveDataTable').on('submit', '.delete-customer-form', function(e) {
         e.preventDefault();

         let form = $(this);
         let url = form.attr('action');

         if (confirm('Are you sure to delete this customer?')) {
            $.ajax({
               url: url,
               type: 'POST',
               data: form.serialize(),
               success: function(response) {
                  if (response.success) {
                     toastr.success(response.message || 'Customer deleted successfully!');
                     table.ajax.reload(null, false);
                  } else {
                     toastr.error(response.message || 'Failed to delete customer.');
                  }
               },
               error: function(xhr) {
                  toastr.error(xhr.responseJSON?.message || 'An error occurred. Please try again.');
               }
            });
         }
      });
   });
</script>

@endsectiony