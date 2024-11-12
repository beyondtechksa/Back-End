<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Category;
class CategoryParentReseorce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $category=Category::find($this->id);
        $parents_name=$category->translated_name;
        foreach(getAllParents($category) as $parent){
            $parents_name.='/'.$parent->translated_name;
        }
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'translated_name'=>$this->translated_name,
            'parents_name'=>$parents_name,
        ];
    }
}
