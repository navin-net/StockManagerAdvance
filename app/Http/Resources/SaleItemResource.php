<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleItemResource extends JsonResource
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
            'sale_id' => $this->sale_id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'sale_price' => $this->sale_price,
            'subtotal' => round($this->quantity * $this->sale_price, 2),

            'product' => [
                'id' => $this->product->id ?? null,
                'name' => $this->product->name ?? 'Unknown Product',
                'code' => $this->product->code ?? '',
            ],
        ];

    }
}
