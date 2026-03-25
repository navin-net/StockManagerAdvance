<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\{Purchase, Sale, User};

class Payment extends Model
{


    public $timestamps = false;

    protected $fillable = [
        'sale_id',
        'purchase_id',
        'reference',
        'method',
        'amount',
        'paid_at',
        'note',
        'attachment',
        'pos_paid',
        'pos_balance',
        'created_by',
    ];


    protected $casts = [
        'paid_at' => 'datetime',
    ];



    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }



}
