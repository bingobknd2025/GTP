<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\OtpHelper;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;

class CustomerAuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fname' => 'required|string|max:100',
            'lname' => 'required|string|max:100',
            'email' => 'required|email|unique:customers',
            'password' => 'required|string|min:6|confirmed',
            'mobile_no' => 'required|digits_between:10,15|unique:customers'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $data['password'] = Hash::make($data['password']);
        $customer = Customer::create($data);

        $token = JWTAuth::fromUser($customer);

        OtpHelper::generateAndSendOtp($customer, 'register');

        return response()->json([
            'status'  => 'success',
            'message' => 'Customer registered successfully. OTP has been sent to your email for verification.',
            'token'   => $token,
            'customer' => [
                'id'    => $customer->id,
                'fname' => $customer->fname,
                'lname' => $customer->lname,
                'email' => $customer->email,
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth('customer')->attempt($credentials)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid Credentials'
            ], 401);
        }

        $customer = auth('customer')->user();

        return response()->json([
            'status'   => 'success',
            'token'    => $token,
            'customer' => [
                'id'             => $customer->id,
                'name'           => $customer->fname . $customer->lname,
                'email'          => $customer->email,
                'verified_email' => (bool) $customer->email_verfied,
                'created_at'     => $customer->created_at,
                'updated_at'     => $customer->updated_at,
            ]
        ], 200);
    }


    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp'  => 'required|digits:6',
            'type' => 'required|string'
        ]);


        try {
            $customer = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid or missing token'
            ], 401);
        }

        $otpData = Otp::where('customer_id', $customer->id)
            ->where('type', $request->type)
            ->where('otp', $request->otp)
            ->first();

        if (!$otpData) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid OTP'
            ], 400);
        }

        if (now()->greaterThan($otpData->expires_at)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'OTP expired'
            ], 400);
        }

        $otpData->delete();

        $customer->email_verfied = 1;
        $customer->save();

        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            'status'   => 'success',
            'message'  => 'OTP verified successfully. Please login again to continue.',
        ], 200);
    }


    public function resendOtp(Request $request)
    {
        $request->validate([
            'type'  => 'required|string|in:register,login,forgot_password'
        ]);

        try {
            // JWT Token se authenticate karne ki koshish
            $customer = JWTAuth::parseToken()->authenticate();

            if (!$customer) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Customer not found'
                ], 404);
            }
        } catch (JWTException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid or missing token'
            ], 401);
        }

        // Generate & send OTP (safely)
        $otpSent = OtpHelper::generateAndSendOtp($customer, $request->type);

        if (!$otpSent) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to generate OTP'
            ], 500);
        }

        return response()->json([
            'status'   => 'success',
            'message'  => 'A new OTP has been sent to your email for ' . ucfirst($request->type),
            'customer' => [
                'id'    => $customer->id,
                'fname' => $customer->fname,
                'lname' => $customer->lname,
                'email' => $customer->email,
            ]
        ], 200);
    }

    public function logout()
    {
        auth('customer')->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out'
        ], 200);
    }
}
