<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Step;
class PollResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'translated_name' => $this->translated_name,
            'name' => $this->name,
            'slug' => $this->slug,
            'translated_desc' => $this->translated_desc,
            'desc' => $this->desc,
            'image' => $this->image,
            'color' => $this->color,
            'status' => $this->status,
            'steps' => Step::where('poll_id',$this->id)->orderBy('list','asc')->get(),
            'created_at' => $this->created_at->todatestring(),
        ];
    }
}
