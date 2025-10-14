<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Bank Details Updated</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f8f8f8; padding: 20px;">
  <div style="max-width: 600px; background: #ffffff; padding: 20px; border-radius: 10px; margin: auto;">
    <h2 style="color: #333;">Hello {{ $customer->fname }},</h2>
    <p style="color: #555;">
      We wanted to let you know that your bank details have been successfully updated on your account.
    </p>

    <h3 style="color: #222;">Updated Bank Details:</h3>
    <ul style="color: #444;">
      <li><strong>Bank Name:</strong> {{ $customer->account_bank }}</li>
      <li><strong>Account Name:</strong> {{ $customer->account_name }}</li>
      <li><strong>Account Number:</strong> {{ $customer->account_number }}</li>
      <li><strong>IFSC Code:</strong> {{ $customer->ifsc_code }}</li>
      <li><strong>Account Type:</strong> {{ $customer->account_type }}</li>
    </ul>

    <p style="color: #555;">
      If you didn’t make this change, please contact our support team immediately.
    </p>

    <p style="color: #888; font-size: 14px;">
      Regards,<br>
      <strong>Your Support Team</strong><br>
      {{ config('app.name') }}
    </p>
  </div>
</body>

</html>