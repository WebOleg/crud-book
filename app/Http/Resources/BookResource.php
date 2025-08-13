<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'publication_date' => $this->publication_date?->format('Y-m-d'),
            'image_url' => $this->image_path ? asset('storage/' . $this->image_path) : null,
            'authors' => AuthorResource::collection($this->whenLoaded('authors')),
            'authors_count' => $this->when($this->authors_count !== null, $this->authors_count),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
