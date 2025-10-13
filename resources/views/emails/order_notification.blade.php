<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>{{ $for == 'admin' ? 'New Order Notification' : 'Order Confirmation' }}</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6;">
  <h2 style="color: {{ $for == 'admin' ? '#2c3e50' : '#27ae60' }};">
    {{ $for == 'admin' ? 'New Order Received' : 'Order Placed Successfully' }}
  </h2>

  @if($for == 'admin')
  <p>Hello {{ $settings->mail_from_name ?? 'Admin' }},</p>
  <p>A new order has been placed. Here are the details:</p>
  @else
  <p>Hello {{ $user->name ?? 'User' }},</p>
  <p>Thank you! Your order has been placed successfully. Here are the details:</p>
  @endif

  <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%;">
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
      <th align="left">Unit Price</th>
      <td>{{ $order->unite_price }}</td>
    </tr>
    <tr>
      <th align="left">Total Price</th>
      <td>{{ $order->total_price }}</td>
    </tr>
    <tr>
      <th align="left">Payment Method</th>
      <td>{{ $order->payment_method ?? 'N/A' }}</td>
    </tr>
    <tr>
      <th align="left">Status</th>
      <td>
        <!-- Created,Gold_Recived,Payment_Done,Order_Cancelled,In_Process', -->
        @if($order->status == 'Created')
        Created
        @elseif($order->status == 'Gold_Recived')
        Gold Received
        @elseif($order->status == 'Payment_Done')
        Payment Done
        @elseif($order->status == 'Order_Cancelled')
        Order Cancelled
        @elseif($order->status == 'In_Process')
        In Process
        @else
        Unknown
        @endif
      </td>
    </tr>
    <tr>
      <th align="left">Created At</th>
      <td>{{ $order->created_at->format('d M, Y h:i A') }}</td>
    </tr>
  </table>

  @if($for == 'admin')
  <p style="margin-top: 20px;">Please review this order in the admin panel.</p>
  <p>Regards,<br> {{ $settings->app_name ?? 'Your System' }}</p>
  @else
  <p style="margin-top: 20px;">We will notify you once your order status is updated.</p>
  <p>Regards,<br> {{ config('app.name') }}</p>
  @endif
</body>

</html>