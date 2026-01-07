<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\{Products, Purchase};

class PurchaseItem extends Model
{
  protected $fillable = [
        'purchase_id',
        'product_id',
        'quantity',
        'cost_price'
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}
