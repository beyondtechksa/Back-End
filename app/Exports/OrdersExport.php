<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class OrdersExport implements FromView
{
    public function view(): View
    {
        $orders = DB::table('orders')
            ->leftJoin('users', 'users.id', '=', 'orders.user_id')
            ->select(
                'orders.id as order_id',
                'orders.user_id',
                'orders.subtotal_amount',
                'orders.shipping',
                'orders.discount',
                'orders.address',
                'orders.total_amount',
                DB::raw("CASE
                               WHEN orders.status = 0 THEN 'Pending'
                               WHEN orders.status = 1 THEN 'Completed'
                               WHEN orders.status = 2 THEN 'Refused'
                               ELSE 'Unknown'
                            END as order_status"),
                DB::raw("DATE(orders.created_at) as order_created_at"),
                'users.name as user_name',
            )
            ->orderBy('orders.id', 'DESC')
            ->get();
        return view('exports.orders', compact('orders'));
    }

}
