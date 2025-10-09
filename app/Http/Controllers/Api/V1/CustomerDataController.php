<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\CustomeHelper;
use App\Helpers\OtpHelper;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Enquiry;
use App\Models\Franchise;
use App\Models\Kyc;
use App\Models\Order;
use App\Models\Otp;
use App\Models\Setting;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;

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
            $Orders = Order::where('customer_id', $customerId)->get();

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
                'purity' => 'required|in:18k,20k,22k,24k',
                'before_melting_weight' => 'required|numeric|min:0.01',
                'before_image' => 'nullable|array|max:4',
                'before_image.*' => 'image|mimes:jpeg,png,jpg|max:3072',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validate->errors()->first(),
                ], 400);
            }

            $input = $request->all();
            $purity = (int) $input['purity'];
            $weight = $input['before_melting_weight'];

            $goldApi = [
                "price_gram_24k" => 11526.8432,
                "price_gram_22k" => 10566.2729,
                "price_gram_20k" => 9605.7027,
                "price_gram_18k" => 8645.1324,
            ];

            $purityMap = [
                24 => 'price_gram_24k',
                22 => 'price_gram_22k',
                20 => 'price_gram_20k',
                18 => 'price_gram_18k',
            ];

            $unitPrice = $goldApi[$purityMap[$purity]] ?? 0;
            $totalPrice = $unitPrice * $weight;

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
                $order->purity = $purity;
                $order->before_melting_weight = $weight;
                $order->unite_price = $unitPrice;
                $order->total_price = $totalPrice;
                $order->status = 'Created';
                $order->order_no = $order_no;
                $order->invoice = $invoice;

                $beforeImages = [];
                if ($request->hasFile('before_image')) {
                    foreach ($request->file('before_image') as $file) {
                        if (count($beforeImages) < 4) {
                            $beforeImages[] = $file->store('orders/before', 'public');
                        }
                    }
                }
                $order->before_image = json_encode($beforeImages);

                $order->save();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

            // Email Notifications (Admin, Franchise, Customer)
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

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully!',
                'data' => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
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

    public function updateProfile(Request $request)
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
                'fname' => 'required|string|max:100',
                'lname' => 'required|string|max:100',
                'email' => 'required|email|max:255|unique:customers,email,' . $customer->id,
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 400);
            }

            $customer->fname   = $request->fname;
            $customer->lname   = $request->lname;
            $customer->email   = $request->email;
            $customer->phone   = $request->phone;
            $customer->address = $request->address;
            $customer->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data'    => $customer
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
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
}
