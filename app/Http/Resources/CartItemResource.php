<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
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
            'product' => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'code' => $this->product->code,
                'selling_price' => $this->product->selling_price,
                'image' => $this->product->image,
                'stock_quantity' => $this->product->stock_quantity,
            ],
            'quantity' => $this->quantity,
            'price' => $this->price,
            'subtotal' => round($this->quantity * $this->price, 2),
        ];
    }
}
