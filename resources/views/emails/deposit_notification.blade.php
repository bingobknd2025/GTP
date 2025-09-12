<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>{{ $for == 'admin' ? 'New Withdrawal Notification' : 'Withdrawal Confirmation' }}</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6;">
  <h2 style="color: {{ $for == 'admin' ? '#2c3e50' : '#27ae60' }};">
    {{ $for == 'admin' ? 'New Withdrawal Request' : 'Withdrawal Submitted Successfully' }}
  </h2>

  @if($for == 'admin')
  <p>Hello {{ $settings->mail_from_name ?? 'Admin' }},</p>
  <p>A new withdrawal request has been created. Here are the details:</p>
  @else
  <p>Hello {{ $user->name ?? 'User' }},</p>
  <p>Thank you! Your withdrawal request has been received. Here are the details:</p>
  @endif

  <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%;">
    <tr>
      <th align="left">Transaction ID</th>
      <td>{{ $withdraw->txn_id }}</td>
    </tr>
    <tr>
      <th align="left">Amount</th>
      <td>{{ $withdraw->amount }}</td>
    </tr>
    <tr>
      <th align="left">Method</th>
      <td>{{ $withdraw->method }}</td>
    </tr>
    <tr>
      <th align="left">Status</th>
      <td>
        @if($withdraw->status == 'pending')
        Pending
        @elseif($withdraw->status == 'approved')
        Approved
        @elseif($withdraw->status == 'rejected')
        Rejected
        @else
        Unknown
        @endif
      </td>
    </tr>
  </table>

  @if($for == 'admin')
  <p style="margin-top: 20px;">Please review this withdrawal in the admin panel.</p>
  <p>Regards,<br> {{ $settings->app_name ?? 'Your System' }}</p>
  @else
  <p style="margin-top: 20px;">We will notify you once your withdrawal status is updated.</p>
  <p>Regards,<br> {{ config('app.name') }}</p>
  @endif
</body>

</html>