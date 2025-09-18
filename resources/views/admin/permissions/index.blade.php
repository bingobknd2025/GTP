@extends('admin.layouts.app')

@section('content')

<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

<div class="main-content app-content">
   <div class="container-fluid">
      <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
         <div class="ms-md-1 mb-1 mb-md-0 ms-0">
            <nav>
               <ol class="breadcrumb mb-0">
                  <li class="breadcrumb-item active" aria-current="page">Permission</li>
                  <li class="breadcrumb-item"><a href="javascript:void(0);">Permission Data</a></li>
               </ol>
            </nav>
         </div>
         <div class="page-title fw-semibold fs-18 mb-0">
            <a href="{{ route('admin.permissions.create') }}" class="btn bg-warning-transparent text-warning btn-sm" data-bs-toggle="tooltip" title="Add New">
               <span><i class="fa fa-plus"></i></span>
            </a>
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
                              <th>Sr. No.</th>
                              <th>Permission Name</th>
                              <th>Guard</th>
                              <th>Permission Group</th>
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
   $(document).ready(function() {
      $('#responsiveDataTable').DataTable({
         processing: true,
         serverSide: true,
         responsive: true,
         ajax: "{{ route('admin.permissions.index') }}", // ✅ Correct route
         columns: [{
               data: 'DT_RowIndex',
               name: 'DT_RowIndex',
               orderable: false,
               searchable: false
            },
            {
               data: 'name',
               name: 'name'
            },
            {
               data: 'guard_name',
               name: 'guard_name'
            },
            {
               data: 'group_name',
               name: 'group_name'
            }, // group_name column
            {
               data: 'action',
               name: 'action',
               orderable: false,
               searchable: false
            }
         ]
      });
   });
</script>

@endsection