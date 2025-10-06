<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Franchise;
use App\Models\Kyc;

class HomeController extends Controller
{
  public function index()
  {
    $customers = Customer::query();
    $totalCustomers = $customers->count();
    $activeCustomers = $customers->where('status', 'Approved')->count();
    $inactiveCustomers = $customers->where('status', 'Pending')->count();

    $orders = Order::query();
    $totalOrders = $orders->count();
    $pendingOrders = $orders->where('status', 'Pending')->count();

    $franchises = Franchise::query();
    $totalFranchises = $franchises->count();

    $activeFranchises = $franchises->where('status', 'Approved')->count();
    $inactiveFranchises = $franchises->where('status', 'Pending')->count();

    $totalkyc = KYC::query()->count();
    $pendingkyc = KYC::query()->where('status', 'Pending')->count();
    $totalRevenue = Order::sum('total_price');


    return view('admin.dashboard.index', compact(
      'totalCustomers',
      'activeCustomers',
      'inactiveCustomers',
      'totalOrders',
      'pendingOrders',
      'totalFranchises',
      'activeFranchises',
      'inactiveFranchises',
      'totalkyc',
      'pendingkyc',
      'totalRevenue'
    ));
  }
}
