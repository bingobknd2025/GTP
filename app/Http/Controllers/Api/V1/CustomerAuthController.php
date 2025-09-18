<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\OtpHelper;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Kyc;
use App\Models\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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

    public function getAccessToken(Request $request)
    {
        try {
            $customer = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid or missing token'
            ], 401);
        }

        if ($customer->account_verify == 'Verified') {
            return response()->json(['error' => 'Already verified'], 403);
        }

        $appToken   = env('SUMSUB_APP_TOKEN');
        $secretKey  = env('SUMSUB_SECRET_KEY');
        $baseUrl    = env('SUMSUB_BASE_URL', 'https://api.sumsub.com');
        $levelName  = env('SUMSUB_LEVEL_NAME', 'test-level-kyc');

        $path   = '/resources/accessTokens/sdk';
        $method = 'POST';

        $bodyArr = [
            'applicantIdentifiers' => [
                'email'     => $customer->email,
                'phone'     => $customer->phone,
                'firstName' => $customer->fname,
                'legalName' => $customer->fname . ' ' . $customer->lname,
                'countryOfBirth' => $customer->country,
                'nationality'    => $customer->country,
            ],
            'info' => [
                'firstName' => $customer->fname,
                'lastName'  => $customer->lname,
                'email'     => $customer->email,
                'phone'     => $customer->phone,
                'countryOfBirth' => $customer->country,
                'nationality'    => $customer->country
            ],
            'ttlInSecs'  => 600,
            'userId'     => 'APP_' . $customer->id,
            'levelName'  => $levelName,
        ];

        $body = json_encode($bodyArr);
        $ts   = time();
        $signature = hash_hmac('sha256', $ts . strtoupper($method) . $path . $body, $secretKey);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $baseUrl . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_POSTFIELDS     => $body,
            CURLOPT_HTTPHEADER     => [
                "X-App-Token: $appToken",
                "X-App-Access-Ts: $ts",
                "X-App-Access-Sig: $signature",
                "Content-Type: application/json"
            ],
            CURLOPT_TIMEOUT        => 10,
        ]);

        $resp = curl_exec($ch);
        $err  = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return response()->json(['error' => $err], 500);
        }

        $data = json_decode($resp, true);

        // 👇 expires_in calculate karo
        $expiresAt  = $data['expiresAt'] ?? null;
        $expiresIn  = $expiresAt ? ($expiresAt - time()) : null;

        return response()->json([
            'access_token' => $data['token'] ?? null,
            'expires_in'   => $expiresIn,
            'raw_response' => $data   // debug ke liye (remove in prod)
        ]);
    }


    // public function handleWebhook(Request $request)
    // {
    //     $rawBody         = $request->getContent();
    //     $headerSignature = $request->header('X-Sumsub-Signature') ?? $request->header('X-Signature');
    //     $webhookSecret   = env('SUMSUB_WEBHOOK_SECRET');

    //     if ($webhookSecret && $headerSignature) {
    //         $calculated = hash_hmac('sha256', $rawBody, $webhookSecret);
    //         if (!hash_equals($calculated, $headerSignature)) {
    //             return response('Invalid signature', 403);
    //         }
    //     }

    //     $payload = $request->json()->all();

    //     $event         = $payload['eventType'] ?? ($payload['type'] ?? null);
    //     $externalUserId = $payload['payload']['externalUserId'] ?? $payload['externalUserId'] ?? null;
    //     $reviewResult  = $payload['payload']['reviewResult'] ?? ($payload['reviewResult'] ?? null);

    //     $statusToSet = 'Under review';
    //     if ($reviewResult) {
    //         $decision = is_array($reviewResult) ? ($reviewResult['finalDecision'] ?? $reviewResult['reviewStatus'] ?? null) : $reviewResult;
    //         if (in_array(strtoupper($decision), ['GREEN', 'APPROVED', 'SUCCESS'])) {
    //             $statusToSet = '1';
    //         } elseif (in_array(strtoupper($decision), ['RED', 'DENIED', 'REJECTED'])) {
    //             $statusToSet = '2';
    //         }
    //     } elseif ($event) {
    //         if (str_contains(strtolower($event), 'approved') || str_contains(strtolower($event), 'verified')) {
    //             $statusToSet = '1';
    //         } elseif (str_contains(strtolower($event), 'rejected') || str_contains(strtolower($event), 'denied')) {
    //             $statusToSet = '2';
    //         }
    //     }

    //     $id = null;
    //     if ($externalUserId && preg_match('/(\d+)$/', $externalUserId, $m)) {
    //         $id = intval($m[1]);
    //     }

    //     if ($id) {
    //         $user = Customer::find($id); // ya Customer model
    //         if ($user) {
    //             $checkkyc = DB::table('kycs')->where([
    //                 'customer_id' => $user->id,
    //                 'kyc_type'    => 'online'
    //             ])->first();

    //             if (!$checkkyc) {
    //                 $kycId = DB::table('kycs')->insertGetId([
    //                     'customer_id'  => $user->id,
    //                     'first_name'   => $user->fname ?? '',
    //                     'last_name'    => $user->lname ?? '',
    //                     'email'        => $user->email,
    //                     'country_code' => $user->country_code ?? '',
    //                     'phone_number' => $user->phone ?? '',
    //                     'country'      => $user->country ?? '',
    //                     'status'       => $statusToSet,
    //                     'kyc_type'     => 'online',
    //                     'created_by'   => $user->id,
    //                     'updated_by'   => $user->id,
    //                     'source'       => 'WEB',
    //                     'created_at'   => now(),
    //                     'updated_at'   => now(),
    //                 ]);
    //                 $user->kyc_id = $kycId;
    //             } else {
    //                 DB::table('kycs')->where('id', $checkkyc->id)->update([
    //                     'status'     => $statusToSet,
    //                     'updated_at' => now(),
    //                 ]);
    //             }

    //             $user->email_verfied = $statusToSet;
    //             $user->save();

    //             $subject = "KYC Status Updated.";
    //             $message = $statusToSet == 'Verified'
    //                 ? "Your KYC successfully completed. Now you can deposit and buy plans."
    //                 : "Your KYC is under review / rejected. Please contact support.";

    //             Mail::send([], [], function ($m) use ($user, $subject, $message) {
    //                 $m->to($user->email, $user->fname)
    //                     ->subject($subject)
    //                     ->setBody($message, 'text/plain') // plain text body
    //                     ->from(config('mail.from.address'), config('mail.from.name'));
    //             });
    //         }
    //     }

    //     return response()->json(['ok' => true]);
    // }

    public function handleWebhook(Request $request)
    {

        try {
            $customer = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid or missing token'
            ], 401);
        }
        $id = $customer->id;
        $status = $request->input('status');

        if ($id) {
            $user = Customer::find($id);

            if ($user) {
                // Update ya insert kyc
                $checkkyc = DB::table('kycs')->where([
                    'customer_id' => $user->id,
                    'kyc_type'    => 'online'
                ])->first();

                if (!$checkkyc) {
                    $kycId = DB::table('kycs')->insertGetId([
                        'customer_id'  => $user->id,
                        'first_name'   => $user->fname ?? '',
                        'last_name'    => $user->lname ?? '',
                        'email'        => $user->email,
                        'country_code' => $user->country_code ?? '',
                        'phone_number' => $user->phone ?? '',
                        'country'      => $user->country ?? '',
                        'status'       => $status,
                        'kyc_type'     => 'online',
                        'created_by'   => $user->id,
                        'updated_by'   => $user->id,
                        'source'       => 'WEB',
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ]);
                    $user->kyc_id = $kycId;
                } else {
                    DB::table('kycs')->where('id', $checkkyc->id)->update([
                        'status'     => $status,
                        'updated_at' => now(),
                    ]);
                }

                // User update
                $user->account_verify = $status;
                $user->save();

                // Mail bhejna
                $subject = "KYC Status Updated.";
                $message = $status == '1'
                    ? "Your KYC successfully completed. Now you can deposit and buy plans."
                    : "Your KYC is under review / rejected. Please contact support.";

                Mail::raw($message, function ($m) use ($user, $subject) {
                    $m->to($user->email, $user->fname)
                        ->subject($subject)
                        ->from(config('mail.from.address'), config('mail.from.name'));
                });
            }
        }

        return response()->json(['ok' => true]);
    }

    public function kycstatus(Request $request)
    {
        try {
            $customer = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid or missing token'
            ], 401);
        }

        $kyc = Customer::where('id', $customer->id)->first();
        $kysstatus = Kyc::where('id', $kyc->kyc_id)->first();

        if (!$kyc) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found',
            ], 404);
        }


        if (!is_null($kysstatus)) {
            return response()->json([
                'success' => false,
                'message' => 'KYC already submitted',
                'status'  => $kysstatus->status ?? 'Null', // use your actual status column
            ], 200);
        }

        // If KYC not submitted, allow further flow
        return response()->json([
            'status' => true,
            'is_submit' => false,
            'message' => 'KYC not submitted yet, you can proceed',
        ], 200);
    }
}
