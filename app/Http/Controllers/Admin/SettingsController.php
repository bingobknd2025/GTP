<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Hamcrest\Core\Set;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function mainSettings()
    {
        $mainSettings = Setting::where('id', 1)->first();
        // dd($mainSettings);
        return view('admin.settings.main-settings', compact('mainSettings'));
    }

    public function preferenceSettings()
    {
        $mainSettings = Setting::where('id', 1)->first();
        return view('admin.settings.preference-settings', compact('mainSettings'));
    }

    public function update(Request $request)
    {
        $mainSettings = Setting::first();

        $data = $request->only([
            'website_name',
            'website_email',
            'website_contact',
        ]);

        // Enum checkboxes
        $data['website_under_maintenance'] = $request->has('website_under_maintenance') ? 'Live' : 'Maintenance';
        $data['enable_offline_kyc'] = $request->has('enable_offline_kyc') ? 'Yes' : 'No';
        $data['enable_online_kyc'] = $request->has('enable_online_kyc') ? 'Yes' : 'No';
        $data['min_deposit_amount'] = $request->has('min_deposit_amount') ? 'Yes' : 'No';
        $data['min_withdraw_amount'] = $request->has('min_withdraw_amount') ? 'Yes' : 'No';
        $data['mobile_under_maintenance'] = $request->has('mobile_under_maintenance') ? 'Live' : 'Maintenance';
        $data['display_franchise_user'] = $request->has('display_franchise_user') ? 'Yes' : 'No';
        $data['display_franchise_kyc'] = $request->has('display_franchise_kyc') ? 'Yes' : 'No';
        $data['allow_franchise_kyc_approve'] = $request->has('allow_franchise_kyc_approve') ? 'Yes' : 'No';
        $data['allow_franchise_kyc_add'] = $request->has('allow_franchise_kyc_add') ? 'Yes' : 'No';

        // Files
        if ($request->hasFile('fav_icon')) {
            $data['fav_icon'] = $request->file('fav_icon')->store('uploads/settings', 'public');
        }
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('uploads/settings', 'public');
        }

        $changes = [];
        foreach ($data as $key => $value) {
            if ($mainSettings->$key != $value) {
                $changes[$key] = [
                    'old' => $mainSettings->$key,
                    'new' => $value
                ];
            }
        }

        $mainSettings->update($data);

        return response()->json([
            'success' => true,
            'updated_fields' => array_keys($changes),
            'message' => 'Updated: ' . implode(', ', array_keys($changes))
        ]);
    }

    public function preferenceSettingsUpdate(Request $request)
    {
        // dd($request->all());
        $mainSettings = Setting::first();
        $data = $request->only([
            'mail_server',
            'mail_from_email',
            'mail_from_name',
            'smtp_host',
            'smtp_port',
            'smtp_encryption',
            'smtp_username',
            'smtp_password',
            'google_client_id',
            'google_client_secret',
            'google_redirect_url',
            'captcha_secret_key',
            'captcha_site_key',
        ]);

        $changes = [];
        foreach ($data as $key => $value) {
            if ($mainSettings->$key != $value) {
                $changes[$key] = [
                    'old' => $mainSettings->$key,
                    'new' => $value
                ];
            }
        }

        Setting::where('id', 1)->update($data);

        return response()->json([
            'success' => true,
            'updated_fields' => array_keys($changes),
            'message' => 'Updated: ' . implode(', ', array_keys($changes))
        ]);
    }
}
