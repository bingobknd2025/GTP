<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>{{ $for == 'admin' ? 'New Deposit Notification' : 'Deposit Confirmation' }}</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6;">
  <h2 style="color: {{ $for == 'admin' ? '#2c3e50' : '#27ae60' }};">
    {{ $for == 'admin' ? 'New Deposit Created' : 'Deposit Submitted Successfully' }}
  </h2>

  @if($for == 'admin')
  <p>Hello {{ $settings->mail_from_name ?? 'Admin' }},</p>
  <p>A new deposit has been created. Here are the details:</p>
  @else
  <p>Hello {{ $user->name ?? 'User' }},</p>
  <p>Thank you! Your deposit request has been received. Here are the details:</p>
  @endif

  <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%;">
    <tr>
      <th align="left">Transaction ID</th>
      <td>{{ $deposit->txn_id }}</td>
    </tr>
    <tr>
      <th align="left">Amount</th>
      <td>{{ $deposit->amount }}</td>
    </tr>
    <tr>
      <th align="left">Method</th>
      <td>{{ $deposit->method }}</td>
    </tr>
    <tr>
      <th align="left">Status</th>
      <td>
        @if($deposit->status == 'Pending')
        Pending
        @elseif($deposit->status == 'Approved')
        Approved
        @elseif($deposit->status == 'Rejected')
        Rejected
        @else
        Unknown
        @endif
      </td>
    </tr>
  </table>

  @if($for == 'admin')
  <p style="margin-top: 20px;">Please review this deposit in the admin panel.</p>
  <p>Regards,<br> {{ $settings->app_name ?? 'Your System' }}</p>
  @else
  <p style="margin-top: 20px;">We will notify you once your deposit status is updated.</p>
  <p>Regards,<br> {{ config('app.name') }}</p>
  @endif
</body>

</html>