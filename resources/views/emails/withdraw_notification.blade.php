<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>{{ $for === 'admin' ? 'New Withdrawal Notification' : 'Withdrawal Confirmation' }}</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6;">

  <h2 style="color: {{ $for === 'admin' ? '#2c3e50' : '#27ae60' }};">
    @if($for === 'admin')
    New Withdrawal Request
    @else
    Withdrawal Request Submitted
    @endif
  </h2>

  @if($for === 'admin')
  <p>Hello {{ $settings->mail_from_name ?? 'Admin' }},</p>
  <p>A new withdrawal request has been created by a user.</p>
  @else
  <p>Hello {{ $user->name ?? 'User' }},</p>
  <p>Thank you! Your withdrawal request has been received.</p>
  @endif

  <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%; margin-top: 10px;">
    @if($for === 'admin')
    <tr>
      <th align="left">User</th>
      <td>{{ $user->name ?? 'N/A' }} ({{ $user->email ?? 'N/A' }})</td>
    </tr>
    @endif
    <tr>
      <th align="left">Transaction ID</th>
      <td>{{ $withdraw->txn_id }}</td>
    </tr>
    <tr>
      <th align="left">Amount</th>
      <td>{{ number_format($withdraw->amount, 2) }}</td>
    </tr>
    <tr>
      <th align="left">Method</th>
      <td>{{ $withdraw->payment_mode ?? 'N/A' }}</td>
    </tr>
    <tr>
      <th align="left">Status</th>
      <td>{{ ucfirst($withdraw->status) }}</td>
    </tr>
  </table>

  @if($for === 'admin')
  <p style="margin-top: 20px;">Please review and process this withdrawal in the admin panel.</p>
  <p>Regards,<br>{{ $settings->app_name ?? config('app.name') }}</p>
  @else
  <p style="margin-top: 20px;">We will notify you once your withdrawal status is updated.</p>
  <p>Regards,<br>{{ config('app.name') }}</p>
  @endif

</body>

</html>