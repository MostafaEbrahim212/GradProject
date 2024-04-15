<?php

namespace App\Http\Resources;

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
            'profile' => [
                'first_name' => $this->profile->first_name,
                'last_name' => $this->profile->last_name,
                'mobile' => $this->profile->mobile,
                'picture' => $this->profile->picture,
                'address' => $this->profile->address,
                'city' => $this->profile->city,
            ]
        ];
    }
}
