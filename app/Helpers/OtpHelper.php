<?php

// app/Helpers/OtpHelper.php
namespace App\Helpers;

use App\Models\Otp;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class OtpHelper
{
  //   public static function generateAndSendOtp($customer, $type)
  //   {
  //     $otp = rand(100000, 999999);

  //     Otp::where('customer_id', $customer->id)
  //       ->where('type', $type)
  //       ->delete();

  //     Otp::create([
  //       'customer_id' => $customer->id,
  //       'type'        => $type,
  //       'otp'         => $otp,
  //       'expires_at'  => Carbon::now()->addMinutes(2)
  //     ]);

  //     Mail::send('emails.common_otp', [
  //       'otp'  => $otp,
  //       'type' => $type,
  //       'user' => $customer
  //     ], function ($message) use ($customer, $type) {
  //       $message->to($customer->email, $customer->fname)
  //         ->subject('Your OTP for ' . ucfirst($type));
  //     });

  //     return true;
  //   }

  public static function generateAndSendOtp($customer, $type)
  {
    // Guard: agar customer valid nahi hai
    if (!$customer || !isset($customer->id)) {
      return false;
    }

    $otp = rand(100000, 999999);

    Otp::where('customer_id', $customer->id)
      ->where('type', $type)
      ->delete();

    Otp::create([
      'customer_id' => $customer->id,
      'type'        => $type,
      'otp'         => $otp,
      'expires_at'  => Carbon::now()->addMinutes(2)
    ]);

    Mail::send('emails.common_otp', [
      'otp'  => $otp,
      'type' => $type,
      'user' => $customer
    ], function ($message) use ($customer, $type) {
      $message->to($customer->email, $customer->fname)
        ->subject('Your OTP for ' . ucfirst($type));
    });

    return true;
  }
}
