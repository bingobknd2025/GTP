@extends('layouts.backlayout')
@section('content')
<div class="main-content app-content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-xl-12">
            <div class="card custom-card">
               <div class="card-header justify-content-between">
                  <div class="card-title">
                     Update Password
                  </div>
                  <div class="text-end mb-3">
                     <a href="{{ route('users.index') }}">
                     <button type="button" class="btn btn-primary btn-sm">
                     <i class="fas fa-arrow-left me-1"></i>
                     Back
                     </button>
                     </a>
                  </div>
               </div>
               @if (session('error'))
               <div class="alert alert-danger">
                  {{ session('error') }}
               </div>
               @endif
               @if (session('success'))
               <div class="alert alert-success">
                  {{ session('success') }}
               </div>
               @endif
               <form id="userForm" method="POST" action="{{ route('users.passwordUpdate.store') }}">
                  @csrf
                  <input type="hidden" name="userid" id="userid" value="{{$userid}}">
                  <div class="card-body">
                     <div class="row gy-4">
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                           <label for="password" class="form-label">New Password</label>
                           <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                           <label for="password" class="form-label">Confirm New Password</label>
                           <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                        </div>
                     </div>
                     <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                        <button type="submit" class="btn btn-primary btn-sm mt-2 mb-3 submitForm"><i class="fa-solid fa-floppy-disk"></i> Submit</button>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection