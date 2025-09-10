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
        $customer = Auth::guard('customer');
        $request->validate([
            'otp' => 'required|numeric'
        ]);

        // Token se user identify karo

        if (!$customer) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        // OTP check
        if ($customer->otp == $request->otp) {
            $customer->otp = null; // OTP expire
            $customer->email_verified_at = now();
            $customer->save();

            return response()->json([
                'status' => 'success',
                'message' => 'OTP verified successfully'
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Invalid OTP'], 400);
    }



    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (! $token = auth('customer')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function profile(Request $request)
    {
        $token = $request->bearerToken();
        if (!$token || !JWTAuth::setToken($token)->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $accessToken = JWTAuth::setToken($token)->getPayload();
        if (!$accessToken) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = $accessToken->get('sub');
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $data = Customer::where('id', $user)->first();
        return response()->json([
            'status' => 'success',
            'customer' => $data
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
