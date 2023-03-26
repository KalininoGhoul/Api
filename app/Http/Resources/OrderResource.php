<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'table' => 'Столик №' . $this->table->number,
            'shift_workers' => $this->user->name,
            'create_at' => $this->create_at,
            'status' => $this->orderStatus->name,
            'price' => $this->price,
        ];
    }
}
