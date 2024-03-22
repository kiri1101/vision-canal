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
        // return parent::toArray($request);
        return [
            'id' => $this->uuid,
            'full_name' => $this->name,
            'phone_number' => $this->phone,
            'home_address' => $this->profile->address,
            'city' => $this->profile->state,
            'country_of_origin' => $this->profile->country,
            'profession' => $this->profile->profession,
            'referrer_code' => $this->profile->promo_code,
            'religion' => $this->profile->religion,
            'profile_path' => $this->profile->profile_photo_url
        ];
    }
}
