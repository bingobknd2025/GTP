@extends('admin.layouts.app')

@section('content')

<div class="main-content app-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">

                    <!-- Card Header -->
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Import Users from Excel</h5>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fa fa-arrow-left me-1"></i> Back to Users
                        </a>
                    </div>

                    <div class="card-body">
                        <form id="UserForm" enctype="multipart/form-data" method="POST" action="{{ route('admin.users.import_excel') }}">

                            @csrf

                            <div class="row">
                                <div class="col-md-6 col-lg-6">
                                    <label for="excel_file" class="form-label">Select Excel File <span class="text-danger">*</span></label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="file" class="form-control" name="excel_file" id="excel_file" required accept=".xlsx,.xls">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-upload me-1"></i> Upload Excel
                                        </button>
                                    </div>
                                    <small class="text-muted">Only .xlsx or .xls files are allowed.</small>
                                </div>
                            </div>

                        </form>
                    </div>


                </div>
            </div>
        </div>

    </div>
</div>

@endsection
