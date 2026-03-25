<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = ['reference','warehouse_id', 'customer_id', 'user_id','total_amount', 'status', 'date', 'status', 'payment_status', 'cash_register_id'];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function customer()
    {
        return $this->belongsTo(Companies::class,'customer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }




    public static function generateReference(): string
    {
        $prefix = 'POS-' . now()->format('Ymd') . '-';

        $last = static::where('reference', 'like', $prefix . '%')

        ->orderByDesc('id')->value('reference');

        $next = $last ? ((int) str_replace($prefix, '', $last)) + 1 : 1;

        if ($next > 100) {
            $next = 1;
        }

        return $prefix . str_pad($next, 3, '0', STR_PAD_LEFT);
    }






}
