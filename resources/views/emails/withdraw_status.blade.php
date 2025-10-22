<!DOCTYPE html>
<html>

<head>
  <title>Withdrawal Status Update</title>
</head>

<body>
  <p>Hello {{ $user->fname ?? 'User' }},</p>

  <p>Your withdrawal request (Txn ID: <strong>{{ $withdrawal->txn_id }}</strong>) has been updated.</p>

  <p><strong>Status:</strong> {{ $withdrawal->status }}</p>
  <p><strong>Amount:</strong> ₹{{ number_format($withdrawal->amount, 2) }}</p>

  <p>Thank you,<br>Team {{ config('app.name') }}</p>
</body>

</html>