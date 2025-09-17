<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Franchise Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: radial-gradient(circle at 60% 40%, #4f8cff 0%, #1e2a3a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: rgba(20, 30, 48, 0.85);
            border-radius: 22px;
            box-shadow: 0 8px 40px rgba(31, 41, 55, 0.18);
            padding: 48px 36px 36px 36px;
            max-width: 350px;
            width: 100%;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .user-icon {
            background: #223a5f;
            border-radius: 50%;
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: -35px;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 2px 12px rgba(79,140,255,0.18);
        }
        .user-icon i {
            font-size: 2.5rem;
            color: #fff;
        }
        .login-form {
            width: 100%;
            margin-top: 40px;
        }
        .form-group {
            margin-bottom: 22px;
            position: relative;
        }
        .form-group input {
            width: 100%;
            padding: 14px 16px 14px 48px; /* ← yahan 48px ya 50px left padding rakhein */
            border: none;
            border-radius: 12px;
            font-size: 1.13rem;
            background: #e9f1ff;
            color: #223a5f;
            outline: none;
        }
        .form-group .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #4f8cff;
            font-size: 1.25rem;
            pointer-events: none;
        }
        .login-btn {
            width: 100%;
            padding: 13px;
            background: linear-gradient(90deg, #4f8cff 0%, #223a5f 100%);
            color: #fff;
            font-size: 1.13rem;
            font-weight: 700;
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(79,140,255,0.13);
            cursor: pointer;
            margin-top: 8px;
        }
        .login-btn:hover {
            background: linear-gradient(90deg, #223a5f 0%, #4f8cff 100%);
        }
        .login-links {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            font-size: 0.98rem;
        }
        .login-links a {
            color: #4f8cff;
            text-decoration: none;
        }
        .login-links a:hover {
            text-decoration: underline;
        }
        .remember-me {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #b6bdd6;
        }
        .error-message {
            color: #e53e3e;
            margin-bottom: 10px;
            text-align:center;
            font-size:0.98rem;
        }
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
            z-index: 10;
        }
        .password-toggle i {
            font-size: 1.5rem;
            color: #b6bdd6;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="user-icon">
            <i class="ri-store-line"></i>
        </div>
        <form class="login-form" method="POST" action="{{ route('franchise.login.submit') }}">
            @csrf
            @if($errors->has('email'))
                <div class="error-message">{{ $errors->first('email') }}</div>
            @endif
            <div class="form-group" style="margin-right: 62px;">
                <span class="input-icon"><i class="ri-mail-line"></i></span>
                <input type="email" name="email" placeholder="Email"  autofocus>
            </div>
            <div class="form-group" style="margin-right: 62px;">
                <span class="input-icon"><i class="ri-lock-2-line"></i></span>
                <input type="password" name="password" placeholder="Password" id="password" >
                <button type="button" class="password-toggle" onclick="togglePassword()" style="margin-right: -63px;">
                    <i class="ri-eye-line" id="passwordToggleIcon"></i>
                </button>
            </div>
           
            <button type="submit" class="login-btn" id="loginBtn">LOGIN</button>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        // Password toggle functionality
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('passwordToggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.className = 'ri-eye-off-line';
            } else {
                passwordInput.type = 'password';
                toggleIcon.className = 'ri-eye-line';
            }
        }

        $(function() {
            @if(session('success'))
                toastr.success(@json(session('success')));
            @endif
            @if(session('error'))
                toastr.error(@json(session('error')));
            @endif

            // Handle AJAX form submission
            $('.login-form').on('submit', function(e) {
                e.preventDefault();
                $('.error-message').text(''); // Clear previous error messages

                const $btn = $('#loginBtn');
                const originalBtnHtml = $btn.html();

                $btn.prop('disabled', true).html('<span class="loading-spinner me-2"></span>LOGGING IN...'); // Show loading

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        $btn.prop('disabled', false).html(originalBtnHtml); // Restore button

                        if (response.success) {
                            toastr.success(response.message || 'Login successful! Redirecting...');
                            window.location.href = response.redirect_url || "{{ route('franchise.dashboard') }}";
                        } else {
                            toastr.error(response.message || 'Login failed!');
                        }
                    },
                    error: function(xhr) {
                        $btn.prop('disabled', false).html(originalBtnHtml); // Restore button

                        if (xhr.status === 422) { // Validation errors
                            const errors = xhr.responseJSON.errors;
                            if (errors.email) {
                                $('input[name="email"]').closest('.form-group').append('<div class="error-message">' + errors.email[0] + '</div>');
                            }
                            if (errors.password) {
                                $('input[name="password"]').closest('.form-group').append('<div class="error-message">' + errors.password[0] + '</div>');
                            }
                        } else {
                            toastr.error(xhr.responseJSON.message || 'An error occurred during login.');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
