<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\AdminNotificationMail;
use App\Mail\WelcomeUserMail;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class CustomerAuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'mobile_no' => 'required|string|max:15|unique:customers',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $otp = rand(100000, 999999);

        $customer = Customer::create([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'email' => $request->email,
            'mobile_no' => $request->mobile_no,
            'password' => Hash::make($request->password),
            'customer_otp' => $otp, // column banana padega migration me
        ]);

        // ✅ Mail to Admin
        Mail::raw("Hello Admin,\n\nA new user has registered.\n\nName: {$customer->fname} {$customer->lname}\nEmail: {$customer->email}", function ($message) {
            $message->to('gtpaiiongold@gmail.com')
                ->subject('New Customer Registered');
        });

        // ✅ Mail to User with OTP
        Mail::raw("Hi {$customer->fname},\n\nWelcome to our platform! Your OTP is: {$otp}", function ($message) use ($customer) {
            $message->to($customer->email)
                ->subject('Welcome! Verify Your Email');
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Customer registered successfully. OTP sent to email.',
            'token' => auth('customer')->login($customer),
            'data' => $customer
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_otp' => 'required|numeric',
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }


        $customer = Customer::where('email', $request->email)->first();

        if (!$customer) {
            return response()->json(['status' => 'error', 'message' => 'Customer not found'], 404);
        }

        if ($customer->customer_otp == $request->otp) {
            $customer->customer_otp = null;
            $customer->email_verfied = true;
            $customer->save();

            return response()->json([
                'status' => 'success',
                'message' => 'OTP verified successfully'
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Invalid OTP'], 400);
    }


    public function profile(Request $request)
    {
        // Customer guard se user get karo
        $customer = auth('customer')->user();

        if (!$customer) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'status' => 'success',
            'customer' => $customer
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Customer ko validate ya fetch karna
        $customer = Customer::where('email', $credentials['email'])->first();

        if (!$customer || !Hash::check($credentials['password'], $customer->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // JWT token generate karna
        $token = JWTAuth::fromUser($customer);

        return response()->json([
            'status' => 'success',
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('customer')->factory()->getTTL() * 60
        ]);
    }


    public function logout()
    {
        auth('customer')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('customer')->factory()->getTTL() * 60
        ]);
    }
}
