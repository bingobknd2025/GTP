<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Zone;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Spatie\Permission\Models\Role;

class UsersImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            // 1. Get Zone ID from zone name
            $zone = Zone::where('zone_name', $row['zone'])->first();

            $zone_id = $zone?->id;

            // 2. Create user
            $user = User::create([
                'company_name' => $row['companyname'] ?? '',
                'name'         => $row['username'] ?? '',
                'password'     => Hash::make($row['password'] ?? '12345678'),
                'email'        => $row['email'] ?? '',
                'mobile_no'    => $row['contactno'] ?? '',
                'address'      => $row['address'] ?? '',
                'city'         => $row['city'] ?? '',
                'state'        => $row['state'] ?? '',
                'pin_code'     => $row['pincode'] ?? '',
                'zone'         => $row['zone'],
                'zone_id'      => $zone_id,
                'role_id'      => $row['roleid'] ?? null,
                'gst_no'       => $row['gst'] ?? '',
                'pan_no'       => $row['pan'] ?? '',
            ]);

            // 3. Assign role using Spatie
            if (!empty($row['roleid'])) {
                $role = Role::find($row['roleid']);
                if ($role) {
                    $user->assignRole($role->name);
                }
            }
        }
    }
}
