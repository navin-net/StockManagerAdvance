<?php

namespace App\Models;

use App\Models\PosRegisters;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static count()
 * @method static sum(string $string)
 */
class Sale extends Model
{
    use HasFactory;
    protected $fillable = [
        'reference',
        'warehouse_id',
        'customer_id',
        'user_id',
        'cash_register_id',
        'sale_type',
        'biller_id',
        'payment_method',
        // ── Before / After ─────────────────
        'subtotal',        // 👈 WAS MISSING
        'discount',
        'discount_value',
        'discount_type',
        // ───────────────────────────────────

        'total_amount',
        'payment_status',
        'status',
        'date',
    ];

    protected $casts = [
        'date'           => 'datetime',
        'subtotal'       => 'decimal:2',
        'discount'       => 'decimal:2',
        'discount_value' => 'decimal:2',
        'total_amount'   => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(PosRegisters::class,'cash_register_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Companies::class,'customer_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }




    public static function generateReference(): string
    {
        return 'SALE-' . now()->format('Ymd') . '-' . \Illuminate\Support\Str::ulid();
    }






}
