<?php

namespace App\Http\Controllers\Shipping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShippingAuthController extends Controller
{
    public function showLoginForm()
    {
        return inertia('Auth/ShippingLogin');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::guard('shipping')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            return redirect()->intended(route('shipping.dashboard'));
        }

        return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors(['password' => 'your password is not correct']);;
    }

    public function logout(Request $request)
    {
        Auth::guard('shipping')->logout();

        return redirect('/shipping/login');
    }
}
