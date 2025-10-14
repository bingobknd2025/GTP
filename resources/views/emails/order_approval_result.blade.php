<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Order {{ $status }}</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6;">
  <h2 style="color: {{ $status === 'Accepted' ? '#27ae60' : '#e74c3c' }};">
    Order {{ $status === 'Accepted' ? 'Approved' : 'Rejected' }} by Customer
  </h2>

  @if($for == 'admin')
  <p>Hello {{ $settings->mail_from_name ?? 'Admin' }},</p>
  <p>The customer has {{ strtolower($status) }} their order. Details below:</p>
  @elseif($for == 'franchise')
  <p>Hello {{ $franchise->name ?? 'Franchise' }},</p>
  <p>Your customer has {{ strtolower($status) }} the order. Details below:</p>
  @endif

  <table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse: collapse;">
    <tr>
      <th>Order ID</th>
      <td>{{ $order->order_no }}</td>
    </tr>
    <tr>
      <th>Customer</th>
      <td>{{ $order->customer->name ?? 'N/A' }}</td>
    </tr>
    <tr>
      <th>Franchise</th>
      <td>{{ $order->franchise->name ?? 'N/A' }}</td>
    </tr>
    <tr>
      <th>Status</th>
      <td>{{ $order->status }}</td>
    </tr>
    <tr>
      <th>Remarks</th>
      <td>{{ $order->customer_remarks ?? 'N/A' }}</td>
    </tr>
    <tr>
      <th>Approved At</th>
      <td>{{ $order->updated_at->format('d M, Y h:i A') }}</td>
    </tr>
  </table>

  @if(!empty($afterImages))
  <h3 style="margin-top:20px;">After Melting Images</h3>
  <div style="display:flex; flex-wrap:wrap; gap:10px;">
    @foreach($afterImages as $img)
    <img src="{{ asset('storage/' . $img) }}" style="width:120px; border:1px solid #ccc; border-radius:6px;">
    @endforeach
  </div>
  @endif

  <p style="margin-top: 20px;">Thanks,<br><strong>{{ config('app.name') }}</strong></p>
</body>

</html>