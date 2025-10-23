<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\OtpHelper;
use App\Helpers\CustomeHelper;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Enquiry;
use App\Models\Franchise;
use App\Models\Kyc;
use App\Models\Order;
use App\Models\Otp;
use App\Models\Setting;
use App\Models\Ticket;
use App\Models\Wallet;
use App\Models\Wdmethod;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class CustomerDataController extends Controller
{

    public function dashboard()
    {
        try {
            try {
                $customer = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid or missing token'
                ], 401);
            }

            if (!$customer) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Customer not found'
                ], 404);
            }

            $customerId = $customer->id;
            CustomeHelper::logCustomerActivity($customerId, 'Viewed Dashboard');

            $customer = Customer::find($customerId);
            $totalOrders = Order::where('customer_id', $customerId)->count();
            $Orders = Order::where('customer_id', $customerId)->latest()->get();

            return response()->json([
                'status'  => 'success',
                'message' => 'Dashboard data retrieved successfully',
                'data'    => [
                    'website_currency' => Setting::first()->website_currency,
                    'total_orders' => $totalOrders,
                    'account_balance' => $customer->account_balance,
                    'amount_paid' => $Orders->sum('amount_paid'),
                    'customer'     => $customer,
                    'orders'       => $Orders,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getProfile(Request $request)
    {
        try {
            try {
                $customer = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid or missing token'
                ], 401);
            }

            if (!$customer) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Customer not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Customer profile retrieved successfully',
                'data'    => $customer
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            try {
                $customer = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or missing token',
                ], 401);
            }

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found',
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'fname'       => 'nullable|string|max:100',
                'lname'       => 'nullable|string|max:100',
                'email'       => 'nullable|email|max:255|unique:customers,email,' . $customer->id,
                'mobile_no'       => 'nullable|string|max:20',
                'address'     => 'nullable|string|max:500',
                'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            $customer->fill($request->only(['fname', 'lname', 'email', 'mobile_no', 'address']));

            if ($request->hasFile('profile_pic')) {
                if ($customer->profile_pic && Storage::disk('public')->exists($customer->profile_pic)) {
                    Storage::disk('public')->delete($customer->profile_pic);
                }

                $path = $request->file('profile_pic')->store('profiles', 'public');
                $customer->profile_pic = $path;
            }

            $customer->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Profile updated successfully',
                'data'    => $customer,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateBankDetails(Request $request)
    {
        try {
            try {
                $customer = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or missing token.',
                ], 401);
            }

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found.',
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'account_name'    => 'required|string|max:255',
                'account_type'    => 'required|string|max:50',
                'ifsc_code'       => 'required|string|max:20',
                'account_number'  => 'required|string|max:20',
                'account_bank'    => 'required|string|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            $customer->update([
                'account_bank'   => $request->account_bank,
                'account_name'   => $request->account_name,
                'account_number' => $request->account_number,
                'ifsc_code'      => $request->ifsc_code,
                'account_type'   => $request->account_type,
            ]);

            CustomeHelper::logCustomerActivity($customer->id, 'Updated Bank Details');

            if (!empty($customer->email)) {
                Mail::send('emails.bank-details-updated', [
                    'customer' => $customer,
                ], function ($message) use ($customer) {
                    $message->to($customer->email, $customer->fname . ' ' . $customer->lname)
                        ->subject('Your Bank Details Have Been Updated');
                });
            }

            return response()->json([
                'success' => true,
                'message' => 'Bank details updated successfully.',
                'data'    => $customer,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getFranchises(Request $request)
    {
        try {
            $franchises = DB::table('franchises')
                ->select('id', 'name', 'code',  'contact_person_name', 'contact_person_number', 'status')
                ->get();

            return response()->json([
                'success' => true,
                'data'    => $franchises
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function createOrder(Request $request)
    {
        try {

            try {
                $customer = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid or missing token'
                ], 401);
            }

            if (!$customer) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Customer not found'
                ], 404);
            }

            $ordersLast24Hours = Order::where('customer_id', $customer->id)
                ->where('created_at', '>=', now()->subDay())
                ->count();

            if ($ordersLast24Hours >= 5) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have reached the maximum limit of 5 orders in the last 24 hours.'
                ], 403);
            }

            $validate = Validator::make($request->all(), [
                'purity' => 'required|in:18k,20k,21k,22k,24k',
                'before_melting_weight' => 'required|numeric|min:1.00',
                'unit_price' => 'required|numeric|min:10',
                'total_price' => 'required|numeric|min:10',
                'before_image' => 'nullable|array|max:4',
                'before_image.*' => 'image|mimes:jpeg,png,jpg|max:3072',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validate->errors()->first(),
                ], 400);
            }

            DB::beginTransaction();

            try {
                $lastOrderGlobal = Order::latest('id')->first();
                $nextSequence = $lastOrderGlobal ? $lastOrderGlobal->id + 1 : 1;
                $yearPart = now()->format('Y');
                $seqNumber = str_pad($nextSequence, 3, '0', STR_PAD_LEFT);

                $order_no = 'ORDER' . $yearPart . 'NO' . $seqNumber;
                $invoice  = 'INV' . $yearPart . 'NO' . $seqNumber;

                $order = new Order();
                $order->customer_id = $customer->id;
                $order->franchise_id = Franchise::where('code', $customer->ref_by)->value('id') ?? null;
                $order->purity = $request->purity;
                $order->before_melting_weight = $request->before_melting_weight;
                $order->unite_price = $request->unit_price;
                $order->total_price = $request->total_price;
                $order->status = 'Created';
                $order->order_no = $order_no;
                $order->invoice = $invoice;

                $beforeImages = [];
                if ($request->hasFile('before_image')) {
                    $files = is_array($request->file('before_image'))
                        ? $request->file('before_image')
                        : [$request->file('before_image')];

                    foreach ($files as $file) {
                        $beforeImages[] = $file->store('orders/before', 'public');
                    }
                }

                $order->before_image = json_encode($beforeImages);
                $order->save();

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
            // ✅ Send Email Notifications
            $mainSettings = Setting::first();
            $user = $customer;

            // Admin Mail
            Mail::send('emails.order_notification', [
                'order' => $order,
                'settings' => $mainSettings,
                'for' => 'admin'
            ], function ($message) use ($mainSettings, $user) {
                $message->to($mainSettings->mail_from_email, $mainSettings->mail_from_name)
                    ->subject('New Order from ' . $user->fname . ' ' . $user->lname . ' on ' . config('app.name'));
            });

            // Franchise Mail
            if ($user->ref_by) {
                $franchise = Franchise::where('code', $user->ref_by)->first();
                if ($franchise && $franchise->email) {
                    Mail::send('emails.order_notification', [
                        'order' => $order,
                        'customer' => $user->fname . ' ' . $user->lname,
                        'for' => 'franchise'
                    ], function ($message) use ($franchise) {
                        $message->to($franchise->email, $franchise->name ?? '')
                            ->subject('New Order from Your Referred Customer on ' . config('app.name'));
                    });
                }
            }

            // Customer Mail
            if ($user && $user->email) {
                Mail::send('emails.order_notification', [
                    'order' => $order,
                    'user' => $user,
                    'for' => 'user'
                ], function ($message) use ($user) {
                    $message->to($user->email, $user->fname ?? '')
                        ->subject('Your Order Has Been Created');
                });
            }


            $beforeImageUrls = collect(json_decode($order->before_image, true) ?? [])
                ->map(fn($path) => asset('storage/' . $path))
                ->toArray();

            return response()->json([
                'status' => 'success',
                'message' => 'Order created successfully!',
                'data' => [
                    'id' => $order->id,
                    'order_no' => $order->order_no,
                    'invoice' => $order->invoice,
                    'purity' => $order->purity,
                    'before_melting_weight' => $order->before_melting_weight,
                    'unite_price' => $order->unite_price,
                    'total_price' => $order->total_price,
                    'status' => $order->status,
                    'before_image' => $beforeImageUrls,
                    'created_at' => $order->created_at,
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateOrderApproval(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id'        => 'required|integer|exists:orders,id',
            'approval_status' => 'required|in:Accepted,Rejected',
            'customer_remarks'         => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation Error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            try {
                $customer = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid or missing token',
                ], 401);
            }

            if (!$customer) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Customer not found',
                ], 404);
            }

            $order = Order::find($request->order_id);

            if (!$order) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Order not found',
                ], 404);
            }

            if ($order->customer_id !== $customer->id) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'You do not have permission to approve this order',
                ], 403);
            }

            if ($order->status !== 'Send_to_customer_approval') {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Order is not awaiting your approval',
                ], 400);
            }

            if ($request->approval_status === 'Accepted') {
                $order->approval_status = 'Accepted';
                $order->status = 'In_Process';
            } else {
                $order->approval_status = 'Rejected';
                $order->status = 'In_Process';
            }

            $order->customer_remarks = $request->customer_remarks ?? null;
            $order->updated_at = now();
            $order->save();

            CustomeHelper::logCustomerActivity($customer->id, 'Customer Updated Order Approval');

            $mainSettings = Setting::first();
            $franchise = Franchise::find($order->franchise_id);
            $afterImages = json_decode($order->after_image, true) ?? [];

            if ($mainSettings && $mainSettings->mail_from_email) {
                Mail::send('emails.order_approval_result', [
                    'order'       => $order,
                    'customer'    => $customer,
                    'for'         => 'admin',
                    'afterImages' => $afterImages,
                    'status'      => $request->approval_status,
                ], function ($message) use ($mainSettings, $request) {
                    $subject = $request->approval_status === 'Accepted'
                        ? 'Order Approved by Customer'
                        : 'Order Rejected by Customer';
                    $message->to($mainSettings->mail_from_email, $mainSettings->mail_from_name)
                        ->subject($subject);
                });
            }

            if ($franchise && $franchise->email) {
                Mail::send('emails.order_approval_result', [
                    'order'       => $order,
                    'customer'    => $customer,
                    'for'         => 'franchise',
                    'afterImages' => $afterImages,
                    'status'      => $request->approval_status,
                ], function ($message) use ($franchise, $request) {
                    $subject = $request->approval_status === 'Accepted'
                        ? 'Customer Approved the Order'
                        : 'Customer Rejected the Order';
                    $message->to($franchise->email, $franchise->name ?? '')
                        ->subject($subject);
                });
            }

            return response()->json([
                'status'  => 'success',
                'message' => $request->approval_status === 'Accepted'
                    ? 'Order approved successfully'
                    : 'Order rejected successfully',
                'data'    => $order,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function listOrders(Request $request)
    {
        try {
            // JWT authentication
            try {
                $customer = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or missing token'
                ], 401);
            }

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            // Get search and pagination params
            $search = $request->input('search'); // search by order_no or invoice
            $perPage = $request->input('per_page', 10); // default 10 per page

            // Build query
            $query = Order::where('customer_id', $customer->id)
                ->orderBy('created_at', 'desc');

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('order_no', 'like', "%$search%")
                        ->orWhere('status', $search)
                        ->orWhere('invoice', 'like', "%$search%");
                });
            }

            // Paginate results
            $orders = $query->paginate($perPage, ['id', 'order_no', 'invoice', 'status', 'total_price', 'amount_paid', 'created_at']);

            return response()->json([
                'success' => true,
                'message' => 'Customer orders retrieved successfully',
                'website_currency' => Setting::first()->website_currency,
                'data'    => $orders
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function orderDetails(Request $request)
    {
        try {
            try {
                $customer = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or missing token'
                ], 401);
            }

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'order_id' => [
                    'required',
                    'numeric',
                    function ($attribute, $value, $fail) use ($customer) {
                        $exists = Order::where('id', $value)
                            ->where('customer_id', $customer->id)
                            ->exists();
                        if (!$exists) {
                            $fail('Order not found or access denied.');
                        }
                    }
                ]
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 400);
            }

            $orderId = $request->order_id;

            $order = Order::where('id', $orderId)
                ->where('customer_id', $customer->id)
                ->first();

            $order->before_image = json_decode($order->before_image, true) ?? [];
            $order->after_image  = json_decode($order->after_image, true) ?? [];

            return response()->json([
                'success' => true,
                'message' => 'Order details retrieved successfully',
                'website_currency' => Setting::first()->website_currency,
                'data'    => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function enquiryStore(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name'    => 'required|string|max:255',
                'email'   => 'required|email|max:255',
                'phone'   => 'nullable|string|max:20',
                'subject' => 'nullable|string|max:255',
                'message' => 'required|string',
            ]);

            $enquiry = Enquiry::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Enquiry submitted successfully.',
                'data'    => $enquiry,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function createTicket(Request $request)
    {
        try {
            try {
                $customer = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or missing token'
                ], 401);
            }

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|exists:customers,email',
                'subject' => 'required|string|max:255',
                'message' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 400);
            }

            $ticket = Ticket::create([
                'customer_id' => $customer->id,
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
            ]);

            // Common mail data
            $mailData = [
                'ticket_id' => $ticket->id,
                'customer_name' => $customer->fname . ' ' . $customer->lname,
                'customer_email' => $customer->email,
                'subject' => $ticket->subject,
                'message_body' => $ticket->message,
                'created_at' => $ticket->created_at,
            ];

            $mainSettings = Setting::first();

            if ($mainSettings && $mainSettings->mail_from_email) {
                Mail::send('emails.support-ticket', array_merge($mailData, ['receiver_type' => 'admin']), function ($message) use ($mainSettings, $mailData) {
                    $message->to($mainSettings->mail_from_email, $mainSettings->mail_from_name)
                        ->subject('New Support Ticket - ' . $mailData['subject']);
                });
            }

            if ($customer->franchise_id) {
                $franchise = Franchise::find($customer->franchise_id);
                if ($franchise && $franchise->email) {
                    Mail::send('emails.support-ticket', array_merge($mailData, ['receiver_type' => 'franchise']), function ($message) use ($franchise, $mailData) {
                        $message->to($franchise->email, $franchise->name ?? '')
                            ->subject('Customer Support Ticket - ' . $mailData['subject']);
                    });
                }
            }

            if ($customer && $customer->email) {
                Mail::send('emails.support-ticket', array_merge($mailData, ['receiver_type' => 'customer']), function ($message) use ($customer, $mailData) {
                    $message->to($customer->email, $customer->fname ?? '')
                        ->subject('Your Ticket Has Been Created - ' . $mailData['subject']);
                });
            }


            return response()->json([
                'success' => true,
                'message' => 'Support ticket created successfully and emails sent',
                'data' => $ticket
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function listTickets(Request $request)
    {
        try {

            try {
                $customer = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or missing token'
                ], 401);
            }

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }
            $search = $request->input('search');
            $perPage = $request->input('per_page', 10);

            $query = Ticket::where('customer_id', $customer->id)
                ->orderBy('created_at', 'desc');

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('subject', 'like', "%$search%")
                        ->orWhere('status', $search);
                });
            }

            $tickets = $query->paginate($perPage, ['id', 'subject', 'message', 'created_at']);

            return response()->json([
                'success' => true,
                'message' => 'Support tickets retrieved successfully',
                'data'    => $tickets
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function listPayments(Request $request)
    {
        try {
            try {
                $customer = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid or missing token'
                ], 401);
            }

            if (!$customer) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Customer not found'
                ], 404);
            }

            $settings = Setting::first();

            $payments = Wallet::with(['franchise', 'order'])
                ->where('customer_id', $customer->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10, [
                    'id',
                    'txn_no',
                    'order_id',
                    'customer_id',
                    'franchise_id',
                    'amount',
                    'type',
                    'note',
                    'created_at'
                ]);

            $paymentsData = $payments->map(function ($payment) {
                $franchiseName = $payment->franchise->name ?? 'N/A';
                return [
                    'id'             => $payment->id,
                    'txn_no'         => $payment->txn_no,
                    'order_id'       => $payment->order_id,
                    'franchise_id'   => $payment->franchise_id,
                    'franchise_name' => $franchiseName,
                    'amount'         => $payment->amount,
                    'type'           => $payment->type,
                    'note'           => $payment->note,
                    'created_at'     => $payment->created_at,
                ];
            });

            return response()->json([
                'status'  => 'success',
                'message' => 'Customer payments retrieved successfully',
                'website_currency' => $settings->website_currency ?? 'INR',
                'data'    => $paymentsData,
                'meta'    => [
                    'current_page' => $payments->currentPage(),
                    'last_page'    => $payments->lastPage(),
                    'per_page'     => $payments->perPage(),
                    'total'        => $payments->total(),
                    'has_more'     => $payments->hasMorePages()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

     public function paymentDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:wallet_transactions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation Error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            try {
                $customer = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid or missing token',
                ], 401);
            }

            if (!$customer) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Customer not found',
                ], 404);
            }

            $wallet = Wallet::with(['franchise', 'order'])
                ->where('customer_id', $customer->id)
                ->where('id', $request->id)
                ->first();

            if (!$wallet) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Transaction not found or access denied',
                ], 404);
            }

            // ✅ Fetch withdrawal using same txn_no
            $withdrawal = Withdrawal::where('txn_id', $wallet->txn_no)->first();

            $settings = Setting::first();

            $data = [
                'id'               => $wallet->id,
                'txn_no'           => $wallet->txn_no,
                'order_id'         => $wallet->order_id,
                'amount'           => $wallet->amount,
                'type'             => $wallet->type,
                'note'             => $wallet->note,
                'status'           => $withdrawal->status ?? 'N/A', // ✅ get from withdrawal
                'reference_no'     => $withdrawal->reference_number ?? 'N/A',
                'payment_mode'     => $withdrawal->payment_mode ?? 'N/A',
                'withdraw_charges' => $withdrawal->charges ?? 0,
                'withdraw_amount'  => $withdrawal->amount ?? 0,
                'to_deduct'        => $withdrawal->to_deduct ?? 0,
                'created_at'       => $wallet->created_at?->format('Y-m-d H:i:s'),
                'updated_at'       => $wallet->updated_at?->format('Y-m-d H:i:s'),
                'website_currency' => $settings->website_currency ?? 'INR',

                'franchise' => $wallet->franchise ? [
                    'id'    => $wallet->franchise->id,
                    'name'  => $wallet->franchise->name,
                    'email' => $wallet->franchise->email,
                ] : null,

                'customer' => [
                    'id'    => $customer->id,
                    'name'  => trim($customer->fname . ' ' . $customer->lname),
                    'email' => $customer->email,
                    'phone' => $customer->phone,
                ],

                'order' => $wallet->order ? [
                    'id'              => $wallet->order->id,
                    'status'          => $wallet->order->status,
                    'approval_status' => $wallet->order->approval_status,
                    'amount_paid'     => $wallet->order->amount_paid,
                    'order_note'      => $wallet->order->order_note,
                    'transaction_id'  => $wallet->order->transaction_id,
                ] : null,
            ];

            return response()->json([
                'status'  => 'success',
                'message' => 'Transaction details retrieved successfully',
                'data'    => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function generateInvoice(Request $request)
    {
        try {
            $customer = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid or missing token',
            ], 401);
        }

        if (!$customer) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Customer not found',
            ], 404);
        }

        $request->validate([
            'id' => 'required|integer|exists:wallet_transactions,id',
        ]);

        $transaction = Wallet::with(['order', 'franchise', 'customer'])
            ->findOrFail($request->id);

        // Get ornament images if available
        $afterImages = [];
        if (!empty($transaction->order->after_image)) {
            $afterImages = json_decode($transaction->order->after_image, true);
            $afterImages = array_map(fn($path) => asset('storage/' . $path), $afterImages);
        }

        $data = [
            'title' => 'Invoice',
            'invoice_no' => $transaction->txn_no,
            'transaction' => $transaction,
            'afterImages' => $afterImages,
            'date' => \Carbon\Carbon::parse($transaction->created_at)->format('d M, Y h:i A'),
        ];

        $pdf = Pdf::loadView('invoices.transaction_invoice', $data);

        return $pdf->download('Invoice_' . $transaction->txn_no . '.pdf');
    }

    public function withdrawMethods(Request $request)
    {
        try {
            try {
                $customer = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid or missing token',
                ], 401);
            }

            if (!$customer) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Customer not found',
                ], 404);
            }

            $methods = Wdmethod::where('status', 'enabled')
                ->select('id', 'name', 'charges_type', 'charges_amount', 'minimum', 'maximum', 'bankname', 'account_number', 'ifsc_code')
                ->get();

            return response()->json([
                'status'  => 'success',
                'message' => 'Withdrawal methods retrieved successfully',
                'data'    => $methods
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function withdrawOtp(Request $request)
    {
        try {
            $customer = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid or missing token',
            ], 401);
        }

        if (!$customer) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Customer not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'type'   => 'required|string|in:register,login,forgot_password,withdrawal'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first(),
            ], 400);
        }    

        OtpHelper::generateAndSendOtp($customer, $request->type); 
        CustomeHelper::logCustomerActivity($customer->id, 'Customer Requested Withdrawal OTP');

        return response()->json([
            'status'  => 'success',
            'message' => 'Withdrawal OTP sent to your email.',
        ]);
    }

    public function verifyWithdrawOtp(Request $request)
    {
        try {
            $customer = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid or missing token.',
            ], 401);
        }

        if (!$customer) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Customer not found.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'otp'  => 'required|digits:6',
            'type' => 'required|string|in:register,login,forgot_password,withdrawal',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $verification = OtpHelper::verifyOtp($customer->id, $request->type, $request->otp);

        if (!$verification['status']) {
            return response()->json([
                'status'  => 'error',
                'message' => $verification['message'],
            ], 400);
        }

        return response()->json([
            'status'  => 'success',
            'message' => $verification['message'],
        ], 200);
    }

    public function withdrawRequest(Request $request)
    {
        try {
            $customer = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid or missing token',
            ], 401);
        }

        if (!$customer) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Customer not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'method_id' => 'required|integer|exists:wdmethods,id',
            'bankname' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'ifsc_code' => 'required|string|max:255',
            'source' => 'required|string|max:255|in:WEB,APP',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $method = Wdmethod::findOrFail($request->method_id);

        $existingPending = Withdrawal::where('customer_id', $customer->id)
            ->where('status', 'Pending')
            ->exists();

        if ($existingPending) {
            return response()->json([
                'status' => 'error',
                'message' => 'You already have a pending withdrawal request. Please wait until it is processed.',
            ], 403);
        }

        $charge = $method->charges_type === 'percentage'
            ? ($request->amount * $method->charges_amount) / 100
            : $method->charges_amount;

        $to_deduct = $request->amount + $charge;

        if ($request->amount < $method->minimum || $request->amount > $method->maximum) {
            return response()->json([
                'status' => 'error',
                'message' => "Amount should be between ${$method->minimum} and ${$method->maximum}.",
            ]);
        }

        $walletBalance = $customer->account_balance;

        if ($walletBalance < $to_deduct) {
            return response()->json([
                'status' => 'error',
                'message' => 'Insufficient wallet balance to make this withdrawal.',
            ], 400);
        }

        DB::beginTransaction();
        try {
            $lastTxn = DB::table('wallet_transactions')->orderBy('id', 'desc')->first();
            $nextNumber = 1;

            if ($lastTxn && preg_match('/TXN\d{4}(\d{6})/', $lastTxn->txn_no, $matches)) {
                $nextNumber = intval($matches[1]) + 1;
            }

            $txnId = 'TXN' . date('Y') . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

            // WITHDRAW20250005 for reference number
            $refNo = 'WITHDRAW' . date('Y') . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

            $withdrawal = Withdrawal::create([
                'txn_id' => $txnId,
                'customer_id' => $customer->id,
                'amount' => $request->amount,
                'charges' => $charge,
                'to_deduct' => $to_deduct,
                'status' => 'Pending',
                'payment_mode' => $method->name,
                'paydetails' => json_encode([
                    'bank_name' => $request->bankname,
                    'account_number' => $request->account_number,
                    'ifsc_code' => $request->ifsc_code,
                ]),
                'reference_number' => $refNo,
                'source' => $request->source,
            ]);

            Wallet::create([
                'txn_no' => $txnId, 
                'reference_no' => $refNo,
                'order_id' => null,
                'franchise_id' => Franchise::where('code', $customer->ref_by)->value('id'),
                'customer_id' => $customer->id,
                'amount' => $to_deduct,
                'type' => 'Withdrawal',
                'note' => 'Withdrawal request initiated via ' . $method->name,
            ]);

            $customer->account_balance -= $to_deduct;
            $customer->save();

            DB::commit();
            CustomeHelper::logCustomerActivity($customer->id, 'Customer Created Withdrawal Request');
            $mainSettings = DB::table('settings')->first();
            $franchise = Franchise::where('code', $customer->ref_by)->first();

            if ($mainSettings && $mainSettings->mail_from_email) {
                Mail::send('emails.withdraw_request', [
                    'withdrawal' => $withdrawal,
                    'settings' => $mainSettings,
                    'for' => 'admin',
                ], function ($message) use ($mainSettings) {
                    $message->to($mainSettings->mail_from_email, $mainSettings->mail_from_name)
                        ->subject('New Withdrawal Request Submitted');
                });
            }

            if (!empty($customer->email)) {
                Mail::send('emails.withdraw_request', [
                    'withdrawal' => $withdrawal,
                    'user' => $customer,
                    'for' => 'user',
                ], function ($message) use ($customer) {
                    $message->to($customer->email, $customer->fname ?? '')
                        ->subject('Your Withdrawal Request Submitted');
                });
            }

            if ($franchise && !empty($franchise->email)) {
                Mail::send('emails.withdraw_request', [
                    'withdrawal' => $withdrawal,
                    'franchise' => $franchise,
                    'for' => 'franchise',
                ], function ($message) use ($franchise) {
                    $message->to($franchise->email, $franchise->name ?? '')
                        ->subject('New Withdrawal Request under Your Franchise');
                });
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Withdrawal request submitted successfully!',
                'data' => $withdrawal,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. ' . $e->getMessage(),
            ], 500);
        }
    }
}

