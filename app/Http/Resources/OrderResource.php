<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
        'numero orden' => $this->codigo,
        'iva' => $this->total * 0.13,
        'total' => $this->total,
        'customer' => new CustomerResource($this->customer)

        ];
    }
}
