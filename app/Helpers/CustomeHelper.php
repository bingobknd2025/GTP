<?php

namespace App\Helpers;

use App\Models\CustomerActivityLog;
use App\Models\FranchiseActivityLog;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Request;
use Jenssegers\Agent\Agent;

class CustomeHelper
{
  public static function logAdminActivity(int $userId, $details = null)
  {
    $agent = new Agent();
    $ip = request()->ip();
    $route = Request::fullUrl();

    $device  = $agent->device();
    $browser = $agent->browser();
    $os      = $agent->platform();

    if (is_array($details) || is_object($details)) {
      $details = json_encode($details, JSON_UNESCAPED_UNICODE);
    }

    return UserActivity::create([
      'user_id'    => $userId,
      'type'       => $route,
      'details'    => $details,
      'ip_address' => $ip,
      'device'     => $device,
      'browser'    => $browser,
      'os'         => $os,
    ]);
  }

  public static function logCustomerActivity($customerId, $message)
  {
    $fullUrl = Request::fullUrl();
    $ip  = request()->ip();

    return CustomerActivityLog::create([
      'customer_id' => $customerId,
      'message'     => $message,
      'type'        => $fullUrl,
      'ip_address'  => $ip,
    ]);
  }

  public static function logFranchiseActivity($franchiseId, $message)
  {
    $fullUrl = Request::fullUrl();
    $ip  = request()->ip();

    return FranchiseActivityLog::create([
      'franchise_id' => $franchiseId,
      'message'      => $message,
      'type'         => $fullUrl,
      'ip_address'   => $ip,
    ]);
  }
}
