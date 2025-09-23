<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\OtpHelper;
use App\Http\Controllers\Controller;
use App\Models\Franchise;
use App\Models\Kyc;
use App\Models\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'franchise' => [
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

        $otpData = Otp::where('franchise_id', $franchise->id)
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
                    'message' => 'franchise not found'
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

    public function getAccessToken(Request $request)
    {
        try {
            $franchise = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid or missing token'
            ], 401);
        }

        if ($franchise->account_verify == 'Verified') {
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
                'email'     => $franchise->email,
                'phone'     => $franchise->phone,
                'firstName' => $franchise->fname,
                'legalName' => $franchise->fname . ' ' . $franchise->lname,
                'countryOfBirth' => $franchise->country,
                'nationality'    => $franchise->country,
            ],
            'info' => [
                'firstName' => $franchise->fname,
                'lastName'  => $franchise->lname,
                'email'     => $franchise->email,
                'phone'     => $franchise->phone,
                'countryOfBirth' => $franchise->country,
                'nationality'    => $franchise->country
            ],
            'ttlInSecs'  => 600,
            'userId'     => 'APP_' . $franchise->id,
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

        $expiresAt  = $data['expiresAt'] ?? null;
        $expiresIn  = $expiresAt ? ($expiresAt - time()) : null;

        return response()->json([
            'access_token' => $data['token'] ?? null,
            'expires_in'   => $expiresIn,
            'raw_response' => $data
        ]);
    }

    public function handleWebhook(Request $request)
    {

        try {
            $franchise = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid or missing token'
            ], 401);
        }
        $id = $franchise->id;
        $status = $request->input('status');

        if ($id) {
            $user = franchise::find($id);

            if ($user) {
                // Update ya insert kyc
                $checkkyc = DB::table('kycs')->where([
                    'franchise_id' => $user->id,
                    'kyc_type'    => 'online'
                ])->first();

                if (!$checkkyc) {
                    $kycId = DB::table('kycs')->insertGetId([
                        'franchise_id'  => $user->id,
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
            $franchise = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid or missing token'
            ], 401);
        }

        $kyc = franchise::where('id', $franchise->id)->first();
        $kysstatus = Kyc::where('id', $kyc->kyc_id)->first();

        if (!$kyc) {
            return response()->json([
                'success' => false,
                'message' => 'franchise not found',
            ], 404);
        }


        if (!is_null($kysstatus)) {
            return response()->json([
                'success' => false,
                'message' => 'KYC already submitted',
                'status'  => $kysstatus->status ?? 'Null',
            ], 200);
        }

        return response()->json([
            'status' => true,
            'is_submit' => false,
            'message' => 'KYC not submitted yet, you can proceed',
        ], 200);
    }

    public function getCountry(Request $request)
    {
        $country = DB::table('countries')->orderBy('id', 'desc')->get();
        return response()->json([
            'status'  => 'success',
            'message' => 'Country get Successfully',
            'country' => $country
        ], 200);
    }

    public function submitIdentity(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name'      => 'required|string|max:255',
                'last_name'       => 'required|string|max:255',
                'dob'             => 'required|string|max:255',
                'identity_type'   => 'required|string|max:255|in:Aadhar,PAN,Passport,VoterID,DrivingLicense',
                'identity_number' => 'required|string|max:50',
                'frontimg'        => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'backimg'         => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Validation errors',
                    'errors'  => $validator->errors()
                ], 422);
            }

            // JWT authentication
            try {
                $franchise = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid or missing token'
                ], 401);
            }

            // file upload
            $frontPath = $request->file('frontimg')->store('kyc_docs', 'public');
            $backPath  = $request->hasFile('backimg')
                ? $request->file('backimg')->store('kyc_docs', 'public')
                : null;

            // save/update KYC
            $kyc = Kyc::updateOrCreate(
                ['franchise_id' => $franchise->id],
                [
                    'first_name'      => $request->first_name,
                    'last_name'       => $request->last_name,
                    'dob'             => $request->dob,
                    'identity_type'   => $request->identity_type,
                    'identity_number' => $request->identity_number,
                    'frontimg'        => $frontPath,
                    'backimg'         => $backPath,
                    'identity_status' => 'true',
                    'updated_by'      => $franchise->id,
                ]
            );

            // sirf updated fields response me bhejna
            $updatedData = [
                'first_name'      => $kyc->first_name,
                'last_name'       => $kyc->last_name,
                'dob'             => $kyc->dob,
                'identity_type'   => $kyc->identity_type,
                'identity_number' => $kyc->identity_number,
                'frontimg'        => $kyc->frontimg,
                'backimg'         => $kyc->backimg,
                'identity_status' => $kyc->identity_status,
            ];

            return response()->json([
                'status'  => true,
                'message' => 'Personal Identity submitted successfully.',
                'data'    => $updatedData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function submitResidentialAddress(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'country'   => 'required|string|max:255',
                'city'      => 'required|string|max:255',
                'address'   => 'required|string|max:500',
                'state'     => 'required|string|max:255',
                'zip_code'  => 'required|string|max:20',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Validation errors',
                    'errors'  => $validator->errors()
                ], 422);
            }

            // JWT authentication
            try {
                $franchise = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid or missing token'
                ], 401);
            }

            // save/update KYC address
            $kyc = Kyc::updateOrCreate(
                ['franchise_id' => $franchise->id],
                [
                    'country'             => $request->country,
                    'city'                => $request->city,
                    'address'             => $request->address,
                    'state'               => $request->state,
                    'zip_code'            => $request->zip_code,
                    'resi_address_status' => 'true',
                    'updated_by'          => $franchise->id,
                ]
            );

            // Sirf updated fields response me
            $updatedData = [
                'country'             => $kyc->country,
                'city'                => $kyc->city,
                'address'             => $kyc->address,
                'state'               => $kyc->state,
                'zip_code'            => $kyc->zip_code,
                'resi_address_status' => $kyc->resi_address_status,
            ];

            return response()->json([
                'status'  => true,
                'message' => 'Residential Address submitted successfully.',
                'data'    => $updatedData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function submitAddressProof(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'address_proof_type'  => 'required|in:Utility Bill,Rent Agreement,Bank Statement,Voter ID',
                'address_proof_file'  => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Validation errors',
                    'errors'  => $validator->errors()
                ], 422);
            }

            try {
                $franchise = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid or missing token'
                ], 401);
            }

            $filePath = null;
            if ($request->hasFile('address_proof_file')) {
                $filePath = $request->file('address_proof_file')->store('address_proofs', 'public');
            }

            $kyc = Kyc::updateOrCreate(
                ['franchise_id' => $franchise->id],
                [
                    'address_proof_type'  => $request->address_proof_type,
                    'address_proof_file'  => $filePath,
                    'address_veri_status' => 'true',
                    'updated_by'          => $franchise->id,
                ]
            );

            $updatedData = [
                'address_proof_type'  => $kyc->address_proof_type,
                'address_proof_file'  => $kyc->address_proof_file,
                'address_veri_status' => $kyc->address_veri_status,
            ];

            return response()->json([
                'status'  => true,
                'message' => 'Address Proof submitted successfully.',
                'data'    => $updatedData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function submitMobile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone_number' => 'required|string|max:15',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Validation errors',
                    'errors'  => $validator->errors()
                ], 422);
            }

            try {
                $franchise = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid or missing token'
                ], 401);
            }

            $kyc = Kyc::updateOrCreate(
                ['franchise_id' => $franchise->id],
                [
                    'phone_number'       => $request->phone_number,
                    'mobile_verified_at' => now(),
                    'mobile_status'      => 'true',
                    'updated_by'         => $franchise->id,
                ]
            );

            $updatedData = [
                'phone_number'       => $kyc->phone_number,
                'mobile_verified_at' => $kyc->mobile_verified_at,
                'mobile_status'      => $kyc->mobile_status,
            ];

            return response()->json([
                'status'  => true,
                'message' => 'Mobile number verified successfully.',
                'data'    => $updatedData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function finalSubmit(Request $request)
    {
        try {
            // JWT authentication
            try {
                $franchise = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid or missing token'
                ], 401);
            }

            // Fetch the franchise's KYC record
            $kyc = Kyc::where('franchise_id', $franchise->id)->first();

            if (!$kyc) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'KYC record not found for this user.'
                ], 404);
            }

            // Check if all required statuses are true
            $incompleteSteps = [];

            if ($kyc->mobile_status !== 'true') {
                $incompleteSteps[] = 'Mobile verification incomplete';
            }
            if ($kyc->identity_status !== 'true') {
                $incompleteSteps[] = 'Identity verification incomplete';
            }
            if ($kyc->resi_address_status !== 'true') {
                $incompleteSteps[] = 'Residential address verification incomplete';
            }
            if ($kyc->address_veri_status !== 'true') {
                $incompleteSteps[] = 'Address proof verification incomplete';
            }

            if (!empty($incompleteSteps)) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'KYC cannot be submitted. Please complete all steps.',
                    'details' => $incompleteSteps
                ], 422);
            }

            // Update final status
            $kyc->final_status = true;
            $kyc->status = 'Pending';
            $kyc->updated_by = $franchise->id;
            $kyc->save();

            // Response with all relevant data
            $responseData = [
                'status'       => $kyc->status,
                'final_status' => $kyc->final_status,
                'mobile_status' => $kyc->mobile_status,
                'address_veri_status' => $kyc->address_veri_status,
                'resi_address_status' => $kyc->resi_address_status,
                'identity_status' => $kyc->identity_status,
                'phone_number' => $kyc->phone_number,
                'mobile_verified_at' => $kyc->mobile_verified_at,
                'first_name'   => $kyc->first_name,
                'last_name'    => $kyc->last_name,
                'dob'          => $kyc->dob,
                'identity_type' => $kyc->identity_type,
                'identity_number' => $kyc->identity_number,
                'frontimg'     => $kyc->frontimg,
                'backimg'      => $kyc->backimg,
                'country'      => $kyc->country,
                'city'         => $kyc->city,
                'address'      => $kyc->address,
                'state'        => $kyc->state,
                'zip_code'     => $kyc->zip_code,
                'address_proof_type'  => $kyc->address_proof_type,
                'address_proof_file'  => $kyc->address_proof_file,
            ];

            return response()->json([
                'status'  => true,
                'message' => 'KYC final submission successful.',
                'data'    => $responseData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
