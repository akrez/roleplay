<?php

namespace App\Http\Resources\User;

use App\Http\Resources\JsonResource;
use Illuminate\Http\Request;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(?Request $request = null)
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'name' => $this->name,
        ];
    }
}
