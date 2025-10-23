<!DOCTYPE html>
<html>

<head>
  <title>Withdrawal Status Update</title>
</head>

<body>
  @php
  $recipientName = $user->fname ?? $user->name ?? 'User';
  $recipientType = ucfirst($for); // 'Admin', 'Franchise', or 'User'
  @endphp

  <p>Hello {{ $recipientName }},</p>

  <p>
    This is to inform you that a withdrawal request has been updated.
  </p>

  <p>
    <strong>Transaction ID:</strong> {{ $withdrawal->txn_id ?? 'N/A' }}<br>
    <strong>Status:</strong> {{ $withdrawal->status ?? 'N/A' }}<br>
    <strong>Amount:</strong> ₹{{ number_format($withdrawal->amount ?? 0, 2) }}<br>
    <strong>Charges:</strong> ₹{{ number_format($withdrawal->charges ?? 0, 2) }}<br>
    <strong>To Deduct:</strong> ₹{{ number_format($withdrawal->to_deduct ?? 0, 2) }}<br>
    <strong>Payment Mode:</strong> {{ $withdrawal->payment_mode ?? 'N/A' }}<br>
    <strong>Reference No:</strong> {{ $withdrawal->reference_number ?? 'N/A' }}
  </p>

  <p>
    This notification is for your reference as a {{ $recipientType }}.
  </p>

  <p>Thank you,<br>Team {{ config('app.name') }}</p>
</body>

</html>