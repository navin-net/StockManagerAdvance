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
            'id' => $this->id,
            'profile' => [
                'id' => $this->profile->id ?? null,
                'image' => $this->profile->image ?? null,
                'dob' => $this->profile->dob ?? null,
                'user_id' => $this->profile->user_id ?? null,
            ],
            'group_id' => $this->group_id,
            'groups' => [
                'id' => $this->groups->id ?? 'who are you',
                'name' => $this->groups->name ?? null,
            ],
            'company_id' => $this->company_id,
            'company' => [
                'id' => $this->company->id ?? null,
                'name' => $this->company->name ?? null,
            ],
            'name' => $this->name,
            'email' => $this->email,
            'ip_address' => $this->ip_address,
        ];
    }
}
