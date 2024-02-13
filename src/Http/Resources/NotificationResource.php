<?php

namespace Fpaipl\Authy\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->data,
            'id' => $this->id,
            'created_at' => $this->created_at->diffForHumans(),
            'read_at' => $this->read_at ? $this->read_at->diffForHumans() : 'unread',
        ];
    }
}
