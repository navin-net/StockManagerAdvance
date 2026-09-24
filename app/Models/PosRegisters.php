<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PosRegisters extends Model
{
    use HasFactory;
//    protected $table = 'sma_pos_registers';

    public $timestamps = false;

    protected $fillable = [
        'reference',
        'user_id',
        'cash_in_hand',
        'total_cash',
        'status',
        'note',
        'closed_by',
        'opened_at',
        'closed_at',
    ];

    protected $casts = [
        'cash_in_hand' => 'decimal:2',
        'total_cash'   => 'decimal:2',
        'opened_at'    => 'datetime',
        'closed_at'    => 'datetime',
    ];


    public function sales()
    {
        return $this->hasMany(
            Sale::class,
            'cash_register_id'
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function closedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function movements()
    {
        return $this->hasMany(RegisterCashMovements::class, 'register_id');
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }





}
