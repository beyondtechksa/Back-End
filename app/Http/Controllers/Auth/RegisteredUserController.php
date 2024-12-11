<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use App\Models\Cart;
use App\Notifications\CustomerNotifications;
use App\Notifications\OrderNotifications;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        event(new Registered($user));
        // $admin = Admin::first();

        // $admin->notify(new CustomerNotifications($user->id));
        Auth::login($user);

        if (isset($_COOKIE['user_cart'])) {
            $carts = json_decode($_COOKIE['user_cart']);
            foreach ($carts as $key=>$item) {
                if($item->product_id){
                    Cart::updateOrCreate([
                        'user_id' => $user->id,
                        'product_id' => $item->product_id,
                    ], [
                        'quantity' => $item->quantity,
                        'color' => $item->color,
                        'size' => $item->size
                    ]);

                }
            }
            setcookie('user_cart', null, 0, "/");
            $carts = Cart::with('product.brand')->where('user_id', $user->id)->get();
            
            return redirect('/cart')->with(['page_title' => __('Checkout Address'), 'carts' => $carts]);
        }

        user()->wallet()->create([
            'balance'=>0
        ]);

        return redirect(RouteServiceProvider::HOME);
    }
}
