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
                'first_name' => $this->profile->first_name ?? null,
                'last_name' => $this->profile->last_name ?? null,
                'mobile' => $this->profile->mobile ?? null,
                'picture' => $this->profile->picture ?? null,
                'address' => $this->profile->address ?? null,
                'city' => $this->profile->city ?? null,
            ],
            'request_status' => $this->request_status,
            'is_chairty' => $this->is_chairty,
            'permessions' => [
                'can_create' => $this->chairites_permessions->can_create ?? null,
                'can_read' => $this->chairites_permessions->can_read ?? null,
                'can_update' => $this->chairites_permessions->can_update ?? null,
                'can_delete' => $this->chairites_permessions->can_delete ?? null,
            ],
            'chairty_info' => [
                'name' => $this->chairty_info->name ?? null,
                'address' => $this->chairty_info->address ?? null,
                'picture' => $this->chairty_info->picture ?? null,
                'description' => $this->chairty_info->description ?? null,
                'chairty_type' => $this->chairty_info->chairty_type ?? null,
                'financial_license' => $this->chairty_info->financial_license ?? null,
                'financial_license_image' => $this->chairty_info->financial_license_image ?? null,
                'ad_number' => $this->chairty_info->ad_number ?? null,

            ]
        ];
    }
}
