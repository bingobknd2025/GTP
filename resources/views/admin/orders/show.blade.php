@extends('admin.layouts.app')

@section('content')

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between d-flex align-items-center">
                        <div class="card-title">Order Details</div>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-secondary">Back to Order List</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap w-100">
                                <tbody>
                                    <tr>
                                        <th>Order ID:</th>
                                        <td>{{ $order->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Order No:</th>
                                        <td>{{ $order->order_no }}</td>
                                    </tr>
                                    <tr>
                                        <th>Customer Name:</th>
                                        <td>{{ $order->customer->fname ?? 'N/A' }} {{ $order->customer->lname ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Franchise:</th>
                                        <td>{{ $order->franchise->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Purity:</th>
                                        <td>{{ $order->purity ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Before Melting Weight:</th>
                                        <td>{{ number_format($order->before_melting_weight, 4) }}</td>
                                    </tr>
                                    <tr>
                                        <th>After Melting Weight:</th>
                                        <td>{{ number_format($order->after_melting_weight, 4) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Unit Price:</th>
                                        <td>{{ number_format($order->unite_price, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Price:</th>
                                        <td>{{ number_format($order->total_price, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Amount Paid:</th>
                                        <td>{{ number_format($order->amount_paid, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Invoice:</th>
                                        <td>{{ $order->invoice ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            @if($order->status == 'Created')
                                            <span class="badge bg-secondary">Created</span>
                                            @elseif($order->status == 'Gold_Recived')
                                            <span class="badge bg-warning">Gold Received</span>
                                            @elseif($order->status == 'Payment_Done')
                                            <span class="badge bg-success">Payment Done</span>
                                            @else
                                            <span class="badge bg-secondary">{{ $order->status }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Order Note:</th>
                                        <td>{{ $order->order_note ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Before Images:</th>
                                        <td>
                                            @if($order->before_image)
                                            @php $beforeImgs = json_decode($order->before_image, true);
                                            @endphp
                                            @foreach($beforeImgs as $img)
                                            <img src="{{ asset('storage/' . $img) }}" alt="Before Image" class="img-fluid mb-2" style="max-width:150px;">
                                            @endforeach
                                            @else
                                            N/A
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>After Images:</th>
                                        <td>
                                            @if($order->after_image)
                                            @php $afterImgs = json_decode($order->after_image, true); @endphp
                                            @foreach($afterImgs as $img)
                                            <img src="{{ asset('storage/' . $img) }}" alt="After Image" class="img-fluid mb-2" style="max-width:150px;">
                                            @endforeach
                                            @else
                                            N/A
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Created At:</th>
                                        <td>{{ $order->created_at }}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated At:</th>
                                        <td>{{ $order->updated_at }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection