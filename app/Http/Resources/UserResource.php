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
            'name'=> $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'last_name' => $this->last_name,
            'first_name' => $this->first_name,
            'bakehouse_id' => $this->bakehouse_id,
            'bakehouse' => $this->bakehouse()->select("id","name","address","phone","responsable","nb_delivery_person","uuid")->first(),
            'phone' => $this->phone,
            'active' => $this->active,
            'role'=> $this->roles()->pluck('name')->first(),
            'permissions' => $this->abilityList(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
