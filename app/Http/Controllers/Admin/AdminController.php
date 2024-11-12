<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Newsletters;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use App\Models\Category;
use App\Http\Resources\ViewCategoryResource;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->page_name = 'profile';
    }

    public function dashboard()
    {
        $sell = DB::table('orders')
            ->sum('total_amount');
        $total_sell = number_format($sell, 2);

        $total_products = DB::table('products')->count();
        $total_users = DB::table('users')->count();
        $total_orders = DB::table('orders')->count();
        $orders = DB::table('orders as o')
            ->leftJoin('users as u', 'o.user_id', '=', 'u.id')
            ->select('o.id', 'o.subtotal_amount',
                'o.discount', 'o.shipping', 'o.total_amount', 'o.status', 'u.name', 'o.created_at')
            ->limit(5)
            ->get();
        $year = date('Y');
        $orderChart = $this->orderChart($year);
        $customerChart = $this->customerChart($year);
        return inertia('Dashboard', compact('total_orders'
            , 'total_users', 'total_sell', 'total_products', 'orders'
            ,'orderChart','customerChart','year'))
            ->with(['page_name' => 'dashboard']);
    }

    public function customerChart($year)
    {
        $monthlyOrderCounts = DB::table('users as u')
            ->select(DB::raw('MONTH(u.created_at) as month'), DB::raw('COUNT(*) as user_count'))
            ->whereYear('u.created_at', $year)
            ->groupBy(DB::raw('MONTH(u.created_at)'))
            ->orderBy(DB::raw('MONTH(u.created_at)'))
            ->get();
        $monthlyUserArray = array_fill(1, 12, 0);
        foreach ($monthlyOrderCounts as $count) {
            $monthlyUserArray[$count->month] = $count->user_count;
        }
        return $monthlyUserArray;
    }
    public function orderChart($year)
    {
        $monthlyOrderCounts = DB::table('orders as o')
            ->select(DB::raw('MONTH(o.created_at) as month'), DB::raw('COUNT(*) as order_count'))
            ->whereYear('o.created_at', $year)
            ->groupBy(DB::raw('MONTH(o.created_at)'))
            ->orderBy(DB::raw('MONTH(o.created_at)'))
            ->get();
        $monthlyOrderArray = array_fill(1, 12, 0);
        foreach ($monthlyOrderCounts as $count) {
            $monthlyOrderArray[$count->month] = $count->order_count;
        }
        return $monthlyOrderArray;
    }
    public function login()
    {

        if (auth()->guard('admin')->check()) {
            return redirect('/admin');
        } else {
            return inertia('Auth/Login');
        }
    }

    public function dologin()
    {
        $this->validate(request(), [
            'email' => 'required|email|exists:admins,email',
            'password' => 'required|string'
        ]);
        $remember = request('remember') == 1 ? true : false;
        if (auth()->guard('admin')->attempt(['email' => request('email'), 'password' => request('password')], $remember)) {
            return redirect('admin');
        } else {

            return redirect('admin/login')->withErrors(['password' => 'your password is not correct']);
        }
    }

    public function logout()
    {
        auth()->guard('admin')->logout();
        return redirect('admin/login');
    }


    public function profile()
    {
        return inertia('Profile')->with(['page_name' => $this->page_name]);
    }

    public function profile_update(Request $request)
    {
        $admin = admin();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,' . $admin->id,
            'avatar' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048|dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000',
        ]);
        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];
        if ($request->avatar) {
            \File::delete(get_main_patch($admin->avatar));
            $data['avatar'] = uploadImage($request->file('avatar'), 'admins');
        }
        $admin->update($data);
        return redirect()->back()->with('success', 'item updated successfully!');
    }

    public function password_update(Request $request)
    {
        $admin = admin();
        $request->validate([
            'current_password' => ['required', Rules\Password::defaults()],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        if (!Hash::check($request->current_password, $admin->password)) {
            return redirect()->back()->withErrors(['current_password' => 'the current password is not correct!']);
        }
        $data = [];
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }
        $admin->update($data);
        return redirect()->back()->with('success', 'item updated successfully!');
    }

    public function subscribes()
    {
        $subscribes = Newsletters::latest()->paginate(15);
        return inertia('Subscribes/Index', ['subscribes' => $subscribes])->with(['page_name' => __('subscribes')]);

    }

    public function admin_permissions()
    {
        return \Auth::guard('admin')->check() ? admin()->getPermissionsViaRoles()->pluck('name') : [];
    }

    public function notifications()
    {
        $data['notifications'] = admin()->unReadNotifications;
        $data['unread'] = count(admin()->unReadNotifications);
        return returnSuccess('data', $data, 'success');
    }

    public function markReadNotification($id)
    {
        $notification = admin()->notifications()->find($id);
        $notification->markAsRead();
        return response()->json(['success' => 'Notification marked as read.', 'notification' => $notification]);
    }
    public function getNotifications()
    {
        $notifications = admin()->notifications()->paginate(10);
        return inertia('Notifications/Index', ['notifications' => $notifications])->with(['page_name' => __('notifications')]);
    }
}
