<!DOCTYPE html>
<html>

<head>
  <title>Withdrawal Request Confirmation</title>
</head>

<body>
  @if($for === 'user')
  <h2>Hello {{ $withdrawal->user_name ?? $user->fname ?? 'Customer' }},</h2>
  <p>Your withdrawal request has been submitted successfully.</p>
  @elseif($for === 'franchise')
  <h2>Hello {{ $franchise->name ?? 'Franchise Partner' }},</h2>
  <p>A withdrawal request has been submitted by one of your customers.</p>
  @else
  <h2>Hello Admin,</h2>
  <p>A new withdrawal request has been submitted by {{ $withdrawal->user_name ?? 'a customer' }}.</p>
  @endif

  <p><strong>Transaction ID:</strong> {{ $withdrawal->txn_id }}</p>
  <p><strong>Amount:</strong> ₹{{ number_format($withdrawal->amount, 2) }}</p>
  <p><strong>Charges:</strong> ₹{{ number_format($withdrawal->charges, 2) }}</p>
  <p><strong>Payment Method:</strong> {{ $withdrawal->payment_mode }}</p>
  <p><strong>Status:</strong> <b>{{ $withdrawal->status }}</b></p>

  <p>We’ll notify you once it’s processed.</p>

  <br>
  <p>Thanks & Regards,<br>
    <strong>World Web Robotics Team</strong>
  </p>
</body>

</html>