<?php
namespace App\Services;

use App\Models\ReturnRequest;
use App\Models\ReturnItem;
use App\Models\ReturnStatus;
use Illuminate\Support\Facades\DB;

class ReturnRequestService
{

    public function createReturnRequest($data)
    {
        $return_status_id = ReturnStatus::first()?->id ?? null;
        return DB::transaction(function () use ($data,$return_status_id){
            $returnRequest = ReturnRequest::create([
                'order_id' => $data['order_id'],
                'user_id' => $data['user_id'],
                'status' => 'pending',
                'return_reason_id'=>$data['return_reason_id'],
                'reason' => $data['reason'] ?? null,
                'return_status_id' => $return_status_id,
                'image' => $data['image'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                ReturnItem::create([
                    'return_request_id' => $returnRequest->id,
                    'return_status_id' => $return_status_id,
                    'order_item_id' => $item,
                    'status' => 'pending',
                ]);
            }

            return $returnRequest->load('return_items');
        });
    }


    public function updateReturnRequestStatus($returnRequestId, $status)
    {
        $returnRequest = ReturnRequest::findOrFail($returnRequestId);
        $returnRequest->update(['status' => $status]);
        return $returnRequest;
    }

    public function updateReturnItemStatus($returnItemId, $status)
    {
        $returnItem = ReturnItem::findOrFail($returnItemId);
        $returnItem->update(['status' => $status]);
        return $returnItem;
    }
}
