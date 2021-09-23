<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function images() {
        return $this->hasMany(Image::class);
    }

    protected $fillable = [
        'title',
        'description',
    ];

    public static function search_by_title($title = null)
    {
        $query = self::query();

        if ($title) {
            $title = strtolower($title);
            $query->whereRaw('lower(title) like "%' . $title . '%"');
        }

        return $query;
    }
}
