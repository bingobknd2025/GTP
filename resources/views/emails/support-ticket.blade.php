<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Support Ticket Notification</title>
</head>

<body style="font-family: Arial, sans-serif; color: #333;">
  @if($receiver_type === 'admin')
  <h3>New Support Ticket from Customer</h3>
  <p><strong>Ticket ID:</strong> #{{ $ticket_id }}</p>
  <p><strong>Customer:</strong> {{ $customer_name }} ({{ $customer_email }})</p>
  <p><strong>Subject:</strong> {{ $subject }}</p>
  <p><strong>Message:</strong></p>
  <p>{{ $message_body }}</p>
  <p><strong>Created At:</strong> {{ $created_at }}</p>
  <br>
  <p>Thank you,<br><strong>{{ config('app.name') }} Support Team</strong></p>

  @elseif($receiver_type === 'franchise')
  <h3>New Support Ticket from Your Customer</h3>
  <p><strong>Ticket ID:</strong> #{{ $ticket_id }}</p>
  <p><strong>Customer:</strong> {{ $customer_name }} ({{ $customer_email }})</p>
  <p><strong>Subject:</strong> {{ $subject }}</p>
  <p><strong>Message:</strong></p>
  <p>{{ $message_body }}</p>
  <p><strong>Created At:</strong> {{ $created_at }}</p>
  <br>
  <p>Please review and assist the customer as soon as possible.</p>
  <br>
  <p>Thank you,<br><strong>{{ config('app.name') }} Franchise Support</strong></p>

  @elseif($receiver_type === 'customer')
  <h3>Your Support Ticket Has Been Created</h3>
  <p><strong>Ticket ID:</strong> #{{ $ticket_id }}</p>
  <p><strong>Subject:</strong> {{ $subject }}</p>
  <p><strong>Your Message:</strong></p>
  <p>{{ $message_body }}</p>
  <p><strong>Status:</strong> Open</p>
  <p><strong>Created At:</strong> {{ $created_at }}</p>
  <br>
  <p>Our support team will get back to you shortly.</p>
  <br>
  <p>Thank you,<br><strong>{{ config('app.name') }} Support Team</strong></p>
  @endif
</body>

</html>