<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Cart;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {

        $request->authenticate();

        $request->session()->regenerate();
        if (isset($_COOKIE['user_cart'])) {
            $carts = json_decode($_COOKIE['user_cart']);
            foreach ($carts as $key=>$item) {
                if($item->product_id){
                    Cart::updateOrCreate([
                        'user_id' => Auth::id(),
                        'product_id' => $item->product_id,
                    ], [
                        'quantity' => $item->quantity,
                        'color' => $item->color??null,
                        'size' => $item->size??null,
                    ]);
                }
                
            }
            setcookie('user_cart', null, 0, "/");
            $carts = Cart::with('product.brand')->where('user_id', user()->id)->get();

            return redirect('/cart')->with(['page_title' => __('Checkout Address'), 'carts' => $carts]);
        }
        if (!user()->wallet()->exists()) {
            user()->wallet()->create([
                'balance' => 0
            ]);
        }
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
