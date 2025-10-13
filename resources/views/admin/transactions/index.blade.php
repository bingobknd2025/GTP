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
               @can('Transaction Add')
               <a href="{{ route('admin.transactions.create') }}" class="btn bg-warning-transparent text-warning btn-sm"
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
                              <th>Reference No</th>
                              <th>Reference ID</th>
                              <th>Customer ID</th>
                              <th>Amount</th>
                              <th>Type</th>
                              <th>Naration</th>
                              <th>Created At</th>
                              <th>Updated At</th>
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
         ajax: "{{ route('admin.transactions.index') }}", // ✅ route name lowercase
         columns: [{
               data: 'id',
               name: 'id'
            },
            {
               data: 'reference_no',
               name: 'reference_no'
            },
            {
               data: 'reference_id',
               name: 'reference_id'
            },
            {
               data: 'customer_id',
               name: 'customer_id'
            },
            {
               data: 'amount',
               name: 'amount'
            },
            {
               data: 'type',
               name: 'type'
            },
            {
               data: 'naration',
               name: 'naration'
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

      // ✅ Delete Action
      $('#responsiveDataTable').on('submit', '.delete-transaction-form', function(e) {
         e.preventDefault();

         let form = $(this);
         let url = form.attr('action');

         if (confirm('Are you sure to delete this transaction?')) {
            $.ajax({
               url: url,
               type: 'POST',
               data: form.serialize(),
               success: function(response) {
                  toastr.success(response.message || 'Transaction deleted successfully!');
                  table.ajax.reload();
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