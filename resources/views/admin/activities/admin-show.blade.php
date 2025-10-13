@extends('admin.layouts.app')

@section('content')

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between d-flex align-items-center">
                        <div class="card-title">Admin Activity Details</div>
                        <a href="{{ route('admin.activity.index') }}" class="btn btn-sm btn-secondary">Back to Activity List</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap w-100">
                                <tbody>
                                    <tr>
                                        <th>Activity ID:</th>
                                        <td>{{ $activity->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>User ID:</th>
                                        <td>{{ $activity->user_id ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Type:</th>
                                        <td>{{ $activity->type ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Details:</th>
                                        <td>{{ $activity->details ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>IP Address:</th>
                                        <td>{{ $activity->ip_address ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Device :</th>
                                        <td>{{ $activity->device ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Browser:</th>
                                        <td>{{ $activity->browser ?? 'N/A' }}</td>
                                    </tr>

                                    <tr>
                                        <th>OS:</th>
                                        <td>{{ $activity->os ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Created At:</th>
                                        <td>{{ $activity->created_at }}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated At:</th>
                                        <td>{{ $activity->updated_at }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection