<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\OtpHelper;
use App\Http\Controllers\Controller;
use App\Models\Franchise;
use App\Models\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;

class FranchiseAuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:franchises',
            'password' => 'required|string|min:6|confirmed',
            'pincode' => 'required|string',
            'address' => 'required|string',
            'contact_person_name' => 'required|string',
            'contact_person_number' => 'required|digits_between:10,15|unique:franchises',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $data['password'] = Hash::make($data['password']);

        $lastFranchise = Franchise::orderBy('id', 'desc')->first();
        if ($lastFranchise && $lastFranchise->code) {
            $lastNumber = (int) filter_var($lastFranchise->code, FILTER_SANITIZE_NUMBER_INT);
            $newNumber  = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $data['code'] = 'FRANCODE' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        $franchise = Franchise::create($data);

        $token = JWTAuth::fromUser($franchise);

        OtpHelper::generateAndSendOtps($franchise, 'franchise_register');

        return response()->json([
            'status'  => 'success',
            'message' => 'Franchise registered successfully. OTP has been sent to your email for verification.',
            'token'   => $token,
            'customer' => [
                'id'    => $franchise->id,
                'code'  => $franchise->code,
                'name' => $franchise->name,
                'email' => $franchise->email,
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth('franchise')->attempt($credentials)) {
            return response()->json(['error' => 'Invalid Credentials'], 401);
        }

        $franchise = auth('franchise')->user();

        return response()->json([
            'status'   => 'success',
            'token'    => $token,
            'franchise' => [
                'id'             => $franchise->id,
                'name'           => $franchise->name,
                'email'          => $franchise->email,
                'verified_email' => (bool) $franchise->is_verified,
                'created_at'     => $franchise->created_at,
                'updated_at'     => $franchise->updated_at,
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
            $franchise = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid or missing token'
            ], 401);
        }

        $otpData = Otp::where('customer_id', $franchise->id)
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

        $franchise->is_verified = 1;
        $franchise->save();

        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            'status'   => 'success',
            'message'  => 'OTP verified successfully. Please login again to continue.',
        ], 200);
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'type'  => 'required|string|in:franchise_register,login,forgot_password'
        ]);

        try {
            // JWT Token se authenticate karne ki koshish
            $franchise = JWTAuth::parseToken()->authenticate();

            if (!$franchise) {
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
        $otpSent = OtpHelper::generateAndSendOtps($franchise, $request->type);

        if (!$otpSent) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to generate OTP'
            ], 500);
        }

        return response()->json([
            'status'   => 'success',
            'message'  => 'A new OTP has been sent to your email for ' . ucfirst($request->type),
            'franchise' => [
                'id'    => $franchise->id,
                'name' => $franchise->name,
                'email' => $franchise->email,
            ]
        ], 200);
    }

    public function logout()
    {
        auth('franchise')->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out'
        ], 200);
    }
}
