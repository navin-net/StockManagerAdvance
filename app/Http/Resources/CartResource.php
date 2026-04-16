<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'cart_key' => $this->cart_key,
            'items_count' => $this->items->sum('quantity'),
            'total_price' => round($this->items->sum(fn($item) => $item->quantity * $item->price), 2),
            'items' => CartItemResource::collection($this->items),
        ];
    }
}
