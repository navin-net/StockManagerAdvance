<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static create(array $array)
 * @method static where(string $string, mixed $companyId)
 */
class Companies extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'group_id', 'group_name',
    'email', 'phone','city','number_of_houses','address','street','country',
    'warehouse_id','note','logo'];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Groups::class, 'group_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouses::class, 'warehouse_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'company_id');
    }





}
