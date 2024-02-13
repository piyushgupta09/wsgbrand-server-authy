<?php

namespace Fpaipl\Authy\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'verified' => isset($this->email_verified_at),
            'image' => $this->getFirstMediaUrl('profile'),
            'roles' => $this->getMyRoles(),
            'role' => $this->getMyMainRole(),
            'prefix' => $this->getMyPrefix(),
        ];
    }
}
