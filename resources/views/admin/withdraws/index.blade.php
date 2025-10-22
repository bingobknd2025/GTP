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
                  <li class="breadcrumb-item active" aria-current="page">Withdraw</li>
                  <li class="breadcrumb-item"><a href="javascript:void(0);">Withdraw Data</a></li>
               </ol>
            </nav>
         </div>
         <div class="page-title fw-semibold fs-18 mb-0">
            <div>
               @can('Withdraw Add')
               <a href="{{ route('admin.withdraws.create') }}" class="btn bg-warning-transparent text-warning btn-sm"
                  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Add New">
                  <span>
                     <i class="fa fa-plus"></i>
                  </span>
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
                              <th>ID</th>
                              <th>Txn ID</th>
                              <th>User</th>
                              <th>Amount</th>
                              <th>Charges</th>
                              <th>To Deduct</th>
                              <th>Payment Mode</th>
                              <th>Pay Details</th>
                              <th>Comment</th>
                              <th>Reference No</th>
                              <th>Source</th>
                              <th>Created At</th>
                              <th>Updated At</th>
                              <th>Status</th>
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
      closeButton: true,
      progressBar: true,
      positionClass: "toast-top-right",
      timeOut: 4000
   };

   $(document).ready(function() {
      var table = $('#responsiveDataTable').DataTable({
         processing: true,
         serverSide: true,
         responsive: true,
         ajax: "{{ route('admin.withdraws.index') }}",
         columns: [{
               data: 'id',
               name: 'id'
            },
            {
               data: 'txn_id',
               name: 'txn_id'
            },
            {
               data: 'customer',
               name: 'customer'
            },
            {
               data: 'amount',
               name: 'amount'
            },
            {
               data: 'charges',
               name: 'charges'
            },
            {
               data: 'to_deduct',
               name: 'to_deduct'
            },
            {
               data: 'payment_mode',
               name: 'payment_mode'
            },
            {
               data: 'paydetails',
               name: 'paydetails'
            },
            {
               data: 'comment',
               name: 'comment'
            },
            {
               data: 'reference_number',
               name: 'reference_number'
            },
            {
               data: 'source',
               name: 'source'
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
               data: 'status',
               name: 'status',
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

      // 🗑️ Delete Action
      $('#responsiveDataTable').on('submit', '.delete-withdraw-form', function(e) {
         e.preventDefault();
         let form = $(this);
         if (confirm('Are you sure to delete this withdrawal entry?')) {
            $.ajax({
               url: form.attr('action'),
               type: 'POST',
               data: form.serialize(),
               success: function(response) {
                  toastr.success(response.message || 'Deleted successfully!');
                  table.ajax.reload();
               },
               error: function(xhr) {
                  toastr.error(xhr.responseJSON?.message || 'Something went wrong.');
               }
            });
         }
      });

      // 🔄 Change Status
      $('#responsiveDataTable').on('click', '.change-status', function(e) {
         e.preventDefault();
         let id = $(this).data('id');
         let status = $(this).data('status');

         if (confirm(`Are you sure you want to change status to "${status}"?`)) {
            $.ajax({
               url: `/admin/withdraws/${id}/update-status`,
               type: 'POST',
               data: {
                  status: status,
                  _token: '{{ csrf_token() }}'
               },
               success: function(response) {
                  if (response.success) {
                     toastr.success(response.message);
                     table.ajax.reload(null, false);
                  } else {
                     toastr.error(response.message);
                  }
               },
               error: function(xhr) {
                  toastr.error(xhr.responseJSON?.message || 'Error occurred.');
               }
            });
         }
      });
   });
</script>


@endsection