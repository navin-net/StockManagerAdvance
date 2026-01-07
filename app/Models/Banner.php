<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'status', 'link', 'image'];

    public static function findByIdAndName($id)
    {
        return self::where('id', $id)
            ->firstOrFail();
    }

    public static function updateByIdAndName($id, array $data)
    {
        $banner = self::where('id', $id)
            ->firstOrFail();

        $banner->update($data);

        return $banner;
    }

}
