<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>
    {{ $for == 'admin_approval' ? 'Order Sent to Customer for Approval' : 'Your Order is Awaiting Your Approval' }}
  </title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; background-color: #f8f9fa; padding: 20px;">
  <div style="max-width: 700px; margin: auto; background: #fff; border-radius: 10px; padding: 25px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">

    <h2 style="color: {{ $for == 'admin_approval' ? '#2c3e50' : '#2980b9' }};">
      {{ $for == 'admin_approval' ? 'Order Sent for Customer Approval' : 'Action Required: Approve Your Order' }}
    </h2>

    @if($for == 'admin_approval')
    <p>Hello {{ $settings->mail_from_name ?? 'Admin' }},</p>
    <p>The following order has been sent to the customer for approval. Here are the order details:</p>
    @elseif($for == 'customer_approval')
    <p>Hello {{ $user->name ?? 'Valued Customer' }},</p>
    <p>We’ve completed processing your order. Please review and approve it for the next step. Below are the details:</p>
    @endif

    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%; font-size: 14px; margin-top: 10px;">
      <tr>
        <th align="left">Order ID</th>
        <td>{{ $order->order_no }}</td>
      </tr>
      <tr>
        <th align="left">Customer</th>
        <td>{{ $order->customer->name ?? 'N/A' }}</td>
      </tr>
      <tr>
        <th align="left">Franchise</th>
        <td>{{ $order->franchise->name ?? 'N/A' }}</td>
      </tr>
      <tr>
        <th align="left">Purity</th>
        <td>{{ $order->purity ?? 'N/A' }}</td>
      </tr>
      <tr>
        <th align="left">After Melting Weight</th>
        <td>{{ $order->after_melting_weight ?? 'N/A' }}</td>
      </tr>
      <tr>
        <th align="left">Unit Price</th>
        <td>{{ $order->unite_price ?? 'N/A' }}</td>
      </tr>
      <tr>
        <th align="left">Total Price (After Melt)</th>
        <td>{{ $order->total_price_after_melt ?? 'N/A' }}</td>
      </tr>
      <tr>
        <th align="left">Status</th>
        <td>{{ $order->status }}</td>
      </tr>
      <tr>
        <th align="left">Created At</th>
        <td>{{ $order->created_at->format('d M, Y h:i A') }}</td>
      </tr>
    </table>

    @if(!empty($afterImages))
    <h3 style="margin-top: 20px; color: #555;">After Melting Images</h3>
    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
      @foreach($afterImages as $image)
      <div style="width: 150px;">
        <img src="{{ asset('storage/' . $image) }}" alt="After Image" style="width: 100%; border: 1px solid #ccc; border-radius: 8px;">
      </div>
      @endforeach
    </div>
    @endif

    @if($for == 'customer_approval')
    <div style="margin-top: 25px;">
      <p>Please click the button below to review and approve your order:</p>
      <a href="{{ url('/customer/orders/approval/' . $order->id) }}"
        style="display: inline-block; background: #27ae60; color: #fff; text-decoration: none; padding: 10px 20px; border-radius: 6px; font-weight: bold;">
        Review & Approve Order
      </a>
    </div>
    @endif

    <p style="margin-top: 30px; color: #555;">
      Thank you,<br>
      <strong>{{ config('app.name') }}</strong>
    </p>
  </div>
</body>

</html>