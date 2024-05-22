<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'title'    => $this->title,
            'text'     => Str::limit($this->text, 150),
            'fulltext' => $this->text,
            'image'    => asset('storage/'.$this->image),
            'date'     => $this->created_at->diffForHumans(),
        ];
    }
}
