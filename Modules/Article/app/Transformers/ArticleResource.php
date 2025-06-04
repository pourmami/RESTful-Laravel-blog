<?php

namespace Modules\Article\app\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Category\app\Transformers\CategoryResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'status' => $this->status,
            'published_at' => $this->published_at,
            'category' => new CategoryResource($this->whenLoaded('category')),
        ];
    }
}
