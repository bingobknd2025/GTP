<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
<<<<<<< HEAD
  <title>{{ $for == 'admin' ? 'New Withdrawal Notification' : 'Withdrawal Confirmation' }}</title>
=======
  <title>{{ $for == 'admin' ? 'New Deposit Notification' : 'Deposit Confirmation' }}</title>
>>>>>>> master
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6;">
  <h2 style="color: {{ $for == 'admin' ? '#2c3e50' : '#27ae60' }};">
<<<<<<< HEAD
    {{ $for == 'admin' ? 'New Withdrawal Request' : 'Withdrawal Submitted Successfully' }}
=======
    {{ $for == 'admin' ? 'New Deposit Created' : 'Deposit Submitted Successfully' }}
>>>>>>> master
  </h2>

  @if($for == 'admin')
  <p>Hello {{ $settings->mail_from_name ?? 'Admin' }},</p>
<<<<<<< HEAD
  <p>A new withdrawal request has been created. Here are the details:</p>
  @else
  <p>Hello {{ $user->name ?? 'User' }},</p>
  <p>Thank you! Your withdrawal request has been received. Here are the details:</p>
=======
  <p>A new deposit has been created. Here are the details:</p>
  @else
  <p>Hello {{ $user->name ?? 'User' }},</p>
  <p>Thank you! Your deposit request has been received. Here are the details:</p>
>>>>>>> master
  @endif

  <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%;">
    <tr>
      <th align="left">Transaction ID</th>
<<<<<<< HEAD
      <td>{{ $withdraw->txn_id }}</td>
    </tr>
    <tr>
      <th align="left">Amount</th>
      <td>{{ $withdraw->amount }}</td>
    </tr>
    <tr>
      <th align="left">Method</th>
      <td>{{ $withdraw->method }}</td>
=======
      <td>{{ $deposit->txn_id }}</td>
    </tr>
    <tr>
      <th align="left">Amount</th>
      <td>{{ $deposit->amount }}</td>
    </tr>
    <tr>
      <th align="left">Method</th>
      <td>{{ $deposit->method }}</td>
>>>>>>> master
    </tr>
    <tr>
      <th align="left">Status</th>
      <td>
<<<<<<< HEAD
        @if($withdraw->status == 'pending')
        Pending
        @elseif($withdraw->status == 'approved')
        Approved
        @elseif($withdraw->status == 'rejected')
=======
        @if($deposit->status == 'Pending')
        Pending
        @elseif($deposit->status == 'Approved')
        Approved
        @elseif($deposit->status == 'Rejected')
>>>>>>> master
        Rejected
        @else
        Unknown
        @endif
      </td>
    </tr>
  </table>

  @if($for == 'admin')
<<<<<<< HEAD
  <p style="margin-top: 20px;">Please review this withdrawal in the admin panel.</p>
  <p>Regards,<br> {{ $settings->app_name ?? 'Your System' }}</p>
  @else
  <p style="margin-top: 20px;">We will notify you once your withdrawal status is updated.</p>
=======
  <p style="margin-top: 20px;">Please review this deposit in the admin panel.</p>
  <p>Regards,<br> {{ $settings->app_name ?? 'Your System' }}</p>
  @else
  <p style="margin-top: 20px;">We will notify you once your deposit status is updated.</p>
>>>>>>> master
  <p>Regards,<br> {{ config('app.name') }}</p>
  @endif
</body>

</html>