<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Franchise;
use App\Models\Kyc;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Models\GoldRate;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
  public function mainSetting()
  {
    $settings = Setting::first();

    if (!$settings) {
      return response()->json([
        'status' => 'error',
        'message' => 'Settings not found',
        'data' => []
      ], 404);
    }

    return response()->json([
      'status' => 'success',
      'message' => 'Main Settings fetched successfully',
      'data' => $settings
    ]);
  }

  public function index()
  {
    $currentMonthStart = Carbon::now()->startOfMonth();
    $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
    $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

    $totalCustomers = Customer::count();

    $thisMonthCustomers = Customer::where('created_at', '>=', $currentMonthStart)->count();

    $lastMonthCustomers = Customer::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();

    if ($lastMonthCustomers > 0) {
      $growthPercentage = (($thisMonthCustomers - $lastMonthCustomers) / $lastMonthCustomers) * 100;
    } else {
      $growthPercentage = $thisMonthCustomers > 0 ? 100 : 0;
    }
    $activeCustomers = Customer::where('status', 'Approved')->count();
    $inactiveCustomers = Customer::where('status', 'Pending')->count();

    $currentMonthStart = Carbon::now()->startOfMonth();
    $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
    $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

    $totalOrders = Order::count();

    $thisMonthOrders = Order::where('created_at', '>=', $currentMonthStart)->count();

    $lastMonthOrders = Order::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();

    if ($lastMonthOrders > 0) {
      $orderGrowth = (($thisMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100;
    } else {
      $orderGrowth = $thisMonthOrders > 0 ? 100 : 0;
    }

    $pendingOrders = Order::where('status', 'Pending')->count();
    $completedOrders = Order::where('status', 'Completed')->count();

    $currentMonthStart = Carbon::now()->startOfMonth();
    $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
    $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

    $totalFranchises = Franchise::count();

    $thisMonthFranchises = Franchise::where('created_at', '>=', $currentMonthStart)->count();

    $lastMonthFranchises = Franchise::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();

    if ($lastMonthFranchises > 0) {
      $franchiseGrowth = (($thisMonthFranchises - $lastMonthFranchises) / $lastMonthFranchises) * 100;
    } else {
      $franchiseGrowth = $thisMonthFranchises > 0 ? 100 : 0;
    }

    $activeFranchises = Franchise::where('status', 'Approved')->count();
    $inactiveFranchises = Franchise::where('status', 'Pending')->count();

    $totalkyc = KYC::query()->count();
    $pendingkyc = KYC::query()->where('status', 'Pending')->count();

    $currentMonthStart = Carbon::now()->startOfMonth();
    $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
    $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

    $totalkyc = KYC::count();

    $thisMonthKYC = KYC::where('created_at', '>=', $currentMonthStart)->count();

    $lastMonthKYC = KYC::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();

    if ($lastMonthKYC > 0) {
      $kycGrowth = (($thisMonthKYC - $lastMonthKYC) / $lastMonthKYC) * 100;
    } else {
      $kycGrowth = $thisMonthKYC > 0 ? 100 : 0;
    }

    $pendingkyc = KYC::where('status', 'Pending')->count();
    $approvedKYC = KYC::where('status', 'Approved')->count();
    $totalRevenue = Order::sum('total_price');


    return view('admin.dashboard.index', compact(
      'totalCustomers',
      'activeCustomers',
      'inactiveCustomers',
      'growthPercentage',
      'totalOrders',
      'pendingOrders',
      'completedOrders',
      'orderGrowth',
      'totalFranchises',
      'activeFranchises',
      'inactiveFranchises',
      'franchiseGrowth',
      'totalkyc',
      'pendingkyc',
      'approvedKYC',
      'kycGrowth',
      'totalRevenue'
    ));
  }

  public function getGoldprice()
  {
    $urls = [
      'INR' => "https://www.goldapi.io/api/XAU/INR",
      'USD' => "https://www.goldapi.io/api/XAU/USD",
    ];

    $goldRates = [];
    $errors = [];

    foreach ($urls as $currency => $url) {
      try {
        $response = Http::withHeaders([
          'x-access-token' => 'goldapi-r72pslvwlb57r-io',
        ])->get($url);

        $data = $response->json();

        $goldRate = GoldRate::firstOrCreate(['currency' => $currency]);
        if ($response->failed() || !$data || !isset($data['price'])) {
          $errors[$currency] = $response->failed()
            ? "API failed with status " . $response->status()
            : "Invalid response, showing previous data.";

          $goldRates[] = $goldRate;
          continue;
        }

        $goldRate->update([
          'live_price'       => $data['price'] ?? $goldRate->live_price,
          'price_gram_24k'   => $data['price_gram_24k'] ?? $goldRate->price_gram_24k,
          'price_gram_22k'   => $data['price_gram_22k'] ?? $goldRate->price_gram_22k,
          'price_gram_21k'   => $data['price_gram_21k'] ?? $goldRate->price_gram_21k,
          'price_gram_20k'   => $data['price_gram_20k'] ?? $goldRate->price_gram_20k,
          'price_gram_18k'   => $data['price_gram_18k'] ?? $goldRate->price_gram_18k,
          'price_gram_16k'   => $data['price_gram_16k'] ?? $goldRate->price_gram_16k,
          'price_gram_14k'   => $data['price_gram_14k'] ?? $goldRate->price_gram_14k,
          'price_gram_10k'   => $data['price_gram_10k'] ?? $goldRate->price_gram_10k,
          'fetched_at'       => now()->format('Y-m-d H:i:00'),
        ]);
        $goldRates[] = $goldRate->fresh();
      } catch (\Exception $e) {
        $errors[$currency] = "Exception: " . $e->getMessage();
        $goldRate = GoldRate::where('currency', $currency)->first();
        if ($goldRate) {
          $goldRates[] = $goldRate;
        }
      }
    }

    return response()->json([
      'status' => 'success',
      'message' => 'Gold rates fetched successfully!',
      'data' => $goldRates,
      'errors' => $errors
    ], 200);
  }

  public function termsAndConditions()
  {
    $policy = DB::table('policies')->where('id', 1)->first();

    if (!$policy || !$policy->terms_conditions) {
      return response()->json([
        'status' => 'error',
        'message' => 'Terms & Conditions not found',
        'data' => []
      ], 404);
    }

    $decodedPolicy = json_decode($policy->terms_conditions, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
      return response()->json([
        'status' => 'error',
        'message' => 'Invalid Terms & Conditions JSON format',
        'data' => []
      ], 500);
    }

    return response()->json([
      'status' => 'success',
      'message' => 'Terms & Conditions Policies',
      'data' => $decodedPolicy
    ]);
  }

  public function privacyPolicy()
  {
    $policy = DB::table('policies')->where('id', 1)->first();

    if (!$policy || !$policy->privacy_policy) {
      return response()->json([
        'status' => 'error',
        'message' => 'Privacy Policy not found',
        'data' => []
      ], 404);
    }

    $decodedPolicy = json_decode($policy->privacy_policy, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
      return response()->json([
        'status' => 'error',
        'message' => 'Invalid privacy policy JSON format',
        'data' => []
      ], 500);
    }

    return response()->json([
      'status' => 'success',
      'message' => 'Privacy Policy',
      'data' => $decodedPolicy
    ]);
  }

  public function refundPolicy()
  {
    $policy = DB::table('policies')->where('id', 1)->first();

    if (!$policy || !$policy->refund_policy) {
      return response()->json([
        'status' => 'error',
        'message' => 'Refund Policy not found',
        'data' => []
      ], 404);
    }

    $decodedPolicy = json_decode($policy->refund_policy, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
      return response()->json([
        'status' => 'error',
        'message' => 'Invalid refund policy JSON format',
        'data' => []
      ], 500);
    }

    return response()->json([
      'status' => 'success',
      'message' => 'Refund Policy',
      'data' => $decodedPolicy
    ]);
  }

  public function amlPolicy()
  {
    $policy = DB::table('policies')->where('id', 1)->first();

    if (!$policy) {
      return response()->json([
        'status' => 'error',
        'message' => 'Policy record not found',
        'data' => []
      ], 404);
    }
    $policyField = 'anti_money_laundering';
    if (!property_exists($policy, $policyField) || empty($policy->$policyField)) {
      return response()->json([
        'status' => 'error',
        'message' => 'Policy content not found in record',
        'data' => []
      ], 404);
    }
    $jsonString = $policy->$policyField;



    $decodedPolicy = json_decode($policy->$policyField, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
      return response()->json([
        'status' => 'error',
        'message' => 'Invalid Policy JSON format: ' . json_last_error_msg(),
        'data' => []
      ], 500);
    }

    return response()->json([
      'status' => 'success',
      'message' => 'Anti Money Laundering Policy',
      'data' => $decodedPolicy
    ]);
  }

  public function grievancePolicy()
  {
    $policy = DB::table('policies')->where('id', 1)->first();

    if (!$policy || !$policy->grievance_redressal_policy) {
      return response()->json([
        'status' => 'error',
        'message' => 'Grievance Redressal Policy not found',
        'data' => []
      ], 404);
    }
    $decodedPolicy = json_decode($policy->grievance_redressal_policy, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
      return response()->json([
        'status' => 'error',
        'message' => 'Invalid Grievance Redressal Policy JSON format',
        'data' => []
      ], 500);
    }


    return response()->json([
      'status' => 'success',
      'message' => 'Grievance Redressal Policy',
      'data' => $decodedPolicy
    ]);
  }

  public function affiliatePolicy()
  {
    $policy = DB::table('policies')->where('id', 1)->first();

    if (!$policy || !$policy->affiliate_policy) {
      return response()->json([
        'status' => 'error',
        'message' => 'Affiliate Policy not found',
        'data' => []
      ], 404);
    }
    $decodedPolicy = json_decode($policy->affiliate_policy, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
      return response()->json([
        'status' => 'error',
        'message' => 'Invalid Affiliate Policy JSON format',
        'data' => []
      ], 500);
    }


    return response()->json([
      'status' => 'success',
      'message' => 'Affiliate Policy',
      'data' => $decodedPolicy
    ]);
  }
}
