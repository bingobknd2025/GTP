@extends('layouts.backlayout')
@section('content')

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Show Role</div>
                        <div class="text-end mb-3">
                            <a href="{{ route('roles.index') }}">
                                <button type="button" class="btn btn-primary btn-sm">
                                    <i class="fas fa-arrow-left me-1"></i> Back
                                </button>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row gy-4">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <strong>Name:</strong>
                                    <div class="mt-1">{{ $role->name }}</div>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <strong>Permissions:</strong>
                                <div class="mt-2">
                                    @if(!empty($groupedPermissions) && count($groupedPermissions))
                                    @foreach($groupedPermissions as $groupName => $permissions)
                                    <div class="mb-3">
                                        <h6 class="fw-bold text-primary">{{ $groupName }}</h6>
                                        <div class="d-flex flex-wrap">
                                            @foreach($permissions as $perm)
                                            <span class="badge bg-secondary me-2 mb-2">{{ $perm->name }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                    @else
                                    <p class="text-muted">No permissions assigned.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div> <!-- card-body -->
                </div> <!-- card -->
            </div>
        </div>
    </div>
</div>

@endsection