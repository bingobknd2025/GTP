<?php

// app/Helpers/OtpHelper.php
namespace App\Helpers;

use App\Models\Otp;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class OtpHelper
{
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

  public static function generateAndSendOtps($franchise, $type)
  {
    if (!$franchise || !isset($franchise->id)) {
      return false;
    }

    $otp = rand(100000, 999999);

    Otp::where('customer_id', $franchise->id)
      ->where('type', $type)
      ->delete();

    Otp::create([
      'customer_id' => $franchise->id,
      'type'        => $type,
      'otp'         => $otp,
      'expires_at'  => Carbon::now()->addMinutes(2)
    ]);

    Mail::send('emails.common_otp', [
      'otp'  => $otp,
      'type' => $type,
      'user' => $franchise
    ], function ($message) use ($franchise, $type) {
      $message->to($franchise->email, $franchise->fname)
        ->subject('Your OTP for ' . ucfirst($type));
    });

    return true;
  }

  public static function verifyOtp($customerId, $type, $otp)
  {
    $typeRecord = Otp::where('customer_id', $customerId)
      ->where('type', $type)
      ->first();

    if (!$typeRecord) {
      return [
        'status' => false,
        'message' => 'Invalid request type or no OTP generated for this type.'
      ];
    }

    $record = Otp::where('customer_id', $customerId)
      ->where('type', $type)
      ->where('otp', $otp)
      ->first();

    if (!$record) {
      return [
        'status' => false,
        'message' => 'Incorrect OTP. Please check and try again.'
      ];
    }

    if (Carbon::now()->greaterThan(Carbon::parse($record->expires_at))) {
      return [
        'status' => false,
        'message' => 'OTP has expired. Please request a new one.'
      ];
    }

    $record->delete();

    return [
      'status' => true,
      'message' => 'OTP verified successfully.'
    ];
  }
}
