<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'imageUrl' => $this->getImageUrl(),
            'title' => $this->title,
            'services' => ServiceResource::collection($this->whenLoaded('services')),
            'slug' => $this->when($this->slug,$this->slug),
            'summary' => $this->when($this->summary,$this->summary),
            'description' => $this->when($this->description,$this->description),
            'location' => $this->location,
            'date' => $this->date,
            'created_at_formated' => $this->when($this->created_at, function(){
                return $this->created_at->diffForHumans();
            }),
            'updated_date' => $this->when($this->updated_at,function() {return $this->updated_at->format('M d, Y');}),
            'created_date' => $this->when($this->created_at,function() {return $this->created_at->format('M d, Y');})
           ];
    }
}
