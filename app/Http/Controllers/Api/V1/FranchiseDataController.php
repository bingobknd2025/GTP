<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Helpers\OtpHelper;
use App\Models\Customer;
use App\Models\Franchise;
use App\Models\Order;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;

class FranchiseDataController extends Controller
{
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

            // Fetch customers with pagination
            $customers = Customer::where('ref_by', $franchise->code)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            // Format customers
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
                'data'    => $orders->items(), // ✅ Actual orders array
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
}
