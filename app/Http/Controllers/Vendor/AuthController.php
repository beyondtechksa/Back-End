<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return inertia('Auth/VendorLogin');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::guard('vendor')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            return redirect()->intended(route('vendor.dashboard'));
        }

        return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors(['password' => 'your password is not correct']);;
    }

    public function logout(Request $request)
    {
        Auth::guard('vendor')->logout();

        return redirect('/vendor/login');
    }
}
