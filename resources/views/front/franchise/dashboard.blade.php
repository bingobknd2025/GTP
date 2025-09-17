@extends('admin.layouts.app') {{-- Assuming you have a general front-end app layout, or create a new one --}}

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <h3 class="card-title">Welcome to Franchise Dashboard</h3>
                    </div>
                    <div class="card-body">
                        <p>Hello, {{ Auth::guard('franchise')->user()->name }}!</p>
                        <p>You have successfully logged in to the Franchise Portal.</p>
                        <p>Your Franchise Code: {{ Auth::guard('franchise')->user()->code }}</p>
                        <p>Your Franchise Email: {{ Auth::guard('franchise')->user()->email }}</p>
                        <a href="{{ route('franchise.logout') }}" onclick="event.preventDefault(); document.getElementById('franchise-logout-form').submit();" class="btn btn-danger">Logout</a>
                        <form id="franchise-logout-form" action="{{ route('franchise.logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
