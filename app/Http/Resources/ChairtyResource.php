<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChairtyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->user_id,
            'user' => $this->user->name,
            'id' => $this->id,
            'name' => $this->name,
            'picture' => $this->picture,
            'address' => $this->address,
            'chairty_type' => $this->chairty_type,
            'description' => $this->description,
            'financial_license' => $this->financial_license,
            'financial_license_image' => $this->financial_license_image,
            'ad_number' => $this->ad_number,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
