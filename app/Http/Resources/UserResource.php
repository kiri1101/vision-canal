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
            'id' => $this->uuid,
            'full_name' => $this->name,
            'mail' => $this->isParamNull($this->email),
            'phone_number' => $this->phone,
            'role' => $this->is_admin ? ['id' => 1, 'name' => 'Admin'] : ['id' => 2, 'name' => 'User'],
            'home_address' => $this->isParamNull($this->profile->address),
            'city' => $this->isParamNull($this->profile->state),
            'country_of_origin' => $this->isParamNull($this->profile->country),
            'profession' => $this->isParamNull($this->profile->profession),
            // 'referrer_code' => $this->isParamNull($this->profile->promo_code),
            'religion' => $this->isParamNull($this->profile->religion),
            'profile_path' => $this->isParamNull($this->profile->profile_photo_url),
            'account' => $this->account->balance
        ];
    }

    private function isParamNull($arg)
    {
        return empty($arg) ? '' : $arg;
    }
}
