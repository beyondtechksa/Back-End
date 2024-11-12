<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function main(Order $order)
    {
        $dateRange = \request('range');
        $start_at = null;
        $end_at = null;
        if ($dateRange) {
            $dates = explode(' to ', $dateRange);
            if (count($dates) == 2)
                list($start_at, $end_at) = $dates;
        }
        $dateRangeCondition = function ($q) use ($start_at, $end_at) {
            if ($start_at && $end_at)
                $q->whereBetween(DB::raw('DATE(created_at)'), [$start_at, $end_at]);
        };
//        dd($start_at, $end_at);
// Sum of final selling price
        $sales = DB::table('order_items as o')
            ->when($start_at && $end_at, function ($q) use ($start_at, $end_at) {
                $q->whereBetween(DB::raw('DATE(o.created_at)'), [$start_at, $end_at]);
            })
            ->leftJoin('products as p', 'o.product_id', '=', 'p.id')
            ->sum('p.final_selling_price');
// New orders count with status 0
        $new_orders = $order
            ->when($start_at && $end_at, $dateRangeCondition)
            ->whereStatus(0)
            ->count();
// Done orders count with status 5
        $done_orders = $order
            ->when($start_at && $end_at, $dateRangeCondition)
            ->whereStatus(5)
            ->count();
// Revenue calculation
        $revenues = DB::table('order_items as o')
            ->when($start_at && $end_at, function ($q) use ($start_at, $end_at) {
                $q->whereBetween(DB::raw('DATE(o.created_at)'), [$start_at, $end_at]);
            })
            ->leftJoin('products as p', 'o.product_id', '=', 'p.id')
            ->select(DB::raw('SUM(p.final_selling_price - p.final_price) as total_revenue'))
            ->value('total_revenue');
// Total products count
        $products = DB::table('products')
            ->when($start_at && $end_at, $dateRangeCondition)
            ->count('id');
// Active products count
        $active_products = DB::table('products')
            ->when($start_at && $end_at, $dateRangeCondition)
            ->where('status', 1)
            ->count('id');

        $users_count = DB::table('users as u')
            ->when($start_at && $end_at, $dateRangeCondition)
            ->count();
        $users_orders = DB::table('orders as o')
            ->when($start_at && $end_at, $dateRangeCondition)
            ->groupBy('o.user_id')
            ->count('o.user_id');

        $users_percentage = round($users_orders / $users_count * 100, 2);
        return inertia('Reports/Main',
            compact('sales', 'new_orders',
                'done_orders', 'revenues', 'products', 'active_products', 'users_count', 'users_percentage'));
    }

    public function orders()
    {
        $orders = DB::table('orders as o')
            ->select(
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(CASE WHEN o.status NOT IN (1, 5) THEN 1 ELSE 0 END) as open_orders'),
                DB::raw('SUM(CASE WHEN o.status = 5 THEN 1 ELSE 0 END) as completed_orders'),
                DB::raw('SUM(CASE WHEN o.status = 1 THEN 1 ELSE 0 END) as canceled_orders')
            )
            ->first();
        $orders_count = $orders->total_orders;
        $open_orders = $orders->open_orders;
        $completed_orders = $orders->completed_orders;
        $canceled_orders = $orders->canceled_orders;
        $completed_percentage = $orders_count ? round(($completed_orders / $orders_count) * 100, 2) : 0;
        return inertia('Reports/Orders', compact('open_orders', 'canceled_orders',
            'completed_orders', 'completed_percentage'));
    }

    public function products()
    {
        $baseQuery = DB::table('products as p')
            ->leftJoin('order_items as oi', function ($join) {
                $join->on('oi.product_id', '=', 'p.id');
            })
            ->leftJoin('orders as o', 'oi.order_id', '=', 'o.id')
            ->whereNotNull('p.name_ar')
            ->whereNotNull('p.name_en')
            ->select(
                'p.id', 'p.visit', 'p.image', 'p.name_en', 'p.sku', 'p.name_ar', 'p.stock_status',
                DB::raw('SUM(CASE WHEN oi.product_id IS NOT NULL AND o.status != 2 THEN p.final_selling_price ELSE 0 END) as total_sell'),
                DB::raw('SUM(CASE WHEN oi.product_id IS NOT NULL AND o.status != 2 THEN (p.final_selling_price - p.final_price) ELSE 0 END) as total_revenue'),
                DB::raw('SUM(CASE WHEN oi.product_id IS NOT NULL AND o.status = 2 THEN 1 ELSE 0 END) as refund_count')
            )
            ->groupBy('p.id', 'p.name_en', 'p.name_ar');
        $finished = (clone $baseQuery)
            ->where('p.stock_status', 'out_of_stock')
            ->orderBy('p.id', 'desc')
            ->paginate(5);

        $visits = (clone $baseQuery)
            ->where('p.visit', '!=', 0)
            ->orderBy('p.visit', 'desc')
            ->limit(100)
            ->paginate(5);

        $productsAnalysis = $baseQuery
            ->orderBy('p.id', 'desc')
            ->paginate(5);
        return inertia('Reports/Products',
            ['productsAnalysis' => $productsAnalysis, 'finished' => $finished, 'visits' => $visits]);
    }

    public function carts()
    {
        $carts = DB::table('carts as c')
            ->leftJoin('products as p', 'c.product_id', '=', 'p.id')
            ->leftJoin('users as u', 'c.user_id', '=', 'u.id')
            ->select('p.name_ar',
                'p.name_en',
                'p.image',
                'p.sku',
                'p.visit',
                'c.product_id',
                'u.name AS user_name',
                DB::raw('SUM(c.quantity) AS totalQTY'),
                DB::raw('SUM(c.quantity * p.final_selling_price) AS total')
            )->groupBy('c.product_id');

        $avgCarts = clone $carts->get();
        $avgCarts = $avgCarts->avg('total');
        $percentage = clone $carts->get();
        $ignoredPercentage = $percentage->where('total', '<', $avgCarts)
                ->count() / $percentage->count() * 100;
        $ignoredPercentage = round($ignoredPercentage, 2);
        $cartsPagination = $carts->paginate(5);

        return inertia('Reports/Carts', ['carts' => $cartsPagination,
            'avgCarts' => $avgCarts, 'ignoredPercentage' => $ignoredPercentage]);
    }
}
