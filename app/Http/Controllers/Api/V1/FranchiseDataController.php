<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Helpers\OtpHelper;
use App\Helpers\CustomeHelper;
use App\Models\Customer;
use App\Models\Franchise;
use App\Models\Kyc;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class FranchiseDataController extends Controller
{


    public function dashboard()
    {
        try {
            try {
                $franchise = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid or missing token'
                ], 401);
            }

            if (!$franchise) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Franchise not found'
                ], 404);
            }

            $franchiseId = $franchise->id;

            $franchise = Franchise::find($franchiseId);
            $totalCustomers = Customer::where('ref_by', $franchise->code)->count();
            $totalOrders = Order::where('franchise_id', $franchiseId)->count();
            $Orders = Order::where('franchise_id', $franchiseId)->orderBy('created_at', 'desc')->limit(4)->get();
            $totalRevenue = Order::where('franchise_id', $franchiseId)->sum('amount_paid');
            $ref_link = 'http://localhost:5173/customer/register?ref=' . $franchise->code;

            $setting = Setting::first();

            // Total Verified user under this Franchise
            $verifiedCustomers = Customer::where('ref_by', $franchise->code)
                ->where('account_verify', 'approved')
                ->orwhere('kyc_status', 'Verified')
                ->count();

            // Total Pending user under this Franchise
            $pendingCustomers = Customer::where('ref_by', $franchise->code)
                ->where(function ($query) {
                    $query->where('account_verify', 'pending')
                        ->orWhereNull('account_verify')
                        ->orWhere('kyc_status', 'Pending');
                })
                ->count();

            // Total Rejected user under this Franchise
            $rejectedCustomers = Customer::where('ref_by', $franchise->code)
                ->where(function ($query) {
                    $query->where('account_verify', 'rejected')
                        ->orWhere('kyc_status', 'Rejected');
                })->count();

            // Total Orders and no of Todays orders
            $totalOrders = Order::whereHas('customer', function ($query) use ($franchise) {
                $query->where('ref_by', $franchise->code);
            })->count();

            $todayOrders = Order::whereHas('customer', function ($query) use ($franchise) {
                $query->where('ref_by', $franchise->code);
            })
                ->whereDate('created_at', now()) // only orders created today
                ->count();

            $franchiseCode = $franchise->code;

            $approvedKYC = Kyc::whereHas('customer', function ($query) use ($franchiseCode) {
                $query->where('ref_by', $franchiseCode);
            })
                ->where('status', 'Approved')
                ->count();

            $pendingKYC = Kyc::whereHas('customer', function ($query) use ($franchiseCode) {
                $query->where('ref_by', $franchiseCode);
            })
                ->where('status', 'Pending')
                ->count();

            $rejectedKYC = Kyc::whereHas('customer', function ($query) use ($franchiseCode) {
                $query->where('ref_by', $franchiseCode);
            })
                ->where('status', 'Rejected')
                ->count();


            return response()->json([
                'status'  => 'success',
                'message' => 'Dashboard data retrieved successfully',
                'data'    => [
                    'website_currency' => $setting->website_currency,
                    'ref_link'        => $ref_link,
                    'total_customers' => $totalCustomers,
                    'total_orders'    => $totalOrders,
                    'total_revenue'   => $totalRevenue,
                    'verified_customers' => $verifiedCustomers,
                    'pending_customers'  => $pendingCustomers,
                    'rejected_customers' => $rejectedCustomers,
                    'total_orders'    => $totalOrders,
                    'today_orders'   => $todayOrders,
                    'approved_kyc'    => $approvedKYC,
                    'pending_kyc'     => $pendingKYC,
                    'rejected_kyc'    => $rejectedKYC,
                    'franchise'  => $franchise,
                    'orders'     => $Orders,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function profile()
    {
        try {
            try {
                $franchise = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid or missing token'
                ], 401);
            }

            if (!$franchise) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Franchise not found'
                ], 404);
            }

            $franchiseId = $franchise->id;
            CustomeHelper::logFranchiseActivity($franchiseId, 'Viewed Profile');

            $franchise = Franchise::find($franchiseId);

            return response()->json([
                'status'  => 'success',
                'message' => 'Franchise profile retrieved successfully',
                'data'    => $franchise
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            try {
                $franchise = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid or missing token'
                ], 401);
            }

            if (!$franchise) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Franchise not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'name'       => 'nullable|string|max:100',
                'address'       => 'nullable|string|max:100',
                'email'       => 'nullable|email|max:255|unique:customers,email,' . $franchise->id,
                'contact_no'       => 'nullable|string|max:20',
                'contact_person_name'       => 'nullable|string|max:100',
                'contact_person_number'       => 'nullable|string|max:20',
                'store_lat'       => 'nullable|string|max:50',
                'store_long'       => 'nullable|string|max:50',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            $franchise->fill($request->only(['name', 'address', 'email', 'contact_no', 'contact_person_name', 'contact_person_number', 'store_lat', 'store_long']));

            if ($request->hasFile('image')) {
                if ($franchise->image && Storage::disk('public')->exists($franchise->image)) {
                    Storage::disk('public')->delete($franchise->image);
                }

                $path = $request->file('image')->store('profiles', 'public');
                $franchise->image = $path;
            }

            $franchise->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data'    => $franchise,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getCustomers(Request $request)
    {
        try {
            try {
                $franchise = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid or missing token'
                ], 401);
            }

            if (!$franchise) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Franchise not found'
                ], 404);
            }

            $customers = Customer::where('ref_by', $franchise->code)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $customers->getCollection()->transform(function ($customer) {
                return [
                    'id'              => $customer->id,
                    'name'            => trim(($customer->fname ?? '') . ' ' . ($customer->lname ?? '')),
                    'email'           => $customer->email,
                    'mobile_no'       => $customer->mobile_no,
                    'account_balance' => $customer->account_balance,
                    'account_verify'  => $customer->account_verify ?? 'Not Verified',
                    'status'          => $customer->status,
                    'created_at'      => $customer->created_at->toDateTimeString(),
                ];
            });

            return response()->json([
                'status'  => 'success',
                'message' => 'Customers retrieved successfully',
                'data'    => $customers->items(),
                'meta'    => [
                    'current_page' => $customers->currentPage(),
                    'last_page'    => $customers->lastPage(),
                    'per_page'     => $customers->perPage(),
                    'total'        => $customers->total(),
                ],
                'links'   => [
                    'next' => $customers->nextPageUrl(),
                    'prev' => $customers->previousPageUrl(),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getAllCustomers(Request $request)
    {
        try {
            try {
                $franchise = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid or missing token'
                ], 401);
            }

            if (!$franchise) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Franchise not found'
                ], 404);
            }

            $customers = Customer::where('ref_by', $franchise->code)
                ->orderBy('created_at', 'desc')
                ->get();

            $customers->transform(function ($customer) {
                return [
                    'id'              => $customer->id,
                    'name'            => trim(($customer->fname ?? '') . ' ' . ($customer->lname ?? '')),
                    'email'           => $customer->email,
                ];
            });

            return response()->json([
                'status'  => 'success',
                'message' => 'All customers retrieved successfully',
                'data'    => $customers,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function listAllOrders(Request $request)
    {
        try {
            try {
                $franchise = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or missing token'
                ], 401);
            }

            if (!$franchise) {
                return response()->json([
                    'success' => false,
                    'message' => 'Franchise not found'
                ], 404);
            }

            $franchiseId = $franchise->id;
            $settings = Setting::first();

            // Fetch orders with customer data
            $orders = Order::with('customer') // eager load customer
                ->where('franchise_id', $franchiseId)
                ->orderBy('created_at', 'desc')
                ->paginate(10, [
                    'id',
                    'customer_id',
                    'order_no',
                    'invoice',
                    'status',
                    'total_price',
                    'amount_paid',
                    'created_at'
                ]);

            // Map orders to include full customer name
            $ordersData = $orders->map(function ($order) {
                $customer = $order->customer;
                $customerName = $customer ? trim($customer->fname . ' ' . $customer->lname) : 'N/A';

                return [
                    'id'            => $order->id,
                    'customer_id'   => $order->customer_id,
                    'customer_name' => $customerName,
                    'order_no'      => $order->order_no,
                    'invoice'       => $order->invoice,
                    'status'        => $order->status,
                    'total_price'   => $order->total_price,
                    'amount_paid'   => $order->amount_paid,
                    'created_at'    => $order->created_at,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Franchise orders retrieved successfully',
                'website_currency' => $settings->website_currency,
                'data'    => $ordersData,
                'meta'    => [
                    'current_page' => $orders->currentPage(),
                    'last_page'    => $orders->lastPage(),
                    'per_page'     => $orders->perPage(),
                    'total'        => $orders->total(),
                    'has_more'     => $orders->hasMorePages()
                ]
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
            $franchise = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or missing token'
            ], 401);
        }

        if (!$franchise) {
            return response()->json([
                'success' => false,
                'message' => 'Franchise not found'
            ], 404);
        }

        // Validation
        $validate = Validator::make($request->all(), [
            'customer_id'             => 'required|exists:customers,id',
            'purity'                  => 'nullable|string|max:100',
            'before_melting_weight'   => 'required|numeric|min:0',
            'after_melting_weight'    => 'nullable|numeric|min:0',
            'unite_price'             => 'required|numeric|min:0',
            'total_price'             => 'required|numeric|min:0',
            'amount_paid'             => 'nullable|numeric|min:0',
            'status'                  => 'required|in:Created,Gold_Recived,Payment_Done,Order_Cancelled,In_Process',
            'order_note'              => 'nullable|string',

            'before_image'   => 'nullable|array',
            'before_image.*' => 'image|mimes:jpeg,png,jpg|max:3072',

            'after_image'   => 'nullable|array',
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

        $input['order_no'] = 'ORDER' . $yearPart . 'NO' . $seqNumber;
        $input['invoice']  = 'INV' . $yearPart . 'NO' . $seqNumber;

        // Before images
        $beforeImages = [];
        if ($request->hasFile('before_image')) {
            foreach ($request->file('before_image') as $file) {
                $beforeImages[] = $file->store('orders/before', 'public');
            }
        }
        $input['before_image'] = json_encode($beforeImages);

        // After images
        $afterImages = [];
        if ($request->hasFile('after_image')) {
            foreach ($request->file('after_image') as $file) {
                $afterImages[] = $file->store('orders/after', 'public');
            }
        }
        $input['after_image'] = json_encode($afterImages);

        // Save order
        $order = Order::create([
            'order_no'              => $input['order_no'],
            'customer_id'           => $input['customer_id'],
            'franchise_id'          => $franchise->id, // auto from token
            'purity'                => $input['purity'] ?? null,
            'before_melting_weight' => $input['before_melting_weight'],
            'after_melting_weight'  => $input['after_melting_weight'] ?? 0,
            'unite_price'           => $input['unite_price'],
            'total_price'           => $input['total_price'],
            'amount_paid'           => $input['amount_paid'] ?? 0,
            'invoice'               => $input['invoice'],
            'status'                => $input['status'],
            'order_note'            => $input['order_note'] ?? null,
            'before_image'          => $input['before_image'],
            'after_image'           => $input['after_image'] ?? null,
        ]);

        // Send emails
        $mainSettings = Setting::first();
        $user = Customer::find($order->customer_id);

        $order = $mainSettings->website_currency;

        // To Admin
        if ($mainSettings && $mainSettings->mail_from_email) {
            Mail::send('emails.order_notification', [
                'order'    => $order,
                'settings' => $mainSettings,
                'for'      => 'admin'
            ], function ($message) use ($mainSettings) {
                $message->to($mainSettings->mail_from_email, $mainSettings->mail_from_name)
                    ->subject('New Order Created');
            });
        }

        // To Customer
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

        // To Franchise
        if ($franchise && $franchise->email) {
            Mail::send('emails.order_notification', [
                'order'     => $order,
                'franchise' => $franchise,
                'for'       => 'franchise'
            ], function ($message) use ($franchise) {
                $message->to($franchise->email, $franchise->name ?? '')
                    ->subject('Order Created under Your Franchise');
            });
        }


        return response()->json([
            'success' => true,
            'message' => 'Order created successfully!',
            'website_currency' => $mainSettings->website_currency,
            'data'    => $order
        ]);
    }

    public function updateOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id'               => 'required|integer|exists:orders,id',
            'purity'                 => 'nullable|string|max:100',
            'after_melting_weight'   => 'required|numeric|min:0',
            'unite_price'            => 'nullable|numeric|min:0',
            'total_price'            => 'required|numeric|min:0',
            'amount_paid'            => 'nullable|numeric|min:0',
            'status'                 => 'required|in:Created,Gold_Recieved,Payment_Done,Order_Cancelled,In_Process',
            'order_note'             => 'required|string',
            'after_image'            => 'required|array',
            'after_image.*'          => 'image|mimes:jpeg,png,jpg|max:3072',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation Error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            // ✅ Authenticate Franchise
            try {
                $franchise = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid or missing token',
                ], 401);
            }

            if (!$franchise) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Franchise not found',
                ], 404);
            }

            $order = Order::find($request->order_id);

            if (!$order) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Order not found',
                ], 404);
            }

            if ($order->franchise_id !== $franchise->id) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'You do not have permission to update this order',
                ], 403);
            }

            // ✅ Update fields
            $order->fill($request->only([
                'purity',
                'after_melting_weight',
                'unite_price',
                'total_price',
                'amount_paid',
                'status',
                'order_note',
            ]));

            // ✅ Handle after images upload
            if ($request->hasFile('after_image')) {
                $afterImages = json_decode($order->after_image, true) ?? [];

                foreach ($request->file('after_image') as $file) {
                    $afterImages[] = $file->store('orders/after', 'public');
                }

                $order->after_image = json_encode($afterImages);
            }

            $order->save();

            // ✅ Get settings and users
            $mainSettings = Setting::first();
            $user = Customer::find($order->customer_id);

            // ✅ Common email data
            $afterImages = json_decode($order->after_image, true) ?? [];

            // ✅ Send mail to Admin
            if ($mainSettings && $mainSettings->mail_from_email) {
                Mail::send('emails.order_notification', [
                    'order'       => $order,
                    'settings'    => $mainSettings,
                    'for'         => 'admin',
                    'afterImages' => $afterImages,
                ], function ($message) use ($mainSettings) {
                    $message->to($mainSettings->mail_from_email, $mainSettings->mail_from_name)
                        ->subject('Order Updated');
                });
            }

            // ✅ Send mail to User
            if ($user && $user->email) {
                Mail::send('emails.order_notification', [
                    'order'       => $order,
                    'user'        => $user,
                    'for'         => 'user',
                    'afterImages' => $afterImages,
                ], function ($message) use ($user) {
                    $message->to($user->email, $user->fname ?? '')
                        ->subject('Your Order Has Been Updated');
                });
            }

            // ✅ Send mail to Franchise
            if ($franchise && $franchise->email) {
                Mail::send('emails.order_notification', [
                    'order'       => $order,
                    'franchise'   => $franchise,
                    'for'         => 'franchise',
                    'afterImages' => $afterImages,
                ], function ($message) use ($franchise) {
                    $message->to($franchise->email, $franchise->name ?? '')
                        ->subject('Order Updated under Your Franchise');
                });
            }

            return response()->json([
                'status'            => 'success',
                'message'           => 'Order updated successfully',
                'website_currency'  => $mainSettings->website_currency ?? 'INR',
                'data'              => $order,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function orderDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer|exists:orders,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            try {
                $franchise = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or missing token'
                ], 401);
            }

            if (!$franchise) {
                return response()->json([
                    'success' => false,
                    'message' => 'Franchise not found'
                ], 404);
            }

            $orderId = $request->input('order_id');

            $order = Order::find($orderId);

            $beforeimg = json_decode($order->before_image);
            $afterimg = json_decode($order->after_image);

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            if ($order->franchise_id !== $franchise->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to view this order'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'message' => 'Order details retrieved successfully',
                'website_currency' => Setting::first()->website_currency,
                'beforeimg' => $beforeimg,
                'afterimg' => $afterimg,
                'data'    => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getKycList(Request $request)
    {
        try {
            try {
                $franchise = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid or missing token'
                ], 401);
            }

            if (!$franchise) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Franchise not found'
                ], 404);
            }

            $franchiseCode = $franchise->code;

            $customerIds = Customer::where('ref_by', $franchiseCode)->pluck('id')->toArray();

            if (empty($customerIds)) {
                return response()->json([
                    'status'  => 'success',
                    'message' => 'No KYC records found for your referrals.',
                    'data'    => [],
                    'meta'    => [
                        'current_page' => 1,
                        'last_page'    => 1,
                        'per_page'     => 10,
                        'total'        => 0,
                        'has_more'     => false
                    ]
                ]);
            }

            $kycList = Kyc::whereIn('customer_id', $customerIds)
                ->orderBy('created_at', 'desc')
                ->paginate(10, [
                    'id',
                    'customer_id',
                    'first_name',
                    'last_name',
                    'email',
                    'country_code',
                    'phone_number',
                    'kyc_type',
                    'identity_type',
                    'identity_status',
                    'final_status',
                    'status',
                    'franchise_status',
                    'admin_status',
                    'created_at'
                ]);

            // Format response
            return response()->json([
                'status'  => 'success',
                'message' => 'KYC list retrieved successfully',
                'data'    => $kycList->items(),
                'meta'    => [
                    'current_page' => $kycList->currentPage(),
                    'last_page'    => $kycList->lastPage(),
                    'per_page'     => $kycList->perPage(),
                    'total'        => $kycList->total(),
                    'has_more'     => $kycList->hasMorePages()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function kycDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kyc_id' => 'required|integer|exists:kycs,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation Error',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            try {
                $franchise = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid or missing token'
                ], 401);
            }

            if (!$franchise) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Franchise not found'
                ], 404);
            }

            $kycId = $request->input('kyc_id');

            $kyc = Kyc::find($kycId);

            if (!$kyc) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'KYC record not found'
                ], 404);
            }

            $customer = Customer::find($kyc->customer_id);
            if ($customer->ref_by !== $franchise->code) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'You do not have permission to view this KYC record'
                ], 403);
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'KYC details retrieved successfully',
                'data'    => $kyc
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateKycStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kyc_id'          => 'required|integer|exists:kycs,id',
            'franchise_status' => 'required|in:true,false',
            'subject'         => 'required|string',
            'message'         => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation Error',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            try {
                $franchise = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid or missing token'
                ], 401);
            }

            if (!$franchise) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Franchise not found'
                ], 404);
            }

            $kycId = $request->input('kyc_id');
            $franchiseStatus = $request->input('franchise_status');
            $subject = $request->input('subject');
            $messageBody = $request->input('message');

            $kyc = Kyc::find($kycId);

            if (!$kyc) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'KYC record not found'
                ], 404);
            }

            $customer = Customer::find($kyc->customer_id);
            if ($customer->ref_by !== $franchise->code) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'You do not have permission to update this KYC record'
                ], 403);
            }

            $kyc->franchise_status = $franchiseStatus;
            $kyc->save();

            $mainSettings = Setting::first();
            $adminEmail = $mainSettings->mail_from_email ?? config('mail.admin.address');

            // Email to Customer
            if ($customer && $customer->email) {
                Mail::send('emails.kyc_submission', [
                    'title' => $subject,
                    'heading' => $subject,
                    'bodyMessage' => $messageBody,
                    'customer' => $customer
                ], function ($m) use ($customer, $subject) {
                    $m->to($customer->email, $customer->fname)
                        ->subject($subject)
                        ->from(config('mail.from.address'), config('mail.from.name'));
                });
            }

            // Email to Franchise
            if ($franchise && $franchise->email) {
                Mail::send('emails.kyc_submission', [
                    'title' => $subject,
                    'heading' => $subject,
                    'bodyMessage' => $messageBody,
                    'franchise' => $franchise,
                    'customer' => $customer
                ], function ($m) use ($franchise, $subject) {
                    $m->to($franchise->email, $franchise->name)
                        ->subject($subject)
                        ->from(config('mail.from.address'), config('mail.from.name'));
                });
            }

            // Email to Admin
            if ($adminEmail) {
                Mail::send('emails.kyc_submission', [
                    'title' => $subject,
                    'heading' => $subject,
                    'bodyMessage' => $messageBody,
                    'customer' => $customer
                ], function ($m) use ($adminEmail, $subject, $mainSettings) {
                    $m->to($adminEmail, $mainSettings->mail_from_name ?? 'Admin')
                        ->subject($subject)
                        ->from(
                            $mainSettings->mail_from_email ?? config('mail.from.address'),
                            $mainSettings->mail_from_name ?? config('mail.from.name')
                        );
                });
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'KYC status updated and emails sent successfully',
                'data'    => $kyc
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
