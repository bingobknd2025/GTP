<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\OtpHelper;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Franchise;
use App\Models\Kyc;
use App\Models\Order;
use App\Models\Otp;
use App\Models\Setting;
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

            $customer = Customer::find($customerId);
            $totalOrders = Order::where('customer_id', $customerId)->count();
            $Orders = Order::where('customer_id', $customerId)->get();

            return response()->json([
                'status'  => 'success',
                'message' => 'Dashboard data retrieved successfully',
                'data'    => [
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
                'franchise_id'            => 'required|exists:franchises,id',
                'purity'                  => 'nullable|string|max:100',
                'before_melting_weight'   => 'required|numeric|min:0',
                'after_melting_weight'    => 'nullable|numeric|min:0',
                'unite_price'             => 'required|numeric|min:0',
                'total_price'             => 'required|numeric|min:0',
                'amount_paid'             => 'nullable|numeric|min:0',
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

            $input = $request->all();

            DB::beginTransaction();
            try {
                $lastOrderGlobal = Order::latest('id')->first();
                $nextSequence = $lastOrderGlobal ? $lastOrderGlobal->id + 1 : 1;
                $yearPart = now()->format('Y');
                $seqNumber = str_pad($nextSequence, 3, '0', STR_PAD_LEFT);

                $order_no = 'ORDER' . $yearPart . 'NO' . $seqNumber;
                $invoice  = 'INV'   . $yearPart . 'NO' . $seqNumber;

                $order = new Order();
                $order->customer_id           = $customer->id;
                $order->franchise_id          = $input['franchise_id'];
                $order->purity                = $input['purity'] ?? null;
                $order->before_melting_weight = $input['before_melting_weight'];
                $order->after_melting_weight  = $input['after_melting_weight'] ?? 0;
                $order->unite_price           = $input['unite_price'];
                $order->total_price           = $input['total_price'];
                $order->amount_paid           = $input['amount_paid'] ?? 0;
                $order->status                = 'Created';
                $order->order_note            = $input['order_note'] ?? null;
                $order->order_no              = $order_no;
                $order->invoice               = $invoice;

                $beforeImages = [];
                if ($request->hasFile('before_image')) {
                    foreach ($request->file('before_image') as $file) {
                        $beforeImages[] = $file->store('orders/before', 'public');
                    }
                }
                $order->before_image = json_encode($beforeImages);

                $afterImages = [];
                if ($request->hasFile('after_image')) {
                    foreach ($request->file('after_image') as $file) {
                        $afterImages[] = $file->store('orders/after', 'public');
                    }
                }
                $order->after_image = json_encode($afterImages);

                $order->save();

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

            $mainSettings = Setting::first();
            $user = $customer;

            // Mail to Admin
            Mail::send('emails.order_notification', [
                'order'    => $order,
                'settings' => $mainSettings,
                'for'      => 'admin'
            ], function ($message) use ($mainSettings, $user) {
                $message->to($mainSettings->mail_from_email, $mainSettings->mail_from_name)
                    ->subject('New Order from ' . $user->fname . ' ' . $user->lname . ' on ' . config('app.name'));
            });

            // Mail to User
            if ($user && $user->email) {
                Mail::send('emails.order_notification', [
                    'order' => $order,
                    'user'  => $user,
                    'for'   => 'user'
                ], function ($message) use ($user) {
                    $message->to($user->email, $user->fname ?? '')
                        ->subject('Your Order Has Been Created');
                });
            }

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully!',
                'data'    => $order
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
                'data'    => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
