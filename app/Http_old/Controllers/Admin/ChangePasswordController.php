<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ChangePasswordController extends Controller
{
    public function index()
    {
        return view('admin.change-password.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, auth()->user()->password)) {
                    $fail('The current password does not match.');
                }
            }],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['success' => true, 'message' => 'Password changed successfully.']);
    }
}
