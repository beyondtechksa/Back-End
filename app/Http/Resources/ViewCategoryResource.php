<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Category;
use App\Models\Poll;
use App\Models\Submitting;
class ViewCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $polls_ids=Poll::where('category_id',$this->id)->pluck('id');
        $submittings=Submitting::whereIn('poll_id',$polls_ids)->count();
        return [
            'id' => $this->id,
            'translated_name' => $this->translated_name,
            'icon' => $this->icon,
            'submittings_count' =>$submittings,
        ];
    }
}
