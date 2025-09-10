@extends('admin.layouts.app')

@section('content')

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between d-flex align-items-center">
                         <div class="card-title">Edit User</div>
                         <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary">Back to Users</a>
                     </div>
                    <div class="card-body">
                        <form id="UserForm" method="POST" action="{{ route('admin.users.update', $user->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row gy-4">

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label class="form-label">First Name:</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', explode(' ', $user->name)[0]) }}" required>
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label class="form-label">Last Name:</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', explode(' ', $user->name)[1] ?? '') }}">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label class="form-label">Company Name:</label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name', $user->company_name) }}">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label class="form-label">User ID:</label>
                                    <input type="text" class="form-control" id="user_id" name="user_id" value="{{ old('user_id', $user->user_id) }}">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label class="form-label">Role:</label>
                                    <select class="form-control select2" id="role" name="roles[]" multiple="multiple" style="width: 100%;" required>
                                        @foreach ($roles as $value => $label)
                                            <option value="{{ $value }}" {{ in_array($value, old('roles', $userRole)) ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label class="form-label">Email ID:</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label class="form-label">Contact No.:</label>
                                    <input type="text" class="form-control" id="mobile_no" name="mobile_no" 
                                        pattern="\d{10}" maxlength="10" 
                                        title="Please enter exactly 10 digits" 
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10);" 
                                        value="{{ old('mobile_no', $user->mobile_no) }}"
                                        required>
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label class="form-label">Address:</label>
                                    <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $user->address) }}">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label class="form-label">City:</label>
                                    <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $user->city) }}">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label class="form-label">State:</label>
                                    <input type="text" class="form-control" id="state" name="state" value="{{ old('state', $user->state) }}">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label class="form-label">GST No.:</label>
                                    <input type="text" class="form-control" id="gst_no" name="gst_no" value="{{ old('gst_no', $user->gst_no) }}">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label class="form-label">PAN:</label>
                                    <input type="text" class="form-control" id="pan_no" name="pan_no" value="{{ old('pan_no', $user->pan_no) }}">
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                  <label class="form-label">Pincode:</label>
                                  <input 
                                      type="text" 
                                      class="form-control" 
                                      id="pin_code" 
                                      name="pin_code" 
                                      value="{{ old('pin_code', $user->pin_code) }}"
                                      maxlength="6" 
                                      pattern="\d{6}" 
                                      oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,6);" 
                                      title="Please enter exactly 6 digits"
                                      placeholder="Enter 6-digit Pincode"
                                      required
                                  >
                              </div>


                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <label class="form-label">Zone:</label>
                                    <select class="form-control" id="zone_id" name="zone_id">
                                        <option value="">Select Zone</option>
                                        @foreach($zoneData as $singlezone)
                                            <option value="{{ $singlezone->id }}" {{ old('zone_id', $user->zone_id) == $singlezone->id ? 'selected' : '' }}>{{ $singlezone->zone_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Brand checkbox list -->
                                <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12">
                                    <label class="form-label d-block">Assign Brand:</label>
                                    <div class="d-flex flex-wrap gap-3">
                                        @php
                                            $userBrands = json_decode(old('brand_ids', $user->brand_ids), true) ?? [];
                                        @endphp
                                        @foreach($brandData as $brand)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="brand_ids[]" value="{{ $brand->id }}" id="brand_{{ $brand->id }}"
                                                {{ in_array($brand->id, $userBrands) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="brand_{{ $brand->id }}">
                                                    {{ $brand->brand_name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Submit button right aligned -->
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-sm btn-primary">Update User</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery & Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    const usersIndexUrl = "{{ route('admin.users.index') }}";

    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select a role",
            allowClear: true,
            width: '100%'
        });

        $('#UserForm').on('submit', function(e) {
            e.preventDefault();
            $('.text-danger').remove();

            $.ajax({
                url: $(this).attr('action'),
                method: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    alert('User updated successfully');
                    window.location.href = usersIndexUrl;
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, messages) {
                            const input = $('[name="'+key+'"]');
                            if(input.length) {
                                input.after('<span class="text-danger">'+messages[0]+'</span>');
                            }
                        });
                    } else {
                        alert('Something went wrong, please try again.');
                    }
                }
            });
        });
    });
</script>

@endsection
