@extends('admin.layouts.app')

@section('content')

<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<div class="main-content app-content">
   <div class="container-fluid">
      <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
         <div class="ms-md-1 mb-1 mb-md-0 ms-0">
            <nav>
               <ol class="breadcrumb mb-0">
                  <li class="breadcrumb-item active" aria-current="page">Franchise</li>
                  <li class="breadcrumb-item"><a href="javascript:void(0);">Franchise Data</a></li>
               </ol>
            </nav>
         </div>
         <div class="page-title fw-semibold fs-18 mb-0">
            <div>
                @can('Franchise Add')
               <a href="{{route('admin.franchises.create')}}" class="btn bg-warning-transparent text-warning btn-sm" data-bs-toggle="tooltip" title="" data-bs-placement="bottom" data-bs-original-title="Add New">
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
                              <th><span>Franchise ID</span></th>
                              <th><span>Code</span></th>
                              <th><span>Name</span></th>
                              <th><span>Email</span></th>
                              <th><span>Contact No.</span></th>
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

<script>
   $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

</script>
<script>
   $(document).ready(function() {
    var table = $('#responsiveDataTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "{{ route('admin.franchises.index') }}",
        columns: [
          { data: 'id', name: 'id', orderable: true, searchable: true },
          { data: 'code', name: 'code' },
          { data: 'name', name: 'name' },
          { data: 'email', name: 'email' },
          { data: 'contact_no', name: 'contact_no' },
          { data: 'status', name: 'status', render: function(data, type, row) {
                let statusText = data ? 'Active' : 'Inactive';
                let statusClass = data ? 'btn-success' : 'btn-danger';
                let activeSelected = data ? 'selected' : '';
                let inactiveSelected = data ? '' : 'selected';

                return `
                    <div class="dropdown">
                        <button class="btn btn-sm ${statusClass} dropdown-toggle" type="button" id="dropdownStatus${row.id}" data-bs-toggle="dropdown" aria-expanded="false">
                            ${statusText}
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownStatus${row.id}">
                            <li><a class="dropdown-item status-toggle" href="#" data-id="${row.id}" data-status="1">Active</a></li>
                            <li><a class="dropdown-item status-toggle" href="#" data-id="${row.id}" data-status="0">Inactive</a></li>
                        </ul>
                    </div>
                `;
            } },
          { data: 'action', name: 'action', orderable: false, searchable: false }
      ]



    });

    // Handle status toggle click
    $('#responsiveDataTable').on('click', '.status-toggle', function(e) {
        e.preventDefault();
        let franchiseId = $(this).data('id');
        let newStatus = $(this).data('status');
        let url = "{{ route('admin.franchises.updateStatus', ':id') }}";
        url = url.replace(':id', franchiseId);

        if (confirm('Are you sure you want to change the status?')) {
            $.ajax({
                url: url,
                method: "POST", // Using POST with _method('PUT') in Laravel
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: 'PUT',
                    status: newStatus
                },
                success: function(response) {
                    alert(response.message);
                    table.ajax.reload(null, false); // Reload DataTables without resetting pagination
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message || 'An error occurred. Please try again.');
                }
            });
        }
    });

});

</script>





@endsection
