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
               <a href="{{route('admin.kycs.create')}}" class="btn bg-warning-transparent text-warning btn-sm" data-bs-toggle="tooltip" title="" data-bs-placement="bottom" data-bs-original-title="Add New">
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
                              <th><span>KYC ID</span></th>
                              <th><span>Customer Name</span></th>
                              <th><span>Document Type</span></th>
                              <th><span>Email</span></th>
                              <th><span>Phone Number</span></th>
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
        ajax: "{{ route('admin.kycs.index') }}",
        columns: [
          { data: 'id', name: 'id' },
          { data: 'customer_name', name: 'customer_name', orderable: false, searchable: false },
          { data: 'document_type', name: 'document_type' },
          { data: 'email', name: 'email' },
          { data: 'phone_number', name: 'phone_number' },
          { data: 'status', name: 'status', render: function(data, type, row) {
                let statusText;
                let statusClass;
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
            } },
          { data: 'action', name: 'action', orderable: false, searchable: false, render: function(data, type, row) {
                    let btn = '';
                    let editUrl = "{{ route('admin.kycs.edit', 'ID_PLACEHOLDER') }}".replace('ID_PLACEHOLDER', row.id);
                    let deleteUrl = "{{ route('admin.kycs.destroy', 'ID_PLACEHOLDER') }}".replace('ID_PLACEHOLDER', row.id);
                    let showUrl = "{{ route('admin.kycs.show', 'ID_PLACEHOLDER') }}".replace('ID_PLACEHOLDER', row.id);

                    @can('KYC Edit')
                    btn += '<a href="' + editUrl + '" class="btn btn-sm btn-primary me-1" title="Edit"><i class="fas fa-edit"></i></a>';
                    @endcan

                    @can('KYC Delete')
                    btn += '<form action="' + deleteUrl + '" method="POST" class="delete-kyc-form" style="display:inline;">' +
                           '{{ csrf_field() }}' +
                           '{{ method_field('DELETE') }}' +
                           '<button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash-alt"></i></button>' +
                           '</form>';
                    @endcan

                    @can('KYC View')
                    btn += '<a href="' + showUrl + '" class="btn btn-sm btn-info me-1" title="View"><i class="fas fa-eye"></i></a>';
                    @endcan
                    
                    return btn;
                } }
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
                    console.log('AJAX success callback hit.', response);
                    toastr.success(response.message || 'KYC entry deleted successfully!');
                    window.location.reload(); // Reload the page after successful deletion
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
