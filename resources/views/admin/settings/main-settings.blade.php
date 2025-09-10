@extends('admin.layouts.app')

@section('content')

<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="../assets/libs/prismjs/themes/prism-coy.min.css">

<div class="main-content app-content">



  <div class="container-fluid">
    <div class="row">
      <div class="col-xl-12">
        <div class="card custom-card">
          <div class="card-header justify-content-between">
            <!-- get back response -->
            @if (session('success'))
            <script>
              toastr.success("{{ session('success') }}");
            </script>
            @endif
            @if (session('error'))
            <script>
              toastr.error("{{ session('error') }}");
            </script>
            @endif
            <div class="card-title">
              Main Settings
            </div>
          </div>
          <div class="card-body">
            <form id="settingsForm" enctype="multipart/form-data">
              @csrf
              @method('PUT')

              <div class="row gy-4">

                {{-- Website Name --}}
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                  <label class="form-label">Website Name</label>
                  <input type="text" class="form-control" name="website_name"
                    value="{{ $mainSettings->website_name }}">
                </div>

                {{-- Website Email --}}
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                  <label class="form-label">Website Email</label>
                  <input type="email" class="form-control" name="website_email"
                    value="{{ $mainSettings->website_email }}">
                </div>

                {{-- Website Contact --}}
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                  <label class="form-label">Website Contact</label>
                  <input type="number" class="form-control" name="website_contact"
                    value="{{ $mainSettings->website_contact }}">
                </div>

                {{-- Favicon --}}
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                  <label class="form-label">Website Favicon</label>
                  <input class="form-control" type="file" name="fav_icon">
                </div>

                {{-- Logo --}}
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                  <label class="form-label">Website Logo</label>
                  <input class="form-control" type="file" name="logo">
                </div>
                <div class="card-body d-sm-flex align-item-center justify-content-between">
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="website_under_maintenance"
                      {{ $mainSettings->website_under_maintenance == 'Live' ? 'checked' : '' }}>
                    <label class="form-check-label ms-2">Website Mode Under Maintenance</label>
                  </div>
                  <div class="form-check form-switch col-xl-4 col-lg-6 col-md-6 col-sm-12">
                    <input class="form-check-input" type="checkbox" name="mobile_under_maintenance"
                      {{ $mainSettings->mobile_under_maintenance == 'Live' ? 'checked' : '' }}>
                    <label class="form-check-label ms-2">Mobile Mode Under Maintenance</label>
                  </div>
                </div>

                {{-- Switches --}}
                <div class="card-body d-sm-flex align-item-center justify-content-between">

                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="enable_offline_kyc"
                      {{ $mainSettings->enable_offline_kyc == 'Yes' ? 'checked' : '' }}>
                    <label class="form-check-label ms-2">Offline KYC</label>
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="enable_online_kyc"
                      {{ $mainSettings->enable_online_kyc == 'Yes' ? 'checked' : '' }}>
                    <label class="form-check-label ms-2">Online KYC</label>
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="min_deposit_amount"
                      {{ $mainSettings->min_deposit_amount == 'Yes' ? 'checked' : '' }}>
                    <label class="form-check-label ms-2">Deposit Amount</label>
                  </div>
                </div>
                <div class="card-body d-sm-flex align-item-center justify-content-between">
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="min_withdraw_amount"
                      {{ $mainSettings->min_withdraw_amount == 'Yes' ? 'checked' : '' }}>
                    <label class="form-check-label ms-2">Withdraw Amount</label>
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="mobile_under_maintenance"
                      {{ $mainSettings->mobile_under_maintenance == 'Live' ? 'checked' : '' }}>
                    <label class="form-check-label ms-2">App Under Maintenance</label>
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="display_franchise_user"
                      {{ $mainSettings->display_franchise_user == 'Yes' ? 'checked' : '' }}>
                    <label class="form-check-label ms-2">Display Franchise User</label>
                  </div>
                </div>
                <div class="card-body d-sm-flex align-item-center justify-content-between">
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="display_franchise_kyc"
                      {{ $mainSettings->display_franchise_kyc == 'Yes' ? 'checked' : '' }}>
                    <label class="form-check-label ms-2">Display Franchise KYC</label>
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="allow_franchise_kyc_approve"
                      {{ $mainSettings->allow_franchise_kyc_approve == 'Yes' ? 'checked' : '' }}>
                    <label class="form-check-label ms-2">Allow Franchise KYC Approved</label>
                  </div>

                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="allow_franchise_kyc_add"
                      {{ $mainSettings->allow_franchise_kyc_add == 'Yes' ? 'checked' : '' }}>
                    <label class="form-check-label ms-2">Allow Franchise KYC Add</label>
                  </div>
                </div>

                {{-- Mail Server Settings --}}

                {{-- Buttons --}}
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mt-3">
                  <button type="submit" id="updateBtn" class="form-control btn btn-primary">Update Information</button>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mt-3">
                  <button type="reset" id="resetBtn" class="form-control btn btn-danger">Reset to Default</button>
                </div>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
  document.getElementById('settingsForm').addEventListener('submit', function(e) {
    e.preventDefault();

    let updateBtn = document.getElementById('updateBtn');
    updateBtn.disabled = true;
    updateBtn.innerText = "Updating...";

    let form = document.getElementById('settingsForm');
    let formData = new FormData(form);

    fetch("{{ route('admin.settings.update') }}", {
        method: "POST",
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          toastr.success(data.message);
        } else {
          toastr.error(data.message || "Something went wrong!");
        }
      })
      .catch(err => {
        toastr.error("Error: " + err);
      })
      .finally(() => {
        updateBtn.disabled = false;
        updateBtn.innerText = "Update Information";
      });
  });

  // Reset button click pe toast dikhana
  document.getElementById('resetBtn').addEventListener('click', function() {
    toastr.info("Form reset to default!");
  });
</script>


@endsection