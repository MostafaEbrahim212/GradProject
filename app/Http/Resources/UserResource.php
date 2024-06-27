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
            'has_recommendation' => $this->has_recommendation,
            'request_status' => $this->request_status,
            'is_charity' => $this->is_charity,
            'permessions' => [
                'can_create' => $this->chairites_permessions->can_create ?? null,
                'can_read' => $this->chairites_permessions->can_read ?? null,
                'can_update' => $this->chairites_permessions->can_update ?? null,
                'can_delete' => $this->chairites_permessions->can_delete ?? null,
            ],
            'charity_info' => [
                'name' => $this->charity_info->name ?? null,
                'address' => $this->charity_info->address ?? null,
                'picture' => $this->charity_info->picture ?? null,
                'description' => $this->charity_info->description ?? null,
                'charity_type' => $this->charity_info->charity_type ?? null,
                'financial_license' => $this->charity_info->financial_license ?? null,
                'financial_license_image' => $this->charity_info->financial_license_image ?? null,
                'ad_number' => $this->charity_info->ad_number ?? null,

            ]
        ];
    }
}
