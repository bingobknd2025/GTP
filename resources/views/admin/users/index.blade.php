@extends('admin.layouts.app')

@section('content')

<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

        <div class="main-content app-content">
            <div class="container-fluid">

                <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                    <div class="ms-md-1 mb-1 mb-md-0 ms-0">
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item active" aria-current="page">Users</li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Users Data</a></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="page-title fw-semibold fs-18 mb-0">
                        <div>
                        {{--
                            <a href="javascript:void(0);" class="btn bg-secondary-transparent text-secondary btn-sm" data-bs-toggle="tooltip" title="" data-bs-placement="bottom" data-bs-original-title="Rating">
                                <span>
                                    <i class="fa fa-star"></i>
                                </span>
                            </a>
                            <a href="avascript:void(0);" class="btn bg-primary-transparent text-primary mx-2 btn-sm" data-bs-toggle="tooltip" title="" data-bs-placement="bottom" data-bs-original-title="lock">
                                <span>
                                    <i class="fa fa-lock"></i>
                                </span>
                            </a>
                        --}}

                        @can('User Add')
                            <a href="{{route('admin.users.create')}}" class="btn bg-warning-transparent text-warning btn-sm" data-bs-toggle="tooltip" title="" data-bs-placement="bottom" data-bs-original-title="Add New">
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
                            <div class="card-header d-flex justify-content-between align-items-center">
                               <div class="card-title">
                                   Users List
                               </div>
                            @can('User Excel Import View')
                               <a href="{{route('admin.users.import_excel_view')}}" class="btn btn-sm btn-success">
                                   <i class="fa fa-file-excel"></i> Import ServiceCenter or Users from Excel
                               </a>
                            @endcan
                           </div>


                            <div class="card-body">
                               <div class="table-responsive">
                                   <table id="responsiveDataTable" class="table table-bordered text-nowrap w-100">
                                       <thead>
                                           <tr>
                                               <th>Sr. No.</th>
                                               <th>Full Name</th>
                                               <th>Role Name</th>
                                               <th>User Name</th>
                                               <th>Email ID</th>
                                               <th>Contact No</th>
                                               <th>Zone</th>
                                               <th>Address</th>
                                               <th>City</th>
                                               <th>Pincode</th>
                                               <th>State</th>
                                               <th>Assign Brand</th>
                                               <th>GST</th>
                                               <th>Pan</th>
                                               <th>Created Date</th>
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
        ajax: "{{ route('admin.users.index') }}",
        columns: [
          {
            data: 'id',
            name: 'id',
            render: function (data, type, row, meta) {
                return `
                    ${data}
                    <br>
                    <a href="/unique/admin/users/${data}/pincode" class="btn btn-sm btn-secondary mt-1" title="Pincode">
                        <i class="fas fa-map-pin"></i> Pincode
                    </a>
                `;
            }
        },

          { data: 'name', name: 'name' },
          { data: 'role', name: 'role' },
          { data: 'name', name: 'name' },
          { data: 'email', name: 'email' },
          { data: 'mobile_no', name: 'mobile_no' },
          { data: 'zone', name: 'zone' },
          { data: 'address', name: 'address' },
          { data: 'city', name: 'city' },
          { data: 'pin_code', name: 'pin_code' },
          { data: 'state', name: 'state' },
          { data: 'brand_names', name: 'brand_names' },
          { data: 'gst_no', name: 'gst_no' },
          { data: 'pan_no', name: 'pan_no' },
          { data: 'created_at', name: 'created_at' },
          { data: 'action', name: 'action', orderable: false, searchable: false }
      ]



    });

});

</script>




     @endsection