<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Order Approval Request</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; background-color: #f8f9fa; padding: 20px;">

  <div style="max-width: 650px; margin: auto; background: #ffffff; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); padding: 20px;">

    <h2 style="color: #2c3e50; text-align: center;">Order Sent for Approval</h2>

    <p>Hello {{ $customer->name ?? 'Customer' }},</p>

    <p>Your franchise <strong>{{ $franchise->name ?? 'Franchise' }}</strong> has reviewed your order and sent it for your approval.
      Please review the details below carefully before confirming.</p>

    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%; margin-top: 15px;">
      <tr style="background-color: #f1f1f1;">
        <th align="left">Order ID</th>
        <td>{{ $order->order_no }}</td>
      </tr>
      <tr>
        <th align="left">Customer Name</th>
        <td>{{ $order->customer->name ?? 'N/A' }}</td>
      </tr>
      <tr>
        <th align="left">Franchise Name</th>
        <td>{{ $order->franchise->name ?? 'N/A' }}</td>
      </tr>
      <tr>
        <th align="left">Item</th>
        <td>{{ $order->item_name ?? 'N/A' }}</td>
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
        <th align="left">Status</th>
        <td><strong>{{ ucfirst($order->status) }}</strong></td>
      </tr>
      <tr>
        <th align="left">Created At</th>
        <td>{{ $order->created_at->format('d M, Y h:i A') }}</td>
      </tr>
    </table>

    @if(!empty($approvalImages))
    <h3 style="margin-top: 25px;">Attached Images</h3>
    <div style="display: flex; flex-wrap: wrap; gap: 12px;">
      @foreach($approvalImages as $image)
      <div style="width: 150px;">
        <img src="{{ asset('storage/' . $image) }}" alt="Approval Image" style="width: 100%; border: 1px solid #ddd; border-radius: 8px;">
      </div>
      @endforeach
    </div>
    @endif

    <p style="margin-top: 25px;">Once you review the details, please click the button below to approve your order.</p>

    <div style="text-align: center; margin-top: 20px;">
      <a href="{{ $approvalLink ?? '#' }}" style="background-color: #27ae60; color: #fff; padding: 12px 25px; text-decoration: none; border-radius: 6px; font-weight: bold;">
        Approve Order
      </a>
    </div>

    <p style="margin-top: 25px;">If you have any questions, please contact your franchise representative for clarification.</p>

    <p style="margin-top: 20px;">Thank you,<br><strong>{{ config('app.name') }}</strong></p>

  </div>

</body>

</html>