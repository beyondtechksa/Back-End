<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\CompanyRequest;

class OrderRequestExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public $checked;
    public function __construct(Array $checked){
        $this->checked=$checked;
    }

    public function collection()
    {
        session()->forget('checked');
        return CompanyRequest::with('user','order_item')->whereIn('id',$this->checked)->get();
    }

    public function map($company_request): array
    {
        return [
            $company_request->order_id,
            $company_request->company_product_id,
            $company_request->user?$company_request->user->name:null,
            $company_request->order_item->quantity,
            $company_request->order_item->size,
            $company_request->order_item->color,
            $company_request->created_at->format('d-m-Y H:i:s'),
        ];
    }

    public function headings(): array
    {
        return [
            'order id',
            'company product id',
            'user',
            'quantity',
            'size',
            'color',
            'Date',
   
        ];
    }
}
