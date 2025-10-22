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
               <div class="card-body d-flex justify-content-between align-items-center flex-sm-nowrap flex-wrap">
                  <div class="d-flex flex-row align-items-center">
                     <select class="form-select m-1" id="franchiseFilter">
                        <option value="">All Franchises</option>
                        @foreach($franchises as $franchise)
                        <option value="{{ $franchise->id }}">{{ $franchise->name }}</option>
                        @endforeach
                     </select>

                     <select class="form-select m-1" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Reject">Rejected</option>
                     </select>

                     <select class="form-select m-1" id="kycFilter">
                        <option value="">All KYC Status</option>
                        <option value="Verified">Verified</option>
                        <option value="Not Verified">Not Verified</option>
                        <option value="Rejected">Rejected</option>
                     </select>
                  </div>

                  <div class="d-flex m-1">
                     <button class="btn btn-success me-2" id="exportExcel">
                        <i class="bi bi-file-earmark-excel"></i> Excel
                     </button>
                     <button class="btn btn-info text-white" id="exportCSV">
                        <i class="bi bi-file-earmark-spreadsheet"></i> CSV
                     </button>
                  </div>
               </div>


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
         ajax: {
            url: "{{ route('admin.customers.index') }}",
            data: function(d) {
               d.franchise_id = $('#franchiseFilter').val();
               d.status = $('#statusFilter').val();
               d.kyc_status = $('#kycFilter').val();
            }
         },
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
                  return `<span class="btn btn-sm ${data ? 'btn-success' : 'btn-danger'}">${data ? 'Verified' : 'Unverified'}</span>`;
               }
            },
            {
               data: 'mobile_verfied',
               name: 'mobile_verfied',
               render: function(data) {
                  return `<span class="btn btn-sm ${data ? 'btn-success' : 'btn-danger'}">${data ? 'Verified' : 'Unverified'}</span>`;
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
               name: 'status'
            },
            {
               data: 'action',
               name: 'action',
               orderable: false,
               searchable: false
            }
         ]
      });

      // Trigger table reload on filter change
      $('#franchiseFilter, #statusFilter, #kycFilter').change(function() {
         table.ajax.reload();
      });
   });
</script>

@endsectiony