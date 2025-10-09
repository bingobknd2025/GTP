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

class HomeController extends Controller
{
  public function index()
  {
    // Current and last month dates
    $currentMonthStart = Carbon::now()->startOfMonth();
    $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
    $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

    // Total customers (all time)
    $totalCustomers = Customer::count();

    // Customers registered this month
    $thisMonthCustomers = Customer::where('created_at', '>=', $currentMonthStart)->count();

    // Customers registered last month
    $lastMonthCustomers = Customer::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();

    // Calculate % change (handle divide by zero)
    if ($lastMonthCustomers > 0) {
      $growthPercentage = (($thisMonthCustomers - $lastMonthCustomers) / $lastMonthCustomers) * 100;
    } else {
      $growthPercentage = $thisMonthCustomers > 0 ? 100 : 0;
    }
    $activeCustomers = Customer::where('status', 'Approved')->count();
    $inactiveCustomers = Customer::where('status', 'Pending')->count();

    // ---- ORDER DATA ----
    $currentMonthStart = Carbon::now()->startOfMonth();
    $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
    $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

    // Total Orders (All Time)
    $totalOrders = Order::count();

    // Orders this month
    $thisMonthOrders = Order::where('created_at', '>=', $currentMonthStart)->count();

    // Orders last month
    $lastMonthOrders = Order::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();

    // % Growth Calculation
    if ($lastMonthOrders > 0) {
      $orderGrowth = (($thisMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100;
    } else {
      $orderGrowth = $thisMonthOrders > 0 ? 100 : 0;
    }

    // Order status counts
    $pendingOrders = Order::where('status', 'Pending')->count();
    $completedOrders = Order::where('status', 'Completed')->count();

    // ---- FRANCHISE DATA ----
    $currentMonthStart = Carbon::now()->startOfMonth();
    $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
    $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

    // Total franchises (all time)
    $totalFranchises = Franchise::count();

    // Franchises registered this month
    $thisMonthFranchises = Franchise::where('created_at', '>=', $currentMonthStart)->count();

    // Franchises registered last month
    $lastMonthFranchises = Franchise::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();

    // % Growth calculation (safe divide)
    if ($lastMonthFranchises > 0) {
      $franchiseGrowth = (($thisMonthFranchises - $lastMonthFranchises) / $lastMonthFranchises) * 100;
    } else {
      $franchiseGrowth = $thisMonthFranchises > 0 ? 100 : 0;
    }

    // Status counts
    $activeFranchises = Franchise::where('status', 'Approved')->count();
    $inactiveFranchises = Franchise::where('status', 'Pending')->count();

    $totalkyc = KYC::query()->count();
    $pendingkyc = KYC::query()->where('status', 'Pending')->count();

    // ---- KYC DATA ----
    $currentMonthStart = Carbon::now()->startOfMonth();
    $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
    $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

    // Total KYC (all time)
    $totalkyc = KYC::count();

    // KYC this month
    $thisMonthKYC = KYC::where('created_at', '>=', $currentMonthStart)->count();

    // KYC last month
    $lastMonthKYC = KYC::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();

    // % Growth Calculation
    if ($lastMonthKYC > 0) {
      $kycGrowth = (($thisMonthKYC - $lastMonthKYC) / $lastMonthKYC) * 100;
    } else {
      $kycGrowth = $thisMonthKYC > 0 ? 100 : 0;
    }

    // Status counts
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
      'USD' => "https://www.goldapi.io/api/XAU/USD"
    ];

    $goldRates = [];
    $errors = [];

    foreach ($urls as $currency => $url) {
      try {
        $response = Http::withHeaders([
          'x-access-token' => 'goldapi-r72pslvwlb57r-io',
        ])->get($url);

        if ($response->failed()) {
          $errors[$currency] = "API failed with status " . $response->status();
          continue;
        }

        $data = $response->json();

        if (!$data || !isset($data['price'])) {
          $errors[$currency] = "Invalid or empty response for {$currency}";
          continue;
        }

        // Check if record exists
        $goldRate = GoldRate::where('currency', $currency)->first();

        if ($goldRate) {
          // Update only existing record
          $goldRate->update([
            'live_price'       => $data['price'] ?? null,
            'price_gram_24k'   => $data['price_gram_24k'] ?? null,
            'price_gram_22k'   => $data['price_gram_22k'] ?? null,
            'price_gram_21k'   => $data['price_gram_21k'] ?? null,
            'price_gram_20k'   => $data['price_gram_20k'] ?? null,
            'price_gram_18k'   => $data['price_gram_18k'] ?? null,
            'price_gram_16k'   => $data['price_gram_16k'] ?? null,
            'price_gram_14k'   => $data['price_gram_14k'] ?? null,
            'price_gram_10k'   => $data['price_gram_10k'] ?? null,
            'fetched_at'       => Carbon::now()->format('Y-m-d H:i:00'),
          ]);
        } else {
          // Skip if not found
          $errors[$currency] = "Record not found for {$currency}, skipped update.";
          continue;
        }

        $goldRates[] = $goldRate->fresh();
      } catch (\Exception $e) {
        $errors[$currency] = "Exception: " . $e->getMessage();
      }
    }

    if (empty($goldRates)) {
      return response()->json([
        'status' => 'error',
        'message' => 'No gold rates updated.',
        'errors' => $errors
      ], 500);
    }

    return response()->json([
      'status' => 'success',
      'message' => 'Gold rates updated successfully!',
      'data' => $goldRates,
      'errors' => $errors
    ], 200);
  }
}
