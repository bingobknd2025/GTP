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
<<<<<<< HEAD
                @can('Customer Add')
               <a href="{{route('admin.customers.create')}}" class="btn bg-warning-transparent text-warning btn-sm" data-bs-toggle="tooltip" title="" data-bs-placement="bottom" data-bs-original-title="Add New">
               <span>
               <i class="fa fa-plus"></i>
               </span>
=======
               @can('Customer Add')
               <a href="{{route('admin.customers.create')}}" class="btn bg-warning-transparent text-warning btn-sm" data-bs-toggle="tooltip" title="Add New">
                  <span><i class="fa fa-plus"></i></span>
>>>>>>> master
               </a>
               @endcan
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-xl-12">
            <div class="card custom-card">
<<<<<<< HEAD
=======
               <!-- <div class="card-body d-flex justify-content-between align-items-center flex-sm-nowrap flex-wrap">

                  <div class="d-flex flex-wrap align-items-center">
                     <div class="dropdown m-1">
                        <button class="btn btn-light dropdown-toggle" type="button" id="franchiseFilter" data-bs-toggle="dropdown" aria-expanded="false">
                           Franchise
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="franchiseFilter">
                           <li><a class="dropdown-item" href="javascript:void(0);">All</a></li>
                           <li><a class="dropdown-item" href="javascript:void(0);">Franchise A</a></li>
                           <li><a class="dropdown-item" href="javascript:void(0);">Franchise B</a></li>
                        </ul>
                     </div>

                     <div class="dropdown m-1">
                        <button class="btn btn-light dropdown-toggle" type="button" id="statusFilter" data-bs-toggle="dropdown" aria-expanded="false">
                           Status
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="statusFilter">
                           <li><a class="dropdown-item" href="javascript:void(0);">All</a></li>
                           <li><a class="dropdown-item" href="javascript:void(0);">Pending</a></li>
                           <li><a class="dropdown-item" href="javascript:void(0);">Approved</a></li>
                           <li><a class="dropdown-item" href="javascript:void(0);">Rejected</a></li>
                        </ul>
                     </div>
                     <div class="dropdown m-1">
                        <button class="btn btn-light dropdown-toggle" type="button" id="kycFilter" data-bs-toggle="dropdown" aria-expanded="false">
                           KYC Status
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="kycFilter">
                           <li><a class="dropdown-item" href="javascript:void(0);">All</a></li>
                           <li><a class="dropdown-item" href="javascript:void(0);">Verified</a></li>
                           <li><a class="dropdown-item" href="javascript:void(0);">Not Verified</a></li>
                           <li><a class="dropdown-item" href="javascript:void(0);">Pending</a></li>
                        </ul>
                     </div>
                  </div>

                  <div class="d-flex m-1">
                     <button class="btn btn-success me-2">
                        <i class="bi bi-file-earmark-excel"></i> Excel
                     </button>
                     <button class="btn btn-info text-white">
                        <i class="bi bi-file-earmark-spreadsheet"></i> CSV
                     </button>
                  </div>

               </div> -->
               <div class="card-body d-flex justify-content-between align-items-center flex-sm-nowrap flex-wrap">
                  <!-- Left side: Filters -->
                  <div class="d-flex flex-wrap align-items-center">
                     <!-- Franchise Filter -->
                     <select class="form-select m-1" id="franchiseFilter">
                        <option value="">All Franchises</option>
                        @foreach($franchises as $franchise)
                        <option value="{{ $franchise->id }}">{{ $franchise->name }}</option>
                        @endforeach
                     </select>

                     <!-- Status Filter -->
                     <select class="form-select m-1" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Reject">Rejected</option>
                     </select>

                     <!-- KYC Status Filter -->
                     <select class="form-select m-1" id="kycFilter">
                        <option value="">All KYC Status</option>
                        <option value="Verified">Verified</option>
                        <option value="Not Verified">Not Verified</option>
                        <option value="Rejected">Rejected</option>
                     </select>
                  </div>

                  <!-- Right side: Export Buttons -->
                  <div class="d-flex m-1">
                     <button class="btn btn-success me-2" id="exportExcel">
                        <i class="bi bi-file-earmark-excel"></i> Excel
                     </button>
                     <button class="btn btn-info text-white" id="exportCSV">
                        <i class="bi bi-file-earmark-spreadsheet"></i> CSV
                     </button>
                  </div>
               </div>


>>>>>>> master
               <div class="card-body">
                  <div class="table-responsive">
                     <table id="responsiveDataTable" class="table table-bordered text-nowrap w-100">
                        <thead>
                           <tr>
                              <th><span>Customer ID</span></th>
<<<<<<< HEAD
                              <th><span>KYC ID</span></th>
                              <th><span>Franchise Name</span></th>
                              <th><span>First Name</span></th>
                              <th><span>Last Name</span></th>
                              <th><span>Email</span></th>
                              <th><span>Mobile No.</span></th>
                              <th><span>Status</span></th>
                              <th><span>Email Verified</span></th>
                              <th><span>Mobile Verified</span></th>
=======
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
>>>>>>> master
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
<<<<<<< HEAD
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Configure Toastr options globally
toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut",
    "onShown": function () { console.log('Toastr message shown.'); },
    "onHidden": function () { console.log('Toastr message hidden.'); }
};

$(document).ready(function() {
    var table = $('#responsiveDataTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "{{ route('admin.customers.index') }}",
        columns: [
          { data: 'id', name: 'id' },
          { data: 'kyc_id', name: 'kyc_id' },
          { data: 'franchise_name', name: 'franchise_name', orderable: false, searchable: false }, // From relationship
          { data: 'fname', name: 'fname' },
          { data: 'lname', name: 'lname' },
          { data: 'email', name: 'email' },
          { data: 'mobile_no', name: 'mobile_no' },
          { data: 'status', name: 'status', render: function(data, type, row) {
                let statusText = data ? 'Active' : 'Inactive';
                let statusClass = data ? 'btn-success' : 'btn-danger';
                return '<span class="btn btn-sm ' + statusClass + '">' + statusText + '</span>';
            } },
          { data: 'email_verfied', name: 'email_verfied', render: function(data, type, row) {
                let verifiedText = data ? 'Verified' : 'Unverified';
                let verifiedClass = data ? 'btn-success' : 'btn-danger';
                return '<span class="btn btn-sm ' + verifiedClass + '">' + verifiedText + '</span>';
            } },
          { data: 'mobile_verfied', name: 'mobile_verfied', render: function(data, type, row) {
                let verifiedText = data ? 'Verified' : 'Unverified';
                let verifiedClass = data ? 'btn-success' : 'btn-danger';
                return '<span class="btn btn-sm ' + verifiedClass + '">' + verifiedText + '</span>';
            } },
          { data: 'action', name: 'action', orderable: false, searchable: false, render: function(data, type, row) {
                    let btn = '';
                    let editUrl = "{{ route('admin.customers.edit', 'ID_PLACEHOLDER') }}".replace('ID_PLACEHOLDER', row.id);
                    let deleteUrl = "{{ route('admin.customers.destroy', 'ID_PLACEHOLDER') }}".replace('ID_PLACEHOLDER', row.id);
                    let showUrl = "{{ route('admin.customers.show', 'ID_PLACEHOLDER') }}".replace('ID_PLACEHOLDER', row.id);

                    @can('Customer Edit')
                    btn += '<a href="' + editUrl + '" class="btn btn-sm btn-primary me-1" title="Edit"><i class="fas fa-edit"></i></a>';
                    @endcan

                    @can('Customer Delete')
                    btn += '<form action="' + deleteUrl + '" method="POST" class="delete-customer-form" style="display:inline;">' +
                           '{{ csrf_field() }}' +
                           '{{ method_field('DELETE') }}' +
                           '<button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash-alt"></i></button>' +
                           '</form>';
                    @endcan

                    @can('Customer View')
                    btn += '<a href="' + showUrl + '" class="btn btn-sm btn-info me-1" title="View"><i class="fas fa-eye"></i></a>';
                    @endcan
                    
                    return btn;
                } }
          ]
        });

    $('#responsiveDataTable').on('submit', '.delete-customer-form', function(e) {
        e.preventDefault();

        let form = $(this);
        let url = form.attr('action');

        if (confirm('Are you sure to delete this customer?')) {
            $.ajax({
                url: url,
                type: 'POST',
                data: form.serialize(), // Send form data including _token and _method
                success: function(response) {
                    console.log('Delete successful:', response);
                    if (response.success) {
                        console.log('Attempting to display Toastr success message...');
                        toastr.success(response.message || 'Customer deleted successfully!');
                        console.log('Initiating full page reload...');
                        window.location.reload(); // Reload the entire page immediately
                    } else {
                        toastr.error(response.message || 'Failed to delete customer.');
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON.message || 'An error occurred. Please try again.');
                    console.error('AJAX Error:', xhr.responseText);
                }
            });
        }
    });

});

</script>

@endsection
=======
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

   // $(document).ready(function() {
   //    var table = $('#responsiveDataTable').DataTable({
   //       processing: true,
   //       serverSide: true,
   //       responsive: true,
   //       ajax: "{{ route('admin.customers.index') }}",
   //       columns: [{
   //             data: 'id',
   //             name: 'id'
   //          },
   //          {
   //             data: 'customer_name',
   //             name: 'customer_name'
   //          },
   //          {
   //             data: 'kyc_id',
   //             name: 'kyc_id'
   //          },
   //          {
   //             data: 'franchise_name',
   //             name: 'franchise_name',
   //             orderable: false,
   //             searchable: false
   //          },
   //          {
   //             data: 'email',
   //             name: 'email'
   //          },
   //          {
   //             data: 'mobile_no',
   //             name: 'mobile_no'
   //          },
   //          {
   //             data: 'country',
   //             name: 'country'
   //          },
   //          {
   //             data: 'email_verfied',
   //             name: 'email_verfied',
   //             render: function(data) {
   //                let verifiedText = data ? 'Verified' : 'Unverified';
   //                let verifiedClass = data ? 'btn-success' : 'btn-danger';
   //                return '<span class="btn btn-sm ' + verifiedClass + '">' + verifiedText + '</span>';
   //             }
   //          },
   //          {
   //             data: 'mobile_verfied',
   //             name: 'mobile_verfied',
   //             render: function(data) {
   //                let verifiedText = data ? 'Verified' : 'Unverified';
   //                let verifiedClass = data ? 'btn-success' : 'btn-danger';
   //                return '<span class="btn btn-sm ' + verifiedClass + '">' + verifiedText + '</span>';
   //             }
   //          },
   //          {
   //             data: 'kyc_status',
   //             name: 'kyc_status',
   //             orderable: false,
   //             searchable: false
   //          },
   //          {
   //             data: 'status',
   //             name: 'status',
   //          },
   //          {
   //             data: 'action',
   //             name: 'action',
   //             orderable: false,
   //             searchable: false
   //          }
   //       ]
   //    });

   //    // Delete Handler
   //    $('#responsiveDataTable').on('submit', '.delete-customer-form', function(e) {
   //       e.preventDefault();

   //       let form = $(this);
   //       let url = form.attr('action');

   //       if (confirm('Are you sure to delete this customer?')) {
   //          $.ajax({
   //             url: url,
   //             type: 'POST',
   //             data: form.serialize(),
   //             success: function(response) {
   //                if (response.success) {
   //                   toastr.success(response.message || 'Customer deleted successfully!');
   //                   table.ajax.reload(null, false);
   //                } else {
   //                   toastr.error(response.message || 'Failed to delete customer.');
   //                }
   //             },
   //             error: function(xhr) {
   //                toastr.error(xhr.responseJSON?.message || 'An error occurred. Please try again.');
   //             }
   //          });
   //       }
   //    });
   // });

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
>>>>>>> master
