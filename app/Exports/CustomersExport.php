<?php

namespace App\Exports;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CustomersExport implements FromView
{
    public function view(): View
    {
        $customers = User::with(['address'])->withCount(['orders','addresses'])->orderBy('id', 'desc')->get();
        return view('exports.customers',compact('customers'));
    }

}
