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
              Email Settings
            </div>
          </div>
          <div class="card-body">
            <form id="emailSettingsForm" enctype="multipart/form-data">
              @csrf
              @method('PUT')

              <div class="row gy-4">
                <!-- Mail Server Settings -->
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                  <p class="fw-semibold mb-2">Mail Server</p>
                  <select class="form-control" data-trigger name="mail_server" id="choices-single-default">
                    <option value="mail" {{ $mainSettings->mail_server == 'mail' ? 'selected' : '' }}>Send Mail</option>
                    <option value="smtp" {{ $mainSettings->mail_server == 'smtp' ? 'selected' : '' }}>SMTP</option>
                  </select>
                  <small>Select your mail server</small>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                  <label class="form-label">Email From</label>
                  <input type="email" class="form-control" name="mail_from_email"
                    value="{{ $mainSettings->mail_from_email }}">
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                  <label class="form-label">Email From Name</label>
                  <input type="text" class="form-control" name="mail_from_name"
                    value="{{ $mainSettings->mail_from_name }}">
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                  <label class="form-label">SMTP Host</label>
                  <input type="text" class="form-control" name="smtp_host"
                    value="{{ $mainSettings->smtp_host }}">
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                  <label class="form-label">SMTP Port</label>
                  <input type="number" class="form-control" name="smtp_port"
                    value="{{ $mainSettings->smtp_port }}">
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                  <label class="form-label">SMTP Encryption</label>
                  <input type="text" class="form-control" name="smtp_encryption"
                    value="{{ $mainSettings->smtp_encryption }}">
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                  <label class="form-label">SMTP User</label>
                  <input type="text" class="form-control" name="smtp_user"
                    value="{{ $mainSettings->smtp_user }}">
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                  <label class="form-label">SMTP Password</label>
                  <input type="password" class="form-control" name="smtp_password"
                    value="{{ $mainSettings->smtp_password }}">
                </div>

                <!-- Google Settings -->
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                  <label class="form-label">Google Client ID</label>
                  <input type="text" class="form-control" name="google_id"
                    value="{{ $mainSettings->google_id }}">
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                  <label class="form-label">Google Secret</label>
                  <input type="text" class="form-control" name="google_secret"
                    value="{{ $mainSettings->google_secret }}">
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                  <label class="form-label">Google Redirect</label>
                  <input type="text" class="form-control" name="google_redirect"
                    value="{{ $mainSettings->google_redirect }}">
                </div>

                <!-- Captcha -->
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                  <label class="form-label">Captcha Secret</label>
                  <input type="text" class="form-control" name="capt_secret"
                    value="{{ $mainSettings->capt_secret }}">
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                  <label class="form-label">Captcha Site Key</label>
                  <input type="text" class="form-control" name="capt_sitekey"
                    value="{{ $mainSettings->capt_sitekey }}">
                </div>

                <!-- Buttons -->

              </div>
              <div class="row gy-4 justify-items-center mt-2">
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mt-3">
                  <button type="submit" id="updateBtn" class="form-control btn btn-primary">
                    Update Information
                  </button>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mt-3">
                  <button type="reset" id="resetBtn" class="form-control btn btn-danger">
                    Reset to Default
                  </button>
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
  document.getElementById('emailSettingsForm').addEventListener('submit', function(e) {
    e.preventDefault();

    let updateBtn = document.getElementById('updateBtn');
    updateBtn.disabled = true;
    updateBtn.innerText = "Updating...";

    let formData = new FormData(this);

    fetch("{{ route('admin.settings.preference.update') }}", {
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
        toastr.error("Error: " + err.message);
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