<!DOCTYPE html>
<html>

<head>
  <title>Withdrawal Status Update</title>
</head>

<body>
  @if($role === 'user')
  <p>Hello {{ $user->fname ?? 'User' }},</p>
  <p>Your withdrawal request has been <strong>{{ $withdrawal->status }}</strong>.</p>
  <p><strong>Transaction ID:</strong> {{ $withdrawal->txn_id ?? 'N/A' }}</p>
  <p><strong>Amount:</strong> ₹{{ number_format($withdrawal->amount ?? 0, 2) }}</p>
  <p><strong>Charges:</strong> ₹{{ number_format($withdrawal->charges ?? 0, 2) }}</p>
  <p><strong>Net Amount:</strong> ₹{{ number_format(($withdrawal->amount - $withdrawal->charges) ?? 0, 2) }}</p>
  <p><strong>Payment Mode:</strong> {{ $withdrawal->payment_mode ?? 'N/A' }}</p>
  <p><strong>Reference No:</strong> {{ $withdrawal->reference_no ?? 'N/A' }}</p>

  <p>Thank you for using our service.</p>
  <p><strong>Team GTP AIION GOLD</strong></p>

  @elseif($role === 'franchise')
  <p>Hello {{ $franchise->name ?? 'Franchise Partner' }},</p>
  <p>This is to notify you that one of your referred customers, <strong>{{ $user->fname ?? 'User' }}</strong>, has had a withdrawal request updated.</p>
  <p><strong>Transaction ID:</strong> {{ $withdrawal->txn_id ?? 'N/A' }}</p>
  <p><strong>Status:</strong> {{ $withdrawal->status ?? 'N/A' }}</p>
  <p><strong>Amount:</strong> ₹{{ number_format($withdrawal->amount ?? 0, 2) }}</p>

  <p>This notification is for your reference as a Franchise Partner.</p>
  <p><strong>Team GTP AIION GOLD</strong></p>

  @elseif($role === 'admin')
  <p>Hello {{ $settings->mail_from_name ?? 'Admin' }},</p>
  <p>This is to inform you that a withdrawal request has been updated.</p>
  <p><strong>Transaction ID:</strong> {{ $withdrawal->txn_id ?? 'N/A' }}</p>
  <p><strong>Status:</strong> {{ $withdrawal->status ?? 'N/A' }}</p>
  <p><strong>Amount:</strong> ₹{{ number_format($withdrawal->amount ?? 0, 2) }}</p>
  <p><strong>Charges:</strong> ₹{{ number_format($withdrawal->charges ?? 0, 2) }}</p>
  <p><strong>Net Amount:</strong> ₹{{ number_format(($withdrawal->amount - $withdrawal->charges) ?? 0, 2) }}</p>
  <p><strong>Customer:</strong> {{ $user->fname ?? 'N/A' }} ({{ $user->email ?? 'N/A' }})</p>

  <p>This notification is for your reference as an Admin.</p>
  <p><strong>Team GTP AIION GOLD</strong></p>
  @endif
</body>

</html>