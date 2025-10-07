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
                  <li class="breadcrumb-item active" aria-current="page">Deposit</li>
                  <li class="breadcrumb-item"><a href="javascript:void(0);">Deposit Data</a></li>
               </ol>
            </nav>
         </div>
         <div class="page-title fw-semibold fs-18 mb-0">
            <div>
               @can('Deposit Add')
               <a href="{{route('admin.deposits.create')}}" class="btn bg-warning-transparent text-warning btn-sm" data-bs-toggle="tooltip" title="" data-bs-placement="bottom" data-bs-original-title="Add New">
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
                              <th><span>Deposit ID</span></th>
                              <th><span>Trnx. ID</span></th>
                              <th><span>Customer Name</span></th>
                              <th><span>Amount</span></th>
                              <th><span>Payment Method</span></th>
                              <th><span>Plan</span></th>
                              <th><span>Reference Number</span></th>
                              <th><span>Source</span></th>
                              <th><span>Created At</span></th>
                              <th><span>Updated At</span></th>
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
      "timeOut": "4000"
   };

   $(document).ready(function() {
      var table = $('#responsiveDataTable').DataTable({
         processing: true,
         serverSide: true,
         responsive: true,
         ajax: "{{ route('admin.deposits.index') }}",
         columns: [{
               data: 'id',
               name: 'id'
            }, // Deposit ID
            {
               data: 'txn_id',
               name: 'txn_id'
            }, // FIXED: matches DB
            {
               data: 'user',
               name: 'user'
            }, // Will show user id
            {
               data: 'amount',
               name: 'amount'
            },
            {
               data: 'payment_mode',
               name: 'payment_mode'
            },
            {
               data: 'plan',
               name: 'plan'
            }, // Will show plan id
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
               render: function(data) {
                  let statusText, statusClass;
                  switch (parseInt(data)) {
                     case 0:
                        statusText = 'Pending';
                        statusClass = 'bg-warning';
                        break;
                     case 1:
                        statusText = 'Approved';
                        statusClass = 'bg-success';
                        break;
                     case 2:
                        statusText = 'Rejected';
                        statusClass = 'bg-danger';
                        break;
                     default:
                        statusText = 'Unknown';
                        statusClass = 'bg-secondary';
                        break;
                  }
                  return '<span class="badge ' + statusClass + '">' + statusText + '</span>';
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

      // Delete action
      $('#responsiveDataTable').on('submit', '.delete-deposit-form', function(e) {
         e.preventDefault();

         let form = $(this);
         let url = form.attr('action');

         if (confirm('Are you sure to delete this deposit entry?')) {
            $.ajax({
               url: url,
               type: 'POST',
               data: form.serialize(),
               success: function(response) {
                  toastr.success(response.message || 'Deposit deleted successfully!');
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