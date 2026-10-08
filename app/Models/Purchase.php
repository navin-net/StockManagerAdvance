<?php

namespace App\Models;

use App\Models\PurchaseItem;
use App\Models\Payment;
use App\Models\Companies;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static count()
 */
class Purchase extends Model
{
    protected $fillable = [
        'supplier_id',
        'total_amount',
        'date',
        'payment_status',
        'attachments',
        'note',
        'reference',
        'status'
    ];

    public function items()
    {
        return $this->hasMany(PurchaseItem::class, 'purchase_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Companies::class, 'supplier_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'purchase_id');
    }
}
