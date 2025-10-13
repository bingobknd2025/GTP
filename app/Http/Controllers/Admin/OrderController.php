<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Franchise;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Withdrawal;
use DataTables;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    function __construct()
    {
        // Permissions will be added here later
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Order::orderBy('id', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('id', fn($row) => $row->id)
                ->addColumn('order_no', fn($row) => $row->order_no ?? 'N/A')
                ->addColumn('customer_id', fn($row) => $row->customer_id ?? 'N/A')
                ->addColumn('franchise_id', fn($row) => $row->franchise_id ?? 'N/A')
                ->addColumn('purity', fn($row) => $row->purity ?? 'N/A')
                ->addColumn('before_melting_weight', fn($row) => number_format($row->before_melting_weight, 4))
                ->addColumn('after_melting_weight', fn($row) => number_format($row->after_melting_weight, 4))
                ->addColumn('unite_price', fn($row) => number_format($row->unite_price, 2))
                ->addColumn('total_price', fn($row) => number_format($row->total_price, 2))
                ->addColumn('amount_paid', fn($row) => number_format($row->amount_paid, 2))
                ->addColumn('invoice', fn($row) => $row->invoice ?? 'N/A')
                ->addColumn('status', function ($row) {
                    $statusText = str_replace('_', ' ', $row->status); // e.g., 'Gold_Recived' -> 'Gold Recived'
                    $statusClass = match ($row->status) {
                        'Created' => 'bg-secondary',
                        'Gold_Recived' => 'bg-warning',
                        'Payment_Done' => 'bg-success',
                        'Order_Cancelled' => 'bg-danger',
                        'In_Process' => 'bg-primary',
                        default => 'bg-dark',
                    };
                    return '<span class="badge ' . $statusClass . '">' . $statusText . '</span>';
                })
                ->addColumn('created_at', fn($row) => $row->created_at?->format('Y-m-d H:i:s'))
                ->addColumn('updated_at', fn($row) => $row->updated_at?->format('Y-m-d H:i:s'))
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.orders.edit', $row->id);
                    $deleteUrl = route('admin.orders.destroy', $row->id);
                    $showUrl = route('admin.orders.show', $row->id);

                    $btn  = '<a href="' . $showUrl . '" class="btn btn-sm btn-info me-1"><i class="fas fa-eye fw-bold"></i></a>';
                    $btn .= '<a href="' . $editUrl . '" class="btn btn-sm btn-primary me-1"><i class="fas fa-edit fw-bold"></i></a>';
                    $btn .= '<form action="' . $deleteUrl . '" method="POST" class="d-inline">'
                        . csrf_field() . method_field('DELETE')
                        . '<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')"><i class="fas fa-trash-alt fw-bold"></i></button></form>';

                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.orders.index');
    }


    public function create(): View
    {
        $customers = Customer::all();
        $franchises = Franchise::all();
        return view('admin.orders.create', compact('customers', 'franchises'));
    }

    public function store(Request $request): JsonResponse
    {
        // dd($request->all());
        $validate = Validator::make($request->all(), [
            'customer_id'             => 'required|exists:customers,id',
            'franchise_id'            => 'required|exists:franchises,id',
            'purity'                  => 'nullable|string|max:100',
            'before_melting_weight'   => 'required|numeric|min:0',
            'after_melting_weight'    => 'nullable|numeric|min:0',
            'unite_price'             => 'required|numeric|min:0',
            'total_price'             => 'required|numeric|min:0',
            'amount_paid'             => 'nullable|numeric|min:0',
            'status'                  => 'required|in:Created,Gold_Recived,Payment_Done,Order_Cancelled,In_Process',
            'order_note'              => 'nullable|string',

            // images: single or multiple both allowed
            'before_image' => 'required|array',
            'before_image.*' => 'image|mimes:jpeg,png,jpg|max:3072',

            'after_image' => 'nullable|array',
            'after_image.*' => 'image|mimes:jpeg,png,jpg|max:3072',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors()->first(),
            ], 400);
        }

        $input = $request->all();

        $lastOrder = Order::latest('id')->first();
        $nextSequence = $lastOrder ? $lastOrder->id + 1 : 1;
        $yearPart = now()->format('Y');
        $seqNumber = str_pad($nextSequence, 3, '0', STR_PAD_LEFT);

        // Order Number
        $input['order_no'] = 'ORDER' . $yearPart . 'NO' . $seqNumber;

        // Invoice Number
        $input['invoice'] = 'INV' . $yearPart . 'NO' . $seqNumber;

        // Handle before images
        $beforeImages = [];
        if ($request->hasFile('before_image')) {
            $files = is_array($request->file('before_image'))
                ? $request->file('before_image')
                : [$request->file('before_image')];

            foreach ($files as $file) {
                $beforeImages[] = $file->store('orders/before', 'public');
            }
        }
        $input['before_image'] = json_encode($beforeImages);

        // Handle after images
        $afterImages = [];
        if ($request->hasFile('after_image')) {
            $files = is_array($request->file('after_image'))
                ? $request->file('after_image')
                : [$request->file('after_image')];

            foreach ($files as $file) {
                $afterImages[] = $file->store('orders/after', 'public');
            }
        }
        $input['after_image'] = json_encode($afterImages);

        // Create Order
        $order = Order::create([
            'order_no'              => $input['order_no'],
            'customer_id'           => $input['customer_id'],
            'franchise_id'          => $input['franchise_id'],
            'purity'                => $input['purity'] ?? null,
            'before_melting_weight' => $input['before_melting_weight'],
            'after_melting_weight'  => $input['after_melting_weight'] ?? 0,
            'unite_price'           => $input['unite_price'],
            'total_price'           => $input['total_price'],
            'amount_paid'           => $input['amount_paid'] ?? 0,
            'invoice'               => $input['invoice'] ?? null,
            'status'                => $input['status'],
            'order_note'            => $input['order_note'] ?? null,
            'before_image'          => $input['before_image'],
            'after_image'           => $input['after_image'] ?? null,
        ]);

        // Send Emails
        $mainSettings = Setting::first();
        $user = Customer::find($order->customer_id);

        // Mail to Admin
        Mail::send('emails.order_notification', [
            'order'    => $order,
            'settings' => $mainSettings,
            'for'      => 'admin'
        ], function ($message) use ($mainSettings) {
            $message->to($mainSettings->mail_from_email, $mainSettings->mail_from_name)
                ->subject('New Order Created');
        });

        // Mail to User
        if ($user && $user->email) {
            Mail::send('emails.order_notification', [
                'order' => $order,
                'user'  => $user,
                'for'   => 'user'
            ], function ($message) use ($user) {
                $message->to($user->email, $user->fname ?? '')
                    ->subject('Your Order Has Been Submitted');
            });
        }

        return response()->json([
            'success' => true,
            'message' => 'Order created successfully!',
            'data'    => $order
        ]);
    }


    public function show($id): View
    {
        $order = Order::with(['customer', 'franchise'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }


    public function edit($id): View
    {
        $order = Order::with(['customer', 'franchise'])->findOrFail($id);
        $customers = Customer::all();
        $franchises = Franchise::all();

        return view('admin.orders.edit', compact('order', 'customers', 'franchises'));
    }

    public function update(Request $request, $id): JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'customer_id'             => 'required|exists:customers,id',
            'franchise_id'            => 'required|exists:franchises,id',
            'purity'                  => 'nullable|string|max:100',
            'before_melting_weight'   => 'required|numeric|min:0',
            'after_melting_weight'    => 'nullable|numeric|min:0',
            'unite_price'             => 'required|numeric|min:0',
            'total_price'             => 'required|numeric|min:0',
            'amount_paid'             => 'nullable|numeric|min:0',
            'status'                  => 'required|in:Created,Gold_Recived,Payment_Done,Order_Cancelled,In_Process',
            'order_note'              => 'nullable|string',

            'before_image'            => 'nullable|array',
            'before_image.*'          => 'image|mimes:jpeg,png,jpg|max:3072',

            'after_image'             => 'nullable|array',
            'after_image.*'           => 'image|mimes:jpeg,png,jpg|max:3072',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors()->first(),
            ], 400);
        }

        $order = Order::findOrFail($id);
        $input = $request->all();

        // Merge old + new Before Images
        $beforeImages = json_decode($order->before_image, true) ?? [];
        if ($request->hasFile('before_image')) {
            foreach ($request->file('before_image') as $file) {
                $beforeImages[] = $file->store('orders/before', 'public');
            }
        }
        $order->before_image = json_encode($beforeImages);

        // Merge old + new After Images
        $afterImages = json_decode($order->after_image, true) ?? [];
        if ($request->hasFile('after_image')) {
            foreach ($request->file('after_image') as $file) {
                $afterImages[] = $file->store('orders/after', 'public');
            }
        }
        $order->after_image = json_encode($afterImages);

        // Update other fields
        $order->update([
            'customer_id'           => $input['customer_id'],
            'franchise_id'          => $input['franchise_id'],
            'purity'                => $input['purity'] ?? $order->purity,
            'before_melting_weight' => $input['before_melting_weight'],
            'after_melting_weight'  => $input['after_melting_weight'] ?? 0,
            'unite_price'           => $input['unite_price'],
            'total_price'           => $input['total_price'],
            'amount_paid'           => $input['amount_paid'] ?? $order->amount_paid,
            'status'                => $input['status'],
            'order_note'            => $input['order_note'] ?? $order->order_note,
        ]);

        // Send Emails
        $mainSettings = Setting::first();
        $user = Customer::find($order->customer_id);

        // Mail to Admin
        Mail::send('emails.order_notification', [
            'order'    => $order,
            'settings' => $mainSettings,
            'for'      => 'admin'
        ], function ($message) use ($mainSettings) {
            $message->to($mainSettings->mail_from_email, $mainSettings->mail_from_name)
                ->subject('Order Updated');
        });

        // Mail to User
        if ($user && $user->email) {
            Mail::send('emails.order_notification', [
                'order' => $order,
                'user'  => $user,
                'for'   => 'user'
            ], function ($message) use ($user) {
                $message->to($user->email, $user->fname ?? '')
                    ->subject('Your Order Has Been Updated');
            });
        }

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully!',
            'data'    => $order
        ]);
    }



    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        // Delete before images
        if ($order->before_image) {
            $beforeImages = json_decode($order->before_image, true);
            if (is_array($beforeImages)) {
                foreach ($beforeImages as $file) {
                    if (Storage::disk('public')->exists($file)) {
                        Storage::disk('public')->delete($file);
                    }
                }
            }
        }

        // Delete after images
        if ($order->after_image) {
            $afterImages = json_decode($order->after_image, true);
            if (is_array($afterImages)) {
                foreach ($afterImages as $file) {
                    if (Storage::disk('public')->exists($file)) {
                        Storage::disk('public')->delete($file);
                    }
                }
            }
        }

        // Delete order record
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order and its images deleted successfully!');
    }
}
