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
                  <li class="breadcrumb-item active" aria-current="page">Orders</li>
                  <li class="breadcrumb-item"><a href="javascript:void(0);">Order Data</a></li>
               </ol>
            </nav>
         </div>
         <div class="page-title fw-semibold fs-18 mb-0">
            <div>
               @can('Order Add')
               <a href="{{ route('admin.orders.create') }}" class="btn bg-warning-transparent text-warning btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Add New">
                  <i class="fa fa-plus"></i>
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
                     <table id="ordersDataTable" class="table table-bordered text-nowrap w-100">
                        <thead>
                           <tr>
                              <th>ID</th>
                              <th>Order No</th>
                              <th>Customer ID</th>
                              <th>Franchise ID</th>
                              <th>Purity</th>
                              <th>Before Weight</th>
                              <th>After Weight</th>
                              <th>Unit Price</th>
                              <th>Total Price</th>
                              <th>Amount Paid</th>
                              <th>Invoice</th>
                              <th>Status</th>
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
      var table = $('#ordersDataTable').DataTable({
         processing: true,
         serverSide: true,
         responsive: true,
         ajax: "{{ route('admin.orders.index') }}",
         columns: [{
               data: 'id',
               name: 'id'
            },
            {
               data: 'order_no',
               name: 'order_no'
            },
            {
               data: 'customer_id',
               name: 'customer_id'
            },
            {
               data: 'franchise_id',
               name: 'franchise_id'
            },
            {
               data: 'purity',
               name: 'purity'
            },
            {
               data: 'before_melting_weight',
               name: 'before_melting_weight'
            },
            {
               data: 'after_melting_weight',
               name: 'after_melting_weight'
            },
            {
               data: 'unite_price',
               name: 'unite_price'
            },
            {
               data: 'total_price',
               name: 'total_price'
            },
            {
               data: 'amount_paid',
               name: 'amount_paid'
            },
            {
               data: 'invoice',
               name: 'invoice'
            },
            {
               data: 'status',
               name: 'status',
               orderable: false,
               searchable: false
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

      // Delete Action
      $('#ordersDataTable').on('submit', '.delete-order-form', function(e) {
         e.preventDefault();
         let form = $(this),
            url = form.attr('action');
         if (confirm('Are you sure to delete this order?')) {
            $.ajax({
               url: url,
               type: 'POST',
               data: form.serialize(),
               success: function(response) {
                  toastr.success(response.message || 'Order deleted successfully!');
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