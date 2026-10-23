<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Brand, Categories, ProductImage, Qualitys, SubCategory, Units};
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static count()
 */
class Products extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'stock_quantity',
        'expiry_date',
        'cost_price',
        'selling_price',
        'second_name',
        'unit_id',
        'image', // main image (optional if you use gallery)
        'brand_id',
        'category_id',
        'subcategory_id',
        'quality_id',
    ];
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Units::class);
    }


    public function category(): BelongsTo
    {
        // return $this->belongsTo(Categories::class);
        return $this->belongsTo(Categories::class, 'category_id');

    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class, 'subcategory_id');
    }

    public function quality(): BelongsTo
    {
        return $this->belongsTo(Qualitys::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class, 'product_id');
    }

}
