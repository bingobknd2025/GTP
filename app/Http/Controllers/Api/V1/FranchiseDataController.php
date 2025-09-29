<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Helpers\OtpHelper;
use App\Models\Customer;
use App\Models\Franchise;
use App\Models\Kyc;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;

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
            $Orders = Order::where('franchise_id', $franchiseId)->get();
            $totalRevenue = Order::where('franchise_id', $franchiseId)->sum('amount_paid');
            $ref_link = 'http://localhost:5173/customer/register?ref=' . $franchise->code;

            return response()->json([
                'status'  => 'success',
                'message' => 'Dashboard data retrieved successfully',
                'data'    => [
                    'ref_link'        => $ref_link,
                    'total_customers' => $totalCustomers,
                    'total_orders'    => $totalOrders,
                    'total_revenue'   => $totalRevenue,
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

            $orders = Order::where('franchise_id', $franchiseId)
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

            return response()->json([
                'success' => true,
                'message' => 'Franchise orders retrieved successfully',
                'data'    => $orders->items(),
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
