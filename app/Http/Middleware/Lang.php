<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Lang
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $lang = session('lang', 'ar');

        if (!in_array($lang, languages()->pluck('symbol')->toArray())) {
            $lang = 'ar';
            session(['lang' => 'ar']);
        }

        app()->setLocale($lang);

        if ($request->has('redirect_url')) {
            $redirectUrl = $request->query('redirect_url');

            if (filter_var($redirectUrl, FILTER_VALIDATE_URL) && $redirectUrl !== $request->fullUrl()) {
                return redirect()->away($redirectUrl);
            }
        }



        return $next($request);
    }
}
