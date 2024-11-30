<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;
use Auth;
class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    public function rootView(Request $request)
    {

        if ($request->is(['admin*', 'vendor*', 'shipping*'])) {
            return 'admin.app';
        }
        return 'app';
    }

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {

        // Determine if the request is for admin, vendor, or shipping
        $isAdminArea = $request->is(['admin*', 'vendor*', 'shipping*']);

        // Base shared props
        $shared = [
            'auth' => [
                'user' => $isAdminArea ? null : user(),
                'default_img' => '/assets/images/faces/1.jpg',
            ],
            // 'ziggy' => array_merge((new Ziggy)->toArray(), [
            //     'location' => $request->url(),
            //     'query' => $request->query()
            // ]),
            'messages' => [
                'success' => session('success'),
            ],
            'page_title' => session('page_title'),
            'no_header' => session('no_header'),
            'locale' => locale(),
            'locales' => locales(),
            'settings' => all_settings(),
            'currencies' => currencies(),
            'language' => function () {
                return json_decode(file_get_contents(resource_path('lang/' . app()->getLocale() . '.json')), true);
            },
            'general_setings'=>general_settings()
        ];

        if ($isAdminArea) {
            $guard = Auth::guard('admin')->check() ? 'admin' : (Auth::guard('vendor')->check() ? 'vendor' : 'shipping');
            $shared['auth'][$guard] = Auth::guard($guard)->check() ? $guard() : null;
            $shared['auth']['permissions'] = Auth::guard('admin')->check()
                ? admin()->getPermissionsViaRoles()->pluck('name')
                : [];
        }

        return array_merge(parent::share($request), $shared);

    }
}
