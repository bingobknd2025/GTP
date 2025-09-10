@extends('admin.layouts.app')

@section('content')
  
        <div class="main-content app-content">
            <div class="container-fluid">
                <div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">User Details</div>
            </div>
            <div class="card-body">
                <div class="row g-3"> {{-- gap between columns --}}
                    <div class="col-md-4"><strong>Name:</strong> {{ $user->name }}</div>
                    <div class="col-md-4"><strong>Email:</strong> {{ $user->email }}</div>
                    <div class="col-md-4"><strong>Mobile No:</strong> {{ $user->mobile_no }}</div>

                    <div class="col-md-4"><strong>Company Name:</strong> {{ $user->company_name }}</div>
                    <div class="col-md-4"><strong>User ID:</strong> {{ $user->user_id }}</div>
                    <div class="col-md-4"><strong>Address:</strong> {{ $user->address }}</div>

                    <div class="col-md-4"><strong>City:</strong> {{ $user->city }}</div>
                    <div class="col-md-4"><strong>State:</strong> {{ $user->state }}</div>
                    <div class="col-md-4"><strong>GST No:</strong> {{ $user->gst_no }}</div>

                    <div class="col-md-4"><strong>PAN No:</strong> {{ $user->pan_no }}</div>
                    <div class="col-md-4"><strong>Pin Code:</strong> {{ $user->pin_code }}</div>
                    <div class="col-md-4"><strong>Zone:</strong> {{ $user->zoneName ?? 'N/A' }}</div>

                    <div class="col-md-12">
                        <strong>Brands:</strong>
                        @if ($brands->count())
                            @foreach ($brands as $brand)
                                <span class="badge bg-info text-dark me-1">{{ $brand }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back to Users</a>
            </div>
        </div>
    </div>
</div>

 

            </div>
        </div>



@endsection
