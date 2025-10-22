<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Franchise;

class FranchiseAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('front.auth.franchise-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $attemptCredentials = [
            'email' => $request->email,
            'password' => $request->password,
            'status' => 1, // Only active franchises can log in
        ];

        if (Auth::guard('franchise')->attempt($attemptCredentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return response()->json(['success' => true, 'message' => 'Login successful!', 'redirect_url' => route('franchise.dashboard')]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid credentials or inactive account.'], 401);
    }

    public function logout(Request $request)
    {
        Auth::guard('franchise')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('franchise.login');
    }

    // You can add a dashboard method here for after login
    public function dashboard()
    {
        return view('front.franchise.dashboard'); // Assuming you'll create a dashboard for franchises
    }
}
