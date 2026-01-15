<?php

namespace App\Models;

use App\Models\Products;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_review',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    // Each image belongs to one product
    public function product()
    {
        return $this->belongsTo(Products::class);
    }
}
