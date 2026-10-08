<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static find(int $int)
 */
class Groups extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }



}
