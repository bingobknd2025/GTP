<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'create products',
            'view products',
            'edit products',
            'delete products',
            'Franchise List',
            'Franchise Add',
            'Franchise Edit',
            'Franchise View',
            'Franchise Delete',
            'Change Password',
            'Customer List',
            'Customer Add',
            'Customer Edit',
            'Customer View',
            'Customer Delete',
            'KYC List',
            'KYC Add',
            'KYC Edit',
            'KYC View',
            'KYC Delete',
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create role and assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Assign role to user ID 1
        $user = User::find(1);
        if ($user) {
            $user->assignRole('admin');
        }
    }
}
