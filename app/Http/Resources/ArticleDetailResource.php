<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'category' => new categoryResource($this->category),
            'writer' => new writerResource($this->writer),
            'prologue' => $this->prologue,
            'content' => $this->content,
            'thumbnail' => $this->thumbnail,
            'created_at' => date_format($this->created_at, 'l, j M Y, H:i T'),
            'comments' => CommentResource::collection($this->comments)
        ];
    }
}
