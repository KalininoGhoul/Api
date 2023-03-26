<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UsersCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->map(function (User $user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'login' => $user->login,
                'status' => $user->status?->status,
                'group' => $user->group?->group,
            ];
        });
    }
}
