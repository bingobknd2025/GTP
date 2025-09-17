<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>OTP Verification</title>
</head>

<body style="font-family: Arial, sans-serif;">
  <h2>OTP for {{ ucfirst($type) }}</h2>
  <p>Hello {{ $user->fname ?? 'User' }},</p>
  <p>Your One-Time Password (OTP) is:</p>
  <h3 style="color:#27ae60;">{{ $otp }}</h3>
  <p>This OTP is valid for <strong>2 minutes</strong>. Do not share it with anyone.</p>
  <p>Regards,<br>{{ config('app.name') }}</p>
</body>

</html>