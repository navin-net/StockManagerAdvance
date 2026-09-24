<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegisterCashMovements extends Model
{
    use HasFactory;

    public $timestamps = false; // only created_at is tracked, no updates expected


    protected $fillable = [
        'register_id',
        'user_id',
        'type',
        'amount',
        'reason',
        'created_at',
    ];

    protected $casts = [
        'amount'     => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function register(): BelongsTo
    {
        return $this->belongsTo(PosRegisters::class, 'register_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
