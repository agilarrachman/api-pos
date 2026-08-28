<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'code' => $this->code,
            'customer_id' => $this->customer_id,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'items' => TransactionItemResource::collection($this->whenLoaded('items')),
            'subtotal' => (float)(string) $this->subtotal,
            'tax' => (float)(string) $this->tax,
            'total' => (float)(string) $this->total,
            'created_at' => $this->created_at,
        ];
    }
}
